<?php

/**
 *	Posologia
 *
 *  @authoRafael Vinicius Barros Ferreira
 *  @version	0.1
 */
class Posologia {
	
	private $id;
	private $dose;
	private $descricao;

	function __construct(
		$id = '',
		$dose = '',
		$descricao = '',
		$dataCriacao = '',
		$dataAtualizacao = ''
	)
	{ 
		$this->id =  $id;
		$this->dose =  $dose;
		$this->descricao =  $descricao;
 	}
	
	public function getId(){ return $this->id; }
	public function setId($id){ $this->id = $id; }
	
	public function getDose(){ return $this->dose; }
	public function setDose($dose){ $this->dose = $dose; }

	public function getDescricao(){ return $this->descricao; }
	public function setDescricao($descricao){ $this->descricao = $descricao; }
}

?>