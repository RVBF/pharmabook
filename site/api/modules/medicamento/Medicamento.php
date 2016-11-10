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
	private $composicao;
	private $precoFabrica;
	private $precoMaximoConsumidor;
	private $restricaoHospitalar;
	private $laboratorio;
	private $classeTerapeutica;
	private $principioAtivo;

	function __construct(
		$id = '',
		$ean = '',
		$cnpj = '',
		$ggrem = '',
		$registro = '',
		$nomeComercial = '',
		$composicao = '',
		$precoFabrica = '',
		$precoMaximoConsumidor = '',
		$restricaoHospitalar = '',
		$laboratorio = '',
		$classeTerapeutica = '',
		$principioAtivo = ''
	)
	{ 
		$this->id = $id;
		$this->ean = $ean;
		$this->cnpj = $cnpj;
		$this->ggrem = $ggrem;
		$this->registro = $registro;
		$this->nomeComercial = $nomeComercial;
		$this->composicao = $composicao;
		$this->precoFabrica = $precoFabrica;
		$this->precoMaximoConsumidor = $precoMaximoConsumidor;
		$this->restricaoHospitalar = $restricaoHospitalar;
		$this->laboratorio = $laboratorio;
		$this->classeTerapeutica = $classeTerapeutica;
		$this->principioAtivo = $principioAtivo; 	
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

	public function getComposicao(){ return $this->composicao; }
	public function setComposicao($composicao){ $this->composicao = $composicao; }	

	public function getPrecoFabrica(){ return $this->precoFabrica; }
	public function setPrecoFabrica($precoFabrica){ $this->precoFabrica = $precoFabrica; }	

	public function getPrecoConsumidor(){ return $this->precoMaximoConsumidor; }
	public function setPrecoConsumidor($precoMaximoConsumidor){ $this->precoMaximoConsumidor = $precoMaximoConsumidor; }
	
	public function getLaboratorio(){ return $this->laboratorio; }
	public function setLaboratorio($laboratorio){ $this->laboratorio = $laboratorio; }

	public function getClasseTerapeutica(){ return $this->classeTerapeutica; }
	public function setClasseTerapeutica($classeTerapeutica){ $this->classeTerapeutica = $classeTerapeutica; }

	public function getPrincipioAtivo(){ return $this->principioAtivo; }
	public function setPrincipioAtivo($principioAtivo){ $this->principioAtivo = $principioAtivo; }
}

?>