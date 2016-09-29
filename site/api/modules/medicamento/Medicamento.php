<?php

/**
 *	Medicamento
 *
 *  @author Rafael Vinicius Barros Ferreira
 *  @version	0.1
 */
class Medicamento {
	
	private $id;
	private $ean;
	private $cnpj;
	private $ggrem;
	private $registro;
	private $nomeComercial;
	private $classeTerapeutica;
	private $laboratorio;

	function __construct($id = '', $ean = '', $cnpj = '', $ggrem = '', $registro = '', $nomeComercial = '', $classeTerapeutica = '', $laboratorio = '')
	{ 
		$this->id = $id;
		$this->ean = $ean;
		$this->cnpj = $cnpj;
		$this->ggrem = $ggrem;
		$this->registro = $registro;
		$this->nomeComercial = $nomeComercial;
		$this->classeTerapeutica = $classeTerapeutica;
		$this->laboratorio = $laboratorio;
 	}
	
	public function getId(){ return $this->id; }
	public function setId($id){ $this->id = $id; }
	
	public function getEan(){ return $this->ean; }
	public function setEan($ean){ $this->ean = $ean; }

	public function getCnpj(){ return $this->cnpj; }
	public function setCnpj($cnpj){ $this->cnpj = $cnpj; }

	public function getGgrem(){ return $this->ggrem; }
	public function setGgrem($ggrem){ $this->ggrem = $ggrem; }


	public function getRegistro(){ return $this->registro; }
	public function setRegistro($registro){ $this->registro = $registro; }

	public function getNomeComercial(){ return $this->nomeComercial; }
	public function setNomeComercial($nomeComercial){ $this->nomeComercial = $nomeComercial; }
	
	public function getClasseTerapeutica(){ return $this->classeTerapeutica; }
	public function setClasseTerapeutica($classeTerapeutica){ $this->classeTerapeutica = $classeTerapeutica; }
 	

	public function getLaboratorio(){ return $this->laboratorio; }
	public function setLaboratorio($laboratorio){ $this->laboratorio = $laboratorio; }
}

?>