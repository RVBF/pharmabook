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
	private $numero;
	private $complemento;
	private $referencia;
	private $bairro;
	private $cidade;
	private $estado;
	private $pais;
	private $dataCriacao;
	private $dataAtualizacao;

	function __construct(
		$id = 0,
		$cep = '',
		$logradouro = '',
		$numero = '',
		$complemento = '',
		$referencia = '',
		$bairro = '',
		$cidade = '',
		$estado = '',
		$pais = '',
		$dataCriacao = '',
		$dataAtualizacao = ''
	)
	{

		$this->id = (int) $id;
		$this->cep = $cep;
		$this->logradouro = $logradouro;
		$this->numero = (int) $numero;
		$this->complemento = $complemento;
		$this->referencia = $referencia;
		$this->bairro = $bairro;
		$this->cidade = $cidade;
		$this->estado = $estado;
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

	public function getNumero(){ return $this->numero; }
	public function setNumero($numero){ $this->numero = $numero; }

	public function getComplemento(){ return $this->complemento; }
	public function setComplemento($complemento){ $this->complemento = $complemento; }

	public function getReferencia(){ return $this->referencia; }
	public function setReferencia($referencia){ $this->referencia = $referencia; }

	public function getBairro(){ return $this->bairro; }
	public function setBairro($bairro){ $this->bairro = $bairro; }

	public function getCidade(){ return $this->cidade; }
	public function setCidade($cidade){ $this->cidade = $cidade; }

	public function getEstado(){ return $this->estado; }
	public function setEstado($estado){ $this->estado = $estado; }

	public function getPais(){ return $this->pais; }
	public function setPais($pais){ $this->pais = $pais; }

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

		if($this->getComplemento() != '')
		{
			$endereco .= $this->getComplemento(). ', ';
		}

		if($this->getReferencia() != '')
		{
			$endereco .= $this->getReferencia(). ', ';
		}

		if($this->getCidade() != '')
		{
			$endereco .= $this->getCidade(). ', ';
		}

		if($this->getEstado() != '')
		{
			$endereco .= $this->getEstado(). ', ';
		}

		if($this->getPais() != '')
		{
			$endereco .= $this->getPais(). ', ';
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