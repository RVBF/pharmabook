<?php

/**
 *	Tipo Logradouro
 *
 *  @author Irlon Lamblet
 *  @version	1.0
 */

class TipoLogradouro {
	private $id;
	private $nome;

	function __construct($id = '', $nome = '')
	{
		$this->id = (int) $id;
		$this->nome =  $nome;
 	}

	public function getId(){ return $this->id; }
	public function setId($id){ $this->id = $id; }

	public function getNome(){ return $this->nome; }
	public function setNome($nome){ $this->nome = $nome; }
}

?>