<?php

/**
 *	Pais
 *
 *  @author Rafael Vinicius Barros Ferreira
 *  @version	0.1
 */
class Pais {
	
	private $id;
	private $nome;

	function __construct($id = '', $nome = '')
	{ 
		$this->id =  $id;
		$this->nome =  $nome;
 	}
	
	public function getId(){ return $this->id; }
	public function setId($id){ $this->id = $id; }
	
	public function getNome(){ return $this->nome; }
	public function setNome($nome){ $this->nome = $nome; }
}

?>