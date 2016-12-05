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


	function __construct(GeradoraResposta $geradoraResposta,  $params, $sessaoUsuario)
	{
		$this->geradoraResposta = $geradoraResposta;
		$this->params = $params;
		$this->sessao = $sessaoUsuario;
		$this->servicoLogin = new ServicoLogin($this->sessao);
		$this->colecaoUsuario = DI::instance()->create('ColecaoUsuario');
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

			$this->colecao->remover($id);

			return $this->geradoraResposta->semConteudo();
		} 
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}
	}
	
	function adicionar()
	{
		if($this->servicoLogin->verificarSeUsuarioEstaLogado()  == false)
		{
			return $this->geradoraResposta->naoAutorizado('Erro ao acessar página.', GeradoraResposta::TIPO_TEXTO);
		}

		$inexistentes = \ArrayUtil::nonExistingKeys([
			'id',
			'nome',
			'sobrenome',
			'email',
			'login',
			'senha',
			'dataCriacao',
			'dataAtualizacao'
		], $this->params);

		if (count($inexistentes) > 0)
		{
			$msg = 'Os seguintes campos não foram enviados: ' . implode(', ', $inexistentes);
			return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
		}

		$dataCriacao = new DataUtil(\ParamUtil::value($this->params, 'dataCriacao'));
		$dataAtualizacao = new DataUtil(\ParamUtil::value($this->params, 'dataAtualizacao'));
		
		$objUsuario = new Usuario(
			\ParamUtil::value($this->params, 'id'),
			\ParamUtil::value($this->params, 'nome'),
			\ParamUtil::value($this->params, 'sobrenome'),
			\ParamUtil::value($this->params, 'email'),
			\ParamUtil::value($this->params, 'login'),
			\ParamUtil::value($this->params, 'senha'),
			$dataCriacao->formatarDataParaBanco(),
			$dataAtualizacao->formatarDataParaBanco()
		);
		
		try
		{
			$this->colecaoUsuario->adicionar($objUsuario);
		} 
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}		
		
		return $this->geradoraResposta->resposta(JSON::encode($objUsuario), GeradoraResposta::CRIADO, GeradoraResposta::TIPO_JSON);
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
			'email',
			'login',
			'senha',
			'dataCriacao',
			'dataAtualizacao'
		], $this->params);
		
		if (count($inexistentes) > 0)
		{
			$msg = 'Os seguintes campos não foram enviados: ' . implode(', ', $inexistentes);
			return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
		}

		$obj = new Usuario(
			\ParamUtil::value($this->params, 'id'),
			\ParamUtil::value($this->params, 'nome'),
			\ParamUtil::value($this->params, 'email'),
			\ParamUtil::value($this->params, 'login'),
			\ParamUtil::value($this->params, 'senha'),
			\ParamUtil::value($this->params, 'dataCriacao'),
			\ParamUtil::value($this->params, 'dataAtualizacao')
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
}

?>