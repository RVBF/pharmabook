<?php

/**
 *	Login
 *
 *  @author Rafael Vinicius Barros Ferreira
 *  @version	0.1
 */
class Login {
	
	private $identificador;
	private $senha;

	function __construct($identificador = '' , $senha = '')
	{ 
		$this->identificador = $identificador;
		$this->senha = $senha;
 	}
	
	public function getIdentificador(){ return $this->identificador; }
	public function setIdentificador($identificador){ $this->identificador = $identificador; }
	
	public function getSenha(){ return $this->senha; }
	public function setSenha($senha){ $this->senha = $senha; }
}

?>