<?php

use phputil\Session;

/**
* Serviço de Login
*
* @author	Rafael Vinicius barros ferreira
*/

class ServicoLogin {
	
	private $sessao;
	private $colecao;
	
	function __construct(ColecaoUsuario $colecao, Session $sessao)
	{
		$this->colecao = $colecao;
		$this->sessao = $sessao;
	}
}

?>