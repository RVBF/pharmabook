<?php

/**
 *	Bairro
 *
 *  @author Rafael Vinicius Barros Ferreira
 *  @version	0.1
 */
class Bairro {
	
	private $id;
	private $nome;
	private $cidade;

	function __construct($id = '', $nome = '', $cidade = '')
	{ 
		$this->id =  $id;
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