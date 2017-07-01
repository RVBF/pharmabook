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
	private $bairro;
	private $tipoLogradouro;
	private $dataCriacao;
	private $dataAtualizacao;

	function __construct(
		$id = 0,
		$cep = '',
		$logradouro = '',
		$bairro = '',
		$tipoLogradouro = '',
		$dataCriacao = '',
		$dataAtualizacao = ''
	)
	{

		$this->id = (int) $id;
		$this->cep = $cep;
		$this->logradouro = $logradouro;
		$this->bairro = $bairro;
		$this->tipoLogradouro = $tipoLogradouro;
		$this->pais = $pais;
		$this->dataCriacao = $dataCriacao;
		$this->dataAtualizacao = $dataAtualizacao;
	}

	public function getId(){ return $this->id; }
	public function setId($id){ $this->id = $id; }

	public function getCep(){ return $this->cep; }
	public function setCep($cep){ $this->cep = $cep; }

	public function getLogradouro(){ return $this->logradouro; }
	public function setLogradouro($logradouro){ $this->logradouro = $logradouro; }

	public function getBairro(){ return $this->bairro; }
	public function setBairro($bairro){ $this->bairro = $bairro; }

	public function getTipoLogradouro(){ return $this->tipoLogradouro; }
	public function setTipoLogradouro($tipoLogradouro){ $this->tipoLogradouro = $tipoLogradouro; }

	public function getDataCriacao(){ return $this->dataCriacao; }
	public function setDataCriacao($dataCriacao){ $this->dataCriacao = $dataCriacao; }

	public function getDataAtualizacao(){ return $this->dataAtualizacao; }
	public function setDataAtualizacao($dataAtualizacao){ $this->dataAtualizacao = $dataAtualizacao;}

	public function __toString()
	{
		$endereco = '';

		if($this->getLogradouro() != '')
		{
			$endereco .= $this->getLogradouro().', ';
		}

		if($this->getNumero() != '')
		{
			$endereco .=  $this->getNumero().', ';
		}

		if($this->getCep() != '')
		{
			$endereco .= $this->getCep(). '.';
		}

		return $endereco;
	}

	public function mostrarEndereco()
	{
		return $this->__toString();
	}
}

?>