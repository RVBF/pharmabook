<?php

/**
 *	Usuario
 *
 *  @authoRafael Vinicius Barros Ferreira
 *  @version	0.1
 */
class Endereco {
	
	private $id;
	private $logradouro;
	private $bairro;
	private $cidade;
	private $estado;
	private $numero;
	private $complemento;
	private $referencia;
	private $dataCriacao;
	private $dataAtualizacao;

	function __construct(	$id = '',
							$logradouro = '',
							$bairro = '',
							$cidade = '',
							$estado = '',
							$numero = '',
							$complemento = '',
							$referencia = '',
							$dataCriacao = '',
							$dataAtualizacao = ''
						)
	{ 
		$this->id = $id;
		$this->logradouro = $logradouro;
		$this->bairro = $bairro;
		$this->cidade = $cidade;
		$this->estado = $estado;
		$this->numero = $numero;
		$this->complemento = $complemento;
		$this->referencia = $referencia;
		$this->dataCriacao = $dataCriacao;
		$this->dataAtualizacao = $dataAtualizacao;
	}
	
	public function getId(){ return $this->id; }
	public function setId($id){ $this->id = $id; }
	
	public function getLogradouro(){ return $this->logradouro; }
	public function setLogradouro($logradouro){ $this->logradouro = $logradouro; }

	public function getBairro(){ return $this->bairro; }
	public function setBairro($bairro){ $this->bairro = $bairro; }

	public function getCidade(){ return $this->cidade; }
	public function setCidade($cidade){ $this->cidade = $cidade; }

	public function getEstado(){ return $this->estado; }
	public function setEstado($estado){ $this->estado = $estado; }	

	public function getNumero(){ return $this->numero; }
	public function setNumero($numero){ $this->numero = $numero; }

	public function getComplemento(){ return $this->complemento; }
	public function setComplemento($complemento){ $this->complemento = $complemento; }	

	public function getReferencia(){ return $this->referencia; }
	public function setReferencia($referencia){ $this->referencia = $referencia; }

	public function getDataCriacao(){ return $this->dataCriacao; }
	public function setDataCriacao($dataCriacao){ $this->dataCriacao = $dataCriacao; }

	public function getDataAtualizacao(){ return $this->dataAtualizacao; }
	public function setDataAtualizacao($dataAtualizacao){ $this->dataAtualizacao = $dataAtualizacao;}
}

?>