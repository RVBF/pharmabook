<?php

/**
 * Controladora de sessão
 *
 * @author	Rafael Vinicicus Barros Ferreira
 * @version	1.0
 */

class ControladoraSessao {

	private $servico;

	function __construct()
	{
		$this->servico = new ServicoLogin();
	}

	function estaAtiva($request, $response)
	{
		try
		{
			if($this->servico->estaLogado())
			{
				if(!$this->servico->sairPorInatividade())
				{
					$this->servico->atualizaAtividadeUsuario();

					return $this->geradoraResposta->semConteudo();
				}
				else
				{
					throw new Exception("Acesso Negado.");
				}
			}
			else
			{
				throw new Exception("Acesso Negado.");
			}
		}
		catch (\Exception $e)
		{
			return $response->withJson(['mensagem'=> $e->getMessage()], 401);
		}
	}
}
