<?php

/**
 *	Estado
 *
 *  @author Rafael Vinicius Barros Ferreira
 *  @version	0.1
 */
class Estado {
	
	private $id;
	private $nome;
	private $pais;

	function __construct($id = '', $nome = '', $pais = '')
	{ 
		$this->id =  $id;
		$this->nome =  $nome;
		$this->pais = $pais;
 	}
	
	public function getId(){ return $this->id; }
	public function setId($id){ $this->id = $id; }
	
	public function getNome(){ return $this->nome; }
	public function setNome($nome){ $this->nome = $nome; }

	public function getPais(){ return $this->pais; }
	public function setPais($pais){ $this->pais = $pais; }
}

?>