<?php

/**
 * Controladora de Usuario
 *
 * @author	Rafael Vinicius Barros Ferreira
 */
class ControladoraUsuario {

	private $geradoraResposta;
	private $params;
	private $colecaoUsuario;
	private $colecaoEstoque;
	private $servicoLogin;
	private $sessao;
	private $colecaoEnderecoEntidade;
	private $colecaoEndereco;
	private $colecaoCidade;
	private $colecaoBairro;
	private $colecaoEstado;
	private $colecaoPais;
	private $servicoEndereco;

	function __construct(GeradoraResposta $geradoraResposta,  $params, $sessaoUsuario)
	{
		$this->geradoraResposta = $geradoraResposta;
		$this->params = $params;
		$this->sessao = $sessaoUsuario;
		$this->colecaoUsuario = DI::instance()->create('ColecaoUsuario');
		$this->colecaoEnderecoEntidade = DI::instance()->create('ColecaoEnderecoEntidade');
		$this->colecaoEndereco = DI::instance()->create('ColecaoEndereco');
		$this->colecaoCidade = DI::instance()->create('ColecaoCidade');
		$this->colecaoBairro = DI::instance()->create('ColecaoBairro');
		$this->colecaoEstado = DI::instance()->create('ColecaoEstado');
		$this->colecaoPais = DI::instance()->create('ColecaoPais');
		$this->servicoEndereco = new ServicoEndereco();
		$this->servicoLogin = new ServicoLogin($this->sessao, $this->colecaoUsuario);
	}

