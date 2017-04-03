<?php

/**
 * Controladora de Farmácia
 *
 * @author	Rafael Vinicius Barros Ferreira
 *  @version	0.1
 */
class ControladoraFarmacia {

	private $geradoraResposta;
	private $params;
	private $colecaoFarmacia;
	private $colecaoEndereco;
	private $servicoLogin;
	private $sessao;

	function __construct(GeradoraResposta $geradoraResposta,  $params, $sessao)
	{
		$this->geradoraResposta = $geradoraResposta;
		$this->params = $params;
		$this->sessao = $sessao;
		$this->servicoLogin = new ServicoLogin($this->sessao);
		$this->colecaoFarmacia = DI::instance()->create('ColecaoFarmacia');
		$this->colecaoEndereco = DI::instance()->create('ColecaoEndereco');
	}

	function todos()
	{
		if($this->servicoLogin->verificarSeUsuarioEstaLogado()  == false)
		{
			return $this->geradoraResposta->naoAutorizado('Erro ao acessar página.', GeradoraResposta::TIPO_TEXTO);
		}

		$dtr = new \DataTablesRequest($this->params);
		$contagem = 0;
		$objetos = [];
		$erro = null;

		$dtr = new \DataTablesRequest($this->params);
		$contagem = 0;
		$objetos = [];
		$erro = null;

		try
		{
			$contagem = $this->colecaoFarmacia->contagem();

			$objetos = $this->colecaoFarmacia->todos($dtr->limit(), $dtr->offset());

			$resposta = [];

			foreach ($objetos as $objeto)
			{
				$objeto->setDataCriacao($objeto->getDataCriacao()->toBrazilianString());
				$objeto->setDataAtualizacao($objeto->getDataAtualizacao()->toBrazilianString());

				$endereco = $this->colecaoEndereco->comId($objeto->getEndereco());
				if($endereco !=  null)
				{
					$endereco->setDataCriacao($endereco->getDataCriacao()->toBrazilianString());
					$endereco->setDataAtualizacao($endereco->getDataAtualizacao()->toBrazilianString());

					$objeto->setEndereco($endereco);
				}

				array_push($resposta, $objeto);
			}
		}
		catch (\Exception $e ) {
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}

		$conteudo = new \DataTablesResponse(
			$contagem,
			$contagem, //count($objetos ),
			$resposta,
			$dtr->draw(),
			$erro
		);

		return $this->geradoraResposta->ok(JSON::encode($conteudo), GeradoraResposta::TIPO_JSON);
	}

