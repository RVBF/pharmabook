<?php

/**
 *	Bairro
 *
 *  @author Irlon Lamblet
 *  @version	1.0
 */
class Bairro {
	private $id;
	private $nome;
	private $cidade;

	function __construct($id = '', $nome = '', $cidade = '')
	{ 
		$this->id = (int) $id;
		$this->nome =  $nome;
		$this->cidade = $cidade;
 	}
	
	public function getId(){ return $this->id; }
	public function setId($id){ $this->id = $id; }
	
	public function getNome(){ return $this->nome; }
	public function setNome($nome){ $this->nome = $nome; }

	public function getCidade(){ return $this->cidade; }
	public function setCidade($cidade){ $this->cidade = $cidade; }
}

?>