	function remover()
	{
		if($this->servicoLogin->verificarSeUsuarioEstaLogado()  == false)
		{
			return $this->geradoraResposta->naoAutorizado('Erro ao acessar página.', GeradoraResposta::TIPO_TEXTO);
		}

		try
		{
			$id = \ParamUtil::value($this->params, 'id');

			if (! is_numeric($id))
			{
				$msg = 'O id informado não é numérico.';
				return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
			}

			$this->colecaoUsuario->remover($id);

			$this->servicoLogin->logout();

			return $this->geradoraResposta->semConteudo();
		}
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}
	}

	function adicionar()
	{
		$inexistentes = \ArrayUtil::nonExistingKeys([
			'id',
			'cep',
			'logradouro',
			'numero',
			'complemento',
			'referencia',
			'bairro',
			'cidade',
			'estado',
			'latitude',
			'longitude'
		], $this->params['endereco']);

		if (count($inexistentes) > 0)
		{
			$msg = 'Os seguintes campos não foram enviados: ' . implode(', ', $inexistentes);
			return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
		}

		$endereco = $this->colecaoEndereco->comCep(
			\ParamUtil::value($this->params['endereco'], 'cep')
		);

		if(empty($endereco ))
		{
			$endereco= $this->servicoEndereco->consultarCepOnline(\ParamUtil::value($this->params['endereco'], 'cep'));

			if(empty($endereco))
			{
				$cidade = new Cidade(
					\ParamUtil::value($this->params['endereco'], 'id'),
					\ParamUtil::value($this->params['endereco'], 'nome'),
					\ParamUtil::value($this->params['endereco'], 'estado')
				);

				$this->colecaoCidade->adicionar($cidade);

				$bairro = new Bairro(
					\ParamUtil::value($this->params['endereco'], 'id'),
					\ParamUtil::value($this->params['endereco'], 'nome'),
					$cidade
				);

				$this->colecaoBairro->adicionar($bairro);

				$endereco = new Endereco(
					\ParamUtil::value($this->params['endereco'], 'id'),
					\ParamUtil::value($this->params['endereco'], 'cep'),
					\ParamUtil::value($this->params['endereco'], 'logradouro'),
					\ParamUtil::value($this->params['endereco'], 'latitude'),
					\ParamUtil::value($this->params['endereco'], 'longitude'),
					'',
					$bairro
				);

				$this->colecaoEndereco->adicionar($endereco);
			}
		}

		$enderecoEntidade = new EnderecoEntidade(
			0,
			\ParamUtil::value($this->params['endereco'], 'numero'),
			\ParamUtil::value($this->params['endereco'], 'complemento'),
			\ParamUtil::value($this->params['endereco'], 'referencia'),
			is_array($endereco) ? $endereco[0] : $endereco
		);

		$this->colecaoEnderecoEntidade->adicionar($enderecoEntidade);

		$inexistentes = \ArrayUtil::nonExistingKeys([
			'id',
			'nome',
			'sobrenome',
			'email',
			'login',
			'senha',
		], $this->params);

		if (count($inexistentes) > 0)
		{
			$msg = 'Os seguintes campos não foram enviados: ' . implode(', ', $inexistentes);
			return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
		}

		$objUsuario = new Usuario(
			\ParamUtil::value($this->params, 'id'),
			\ParamUtil::value($this->params, 'nome'),
			\ParamUtil::value($this->params, 'sobrenome'),
			\ParamUtil::value($this->params, 'email'),
			\ParamUtil::value($this->params, 'login'),
			\ParamUtil::value($this->params, 'senha'),
			$enderecoEntidade
		);

		try
		{
			$this->colecaoUsuario->adicionar($objUsuario);

			$this->servicoLogin->login($objUsuario->getLogin(), \ParamUtil::value($this->params, 'senha'));

			if($this->servicoLogin->verificarSeUsuarioEstaLogado()) $objUsuario->estaLogado(true);
		}
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}

		return $this->geradoraResposta->resposta(json_encode(['estaLogado' => $objUsuario->getLogado()]), GeradoraResposta::CRIADO, GeradoraResposta::TIPO_JSON);
	}

	function atualizar()
	{
		if($this->servicoLogin->verificarSeUsuarioEstaLogado()  == false)
		{
			return $this->geradoraResposta->naoAutorizado('Erro ao acessar página.', GeradoraResposta::TIPO_TEXTO);
		}

		$inexistentes = \ArrayUtil::nonExistingKeys([
			'id',
			'nome',
			'sobrenome',
			'sobrenome',
			'email',
			'login'
		], $this->params);

		if (count($inexistentes) > 0)
		{
			$msg = 'Os seguintes campos não foram enviados: ' . implode(', ', $inexistentes);
			return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
		}

		$obj = new Usuario(
			\ParamUtil::value($this->params, 'id'),
			\ParamUtil::value($this->params, 'nome'),
			\ParamUtil::value($this->params, 'sobrenome'),
			\ParamUtil::value($this->params, 'email'),
			\ParamUtil::value($this->params, 'login')
		);
		try
		{
			$this->colecaoUsuario->atualizar($obj);
			return $this->geradoraResposta->semConteudo();
		}
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}
	}

	function novaSenha()
	{
		if($this->servicoLogin->verificarSeUsuarioEstaLogado()  == false)
		{
			return $this->geradoraResposta->naoAutorizado('Erro ao acessar página.', GeradoraResposta::TIPO_TEXTO);
		}

		$inexistentes = \ArrayUtil::nonExistingKeys([
			'senhaAtual',
			'novaSenha',
			'confirmacaoSenha'
		], $this->params);

		if (count($inexistentes) > 0)
		{
			$msg = 'Os seguintes campos não foram enviados: ' . implode(', ', $inexistentes);
			return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
		}

		try
		{
			$usuario = $this->colecaoUsuario->comId($this->servicoLogin->getIdUsuario());

			if(empty($usuario)) throw new Exception("Usuário não encontrado.");

			$this->colecaoUsuario->setUsuario($usuario);

			$this->colecaoUsuario->novaSenha(
				\ParamUtil::value($this->params, 'senhaAtual'),
				\ParamUtil::value($this->params, 'novaSenha'),
				\ParamUtil::value($this->params, 'confirmacaoSenha')
			);
			return $this->geradoraResposta->semConteudo();
		}
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}
	}

	function getUsuarioSessao()
	{
		if($this->servicoLogin->verificarSeUsuarioEstaLogado()  == false)
		{
			return $this->geradoraResposta->naoAutorizado('Erro ao acessar página.', GeradoraResposta::TIPO_TEXTO);
		}

		try
		{
			$usuario = $this->colecaoUsuario->comId($this->servicoLogin->getIdUsuario());

			if($usuario != null)
			{
				return $this->geradoraResposta->ok(JSON::encode($usuario), GeradoraResposta::TIPO_JSON);
			}
			else
			{
				throw new Exception("Usuário não encontrado.");
			}
		}
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}
	}

	function comId()
	{
		if($this->servicoLogin->verificarSeUsuarioEstaLogado()  == false)
		{
			return $this->geradoraResposta->naoAutorizado('Erro ao acessar página.', GeradoraResposta::TIPO_TEXTO);
		}

		try
		{
			$id = (int) \ParamUtil::value($this->params, 'id');

			if (!is_int($id))
			{
				$msg = 'O id informado não é um número inteiro.';
				return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
			}

			$usuario = $this->colecaoUsuario->comId($id);

			if(!empty($usuario))
			{
				$usuario->setDataCriacao($usuario->getDataCriacao()->toBrazilianString());
				$usuario->setDataAtualizacao($usuario->getDataAtualizacao()->toBrazilianString());
			}
		}
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}

		return $this->geradoraResposta->resposta(JSON::encode($usuario), GeradoraResposta::OK, GeradoraResposta::TIPO_JSON);
	}
}

?>