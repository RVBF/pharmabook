<?php

/**
 *	Favorito
 *
 *  @author Rafael Vinicius Barros Ferreira
 *  @version	0.1
 */
class Favorito {
	
	private $id;
	private $medicamentoPrecificado;
	private $usuario;

	function __construct($id = '', $medicamentoPrecificado = '', $usuario = '')
	{ 
		$this->id =  (int) $id;
		$this->medicamentoPrecificado =  $medicamentoPrecificado;
		$this->usuario =  $usuario;
 	}
	
	public function getId(){ return $this->id; }
	public function setId($id){ $this->id = $id; }
	
	public function getMedicamentoPrecificado(){ return $this->medicamentoPrecificado; }
	public function setMedicamentoPrecificado($medicamentoPrecificado){ $this->medicamentoPrecificado = $medicamentoPrecificado; }

	public function getUsuario(){ return $this->usuario; }
	public function setUsuario($usuario){ $this->usuario = $usuario; }
}

?>