<?php

use phputil\Session;

/**
* Serviço de login
*
* @author	Rafael Vinicius barros ferreira
*/

class ServicoLoginUsuario {
	
	private $sessao;
	private $colecao;
	
	function __construct(ColecaoUsuario $colecao, Session $sessao)
	{
		Debuger::printr($sessao);
		$this->colecao = $colecao;
		$this->sessao = $sessao;
	}
}

?>