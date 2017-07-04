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
	private $sigla;
	private $pais;

	function __construct($id = '', $nome = '',  $sigla = '', $pais = '')
	{
		$this->id =  (int) $id;
		$this->nome =  $nome;
		$this->sigla =  $sigla;
		$this->pais = $pais;
 	}

	public function getId(){ return $this->id; }
	public function setId($id){ $this->id = $id; }

	public function getNome(){ return $this->nome; }
	public function setNome($nome){ $this->nome = $nome; }

	public function getSigla(){ return $this->sigla; }
	public function setSigla($sigla){ $this->sigla = $sigla; }

	public function getPais(){ return $this->pais; }
	public function setPais($pais){ $this->pais = $pais; }
}

?>