	function adicionar()
	{
		if($this->servicoLogin->verificarSeUsuarioEstaLogado()  == false)
		{
			return $this->geradoraResposta->naoAutorizado('Erro ao acessar página.', GeradoraResposta::TIPO_TEXTO);
		}

		try
		{
			$inexistentes = \ArrayUtil::nonExistingKeys([
				'id',
				'nome',
				'telefone',
				'endereco'
			], $this->params);

			$inexistentes += \ArrayUtil::nonExistingKeys([
				'id',
				'cep',
				'logradouro',
				'numero',
				'complemento',
				'referencia',
				'bairro',
				'cidade',
				'estado',
				'pais'
			], $this->params['endereco']);

			if (count($inexistentes) > 0)
			{
				$msg = 'Os seguintes campos não foram enviados: ' . implode(', ', $inexistentes);
				return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
			}

			$objEndereco = new Endereco(
				\ParamUtil::value($this->params['endereco'], 'id'),
				\ParamUtil::value($this->params['endereco'], 'cep'),
				\ParamUtil::value($this->params['endereco'], 'logradouro'),
				\ParamUtil::value($this->params['endereco'], 'numero'),
				\ParamUtil::value($this->params['endereco'], 'complemento'),
				\ParamUtil::value($this->params['endereco'], 'referencia'),
				\ParamUtil::value($this->params['endereco'], 'bairro'),
				\ParamUtil::value($this->params['endereco'], 'cidade'),
				\ParamUtil::value($this->params['endereco'], 'estado'),
				\ParamUtil::value($this->params['endereco'], 'pais')
			);

			$this->colecaoEndereco->adicionar($objEndereco);

			$objFarmacia = new Farmacia(
				\ParamUtil::value($this->params,'id'),
				\ParamUtil::value($this->params,'nome'),
				\ParamUtil::value($this->params,'telefone'),
				$objEndereco
			);

			$this->colecaoFarmacia->adicionar($objFarmacia);

			return $this->geradoraResposta->semConteudo();
		}
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}
	}

	function atualizar()
	{
		if($this->servicoLogin->verificarSeUsuarioEstaLogado()  == false)
		{
			return $this->geradoraResposta->naoAutorizado('Erro ao acessar página.', GeradoraResposta::TIPO_TEXTO);
		}

		try
		{
			$inexistentes = \ArrayUtil::nonExistingKeys([
				'id',
				'nome',
				'telefone',
				'endereco'
			], $this->params);

			$inexistentes += \ArrayUtil::nonExistingKeys([
				'id',
				'cep',
				'logradouro',
				'numero',
				'complemento',
				'referencia',
				'bairro',
				'cidade',
				'estado',
				'pais'
			], $this->params['endereco']);

			if (count($inexistentes) > 0)
			{
				$msg = 'Os seguintes campos não foram enviados: ' . implode(', ', $inexistentes);
				return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
			}

			$objEndereco = new Endereco(
				\ParamUtil::value($this->params['endereco'], 'id'),
				\ParamUtil::value($this->params['endereco'], 'cep'),
				\ParamUtil::value($this->params['endereco'], 'logradouro'),
				\ParamUtil::value($this->params['endereco'], 'numero'),
				\ParamUtil::value($this->params['endereco'], 'complemento'),
				\ParamUtil::value($this->params['endereco'], 'referencia'),
				\ParamUtil::value($this->params['endereco'], 'bairro'),
				\ParamUtil::value($this->params['endereco'], 'cidade'),
				\ParamUtil::value($this->params['endereco'], 'estado'),
				\ParamUtil::value($this->params['endereco'], 'pais')
			);

			$this->colecaoEndereco->atualizar($objEndereco);

			$objFarmacia = new Farmacia(
				\ParamUtil::value($this->params,'id'),
				\ParamUtil::value($this->params,'nome'),
				\ParamUtil::value($this->params,'telefone'),
				$objEndereco
			);

			$this->colecaoFarmacia->atualizar($objFarmacia);

			return $this->geradoraResposta->semConteudo();
		}
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}
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

			if (!is_numeric($id))
			{
				$msg = 'O id informado não é numérico.';
				return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
			}

			$farmacia = $this->colecaoFarmacia->comId($id);

			if(!$this->colecaoFarmacia->remover($farmacia->getId())) throw new Exception("Não foi possível deletar a farmácia.");

			if(!$this->colecaoEndereco->remover($farmacia->getEndereco())) throw new Exception("Não foi possível deletar o endereço");

			return $this->geradoraResposta->semConteudo();
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

			$farmacia = $this->colecaoFarmacia->comId($id);

			if(!empty($farmacia))
			{
				$farmacia->setDataCriacao($farmacia->getDataCriacao()->toBrazilianString());
				$farmacia->setDataAtualizacao($farmacia->getDataAtualizacao()->toBrazilianString());

				$endereco = $this->colecaoEndereco->comId($farmacia->getEndereco());
				if($endereco !=  null)
				{
					$endereco->setDataCriacao($endereco->getDataCriacao()->toBrazilianString());
					$endereco->setDataAtualizacao($endereco->getDataAtualizacao()->toBrazilianString());

					$farmacia->setEndereco($endereco);
				}
			}
		}
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}

		return $this->geradoraResposta->resposta(JSON::encode($farmacia), GeradoraResposta::OK, GeradoraResposta::TIPO_JSON);
	}
}
?>