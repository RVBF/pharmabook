<?php

/**
 *	Endereco
 *
 *  @authoRafael Vinicius Barros Ferreira
 *  @version	0.1
 */
class Endereco {
	private $id;
	private $cep;
	private $logradouro;
	private $latitude;
	private $longitude;
	private $codigoIbge;
	private $bairro;
	function __construct(
		$id = 0,
		$cep = '',
		$logradouro = '',
		$latitude = '',
		$longitude = '',
		$codigoIbge = '',
		$bairro = ''
	)
	{
		$this->id = (int) $id;
		$this->cep = $cep;
		$this->logradouro = $logradouro;
		$this->latitude = (double) $latitude;
		$this->longitude = (double) $longitude;
		$this->codigoIbge = $codigoIbge;
		$this->bairro = $bairro;
	}

	public function getId(){ return $this->id; }
	public function setId($id){ $this->id = $id; }

	public function getCep(){ return $this->cep; }
	public function setCep($cep){ $this->cep = $cep; }

	public function getLogradouro(){ return $this->logradouro; }
	public function setLogradouro($logradouro){ $this->logradouro = $logradouro; }

	public function getLatitude(){ return $this->latitude; }
	public function setLatitude($latitude){ $this->latitude = $latitude; }

	public function getLongitude(){ return $this->longitude; }
	public function setLongitude($longitude){ $this->longitude = $longitude; }

	public function getCodigoIbge(){ return $this->codigoIbge; }
	public function setCodigoIbge($codigoIbge){ $this->codigoIbge = $codigoIbge; }

	public function getBairro(){ return $this->bairro; }
	public function setBairro($bairro){ $this->bairro = $bairro; }
}

?>