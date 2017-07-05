<?php

/**
 *	Laboratorio
 *
 *  @author Rafael Vinicius Barros Ferreira
 *  @version	0.1
 */
class Laboratorio {

	private $id;
	private $nome;
	private $cnpj;

	function __construct($id = '', $nome = '', $cnpj = '')
	{
		$this->id = (int) $id;
		$this->nome =  $nome;
		$this->cnpj =  $cnpj;
 	}

	public function getId(){ return $this->id; }
	public function setId($id){ $this->id = $id; }

	public function getNome(){ return $this->nome; }
	public function setNome($nome){ $this->nome = $nome; }

	public function getCnpj(){ return $this->cnpj; }
	public function setCnpj($cnpj){ $this->cnpj = $cnpj; }
}

?>