<?php

/**
 *	Estoque
 *
 *  @authoRafael Vinicius Barros Ferreira
 *  @version	0.1
 */
class Estoque {
	
	private $id;
	private $usuario;

	function __construct ($id = '', $usuario = '')
	{ 
		$this->id = $id;
		$this->usuario = $usuario;
 	}
	
	public function getId(){ return $this->id; }
	public function setId($id){ $this->id = $id; }
	
	public function getUsuario(){ return $this->usuario; }
	public function setUsuario($usuario){ $this->usuario = $usuario; }
}

?>