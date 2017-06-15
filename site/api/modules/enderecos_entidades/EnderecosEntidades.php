<?php

/**
 *	Endereco
 *
 *  @authoIrlon de Souza Lamblet
 *  @version	0.1
 */
class Endereco {

	private $id;
	private $numero;
	private $complemento;
	private $referencia;
	private $dataCriacao;
	private $dataAtualizacao;
	private $endereco;

	function __construct(
		$id = 0,
		$numero = 0,
		$complemento = '',
		$referencia = '',
		$dataCriacao = '',
		$dataAtualizacao = '',
		$endereco = null;
	)
	{

		$this->id = (int) $id;
		$this->numero = (int) $numero;
		$this->complemento = $complemento;
		$this->referencia = $referencia;
		$this->dataCriacao = $dataCriacao;
		$this->dataAtualizacao = $dataAtualizacao;
		$this->endereco = $endereco;
	}

	public function getId(){ return $this->id; }
	public function setId($id){ $this->id = $id; }

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

	public function getEndereco(){ return $this->endereco; }
	public function setEndereco($endereco){ $this->endereco = $endereco; }

	public function __toString()
	{
		$endereco = '';

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

		return $endereco;
	}

	public function mostrarEndereco()
	{
		return $this->__toString();
	}
}

?>