<?php

use phputil\Session;

/**
 * Controladora de login do usuário
 *
 * @author	Rafael Vinicicus Barros Ferreira
 */

class ControladoraLoginUsuario {
	private $servico;
	private $geradoraResposta;
	private $params;
	private $sessao;
	
	function __construct(GeradoraResposta $geradoraResposta, $params, Session $sessao)
	{
		$this->geradoraResposta = $geradoraResposta;
		$this->params = $params;
		$this->servico = DI::instance()->create('ServicoUsuario');
		$this->sessao = $sessao;
	}

	/**
	 *	Método que pega os parâmetros login e senha da requisição 
	 * e os utiliza no método logar do serviço do usuario. 
	 * 
	 * @return geradoraResposta->erro 			Caso o array de parâmetros esteja vazio.
	 * @return geradoraResposta->semConteudo 	Caso o login seja efetuado corretamente.
	 * @throws Exception
	 */
	
	function logar()
	{
		$inexistentes = \ArrayUtil::nonExistingKeys([ 'login', 'senha' ], $this->params);
		
		if(count($inexistentes) > 0)
		{
			$msg = 'Os seguintes campos não foram enviados: ' . implode(', ', $inexistentes);
			return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
		}
		
		$login = \ParamUtil::value($this->params, 'login');
		$senha = \ParamUtil::value($this->params, 'senha');
		try 
		{
			$this->servico->logar($login, $senha);
			$usuarioSessao = $this->sessao->get('usuario');
			
			return $this->geradoraResposta->ok(json_encode($usuarioSessao), GeradoraResposta::TIPO_JSON);
		}
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}
	}

	/**
	 *	Método de Logout  que utiliza o método sair do serviço. 
	 * 
	 * @throws Exception
	 */
	function sair()
	{
		try 
		{
			$this->servico->sair();
			return $this->geradoraResposta->semConteudo();
		}
		catch(\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}
	}

	/**
	 *	Método que pega os parâmetros senha atual, nova e de confirmação da requisição 
	 * e os utiliza no método atualizarSenha do serviço. 
	 * 
	 * @return geradoraResposta->erro 			Caso o array de parâmetros esteja vazio.
	 * @return geradoraResposta->semConteudo 	Caso o login seja efetuado corretamente.
	 * @throws Exception
	 */
	function atualizarSenha()
	{
		$inexistentes = \ArrayUtil::nonExistingKeys([
			'atual',
			'nova',
			'confirmacao'
			], $this->params);
		if (count($inexistentes) > 0)
		{
			$msg = 'Os seguintes campos não foram enviados: ' . implode(', ', $inexistentes);
			return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
		}
		$atual = \ParamUtil::value($this->params, 'atual');
		$nova = \ParamUtil::value($this->params, 'nova');
		$confirmacao = \ParamUtil::value($this->params, 'confirmacao');
		try
		{
			$this->servico->atualizarSenha($atual, $nova, $confirmacao);
			return $this->geradoraResposta->semConteudo();
		}
		catch(\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}		
	}
}
