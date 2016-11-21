<?php

/**
 * Controladora de sessão
 *
 * @author	Rafael Vinicicus Barros Ferreira
 */

class ControladoraSessao {

	private $geradoraResposta;
	private $params;
	private $servico;
	private $colecaoUsuario;
	private $sessao;
	
	function __construct(GeradoraResposta $geradoraResposta, $params, Sessao $sessao)
	{
		$this->geradoraResposta = $geradoraResposta;
		$this->params = $params;
		$this->sessao = $sessao;
		$this->colecaoUsuario = DI::instance()->create('ColecaoUsuario');
		$this->servico = new ServicoLogin($this->sessao, $this->colecaoUsuario);
	}

	/**
	 *	Método que pega os parâmetros login e senha da requisição 
	 * e os utiliza no método logar do serviço do usuario. 
	 * 
	 * @return geradoraResposta->erro 			Caso o array de parâmetros esteja vazio.
	 * @return geradoraResposta->semConteudo 	Caso o login seja efetuado corretamente.
	 * @throws Exception
	 */
	
	function estaAtiva()
	{				
		try 
		{
			if($this->servico->estaLogado())
			{
				$this->servico->atualizaAtividadeUsuario();
			}
			else
			{
				return $this->geradoraResposta->naoAutorizado('Erro ao acessar página.', GeradoraResposta::TIPO_TEXTO);
			}		
		}
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}
	}
}
