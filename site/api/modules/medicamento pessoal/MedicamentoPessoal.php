<?php

/**
 *	MedicamentoPessoal
 *
 *  @author Rafael Vinicius Barros Ferreira
 *  @version	0.1
 */
class MedicamentoPessoal {
	
	private $id;
	private $validade;
	private $quantidade;
	private $medicamentoPrecificado;
	private $usuario;
	private $dataCriacao;
	private $dataAtualizacao;
	private $dataNovaCompra;

	function __construct(
		$id = '',
		$validade = '',
		$quantidade = '',
		$medicamentoPrecificado = '',
		$usuario = '',
		$dataCriacao = '',
		$dataAtualizacao = '',
		$dataNovaCompra = ''
	)
	{ 
		$this->id = (int) $id;
		$this->validade = $validade;
		$this->quantidade = $quantidade;
		$this->medicamentoPrecificado = $medicamentoPrecificado;
		$this->usuario = $usuario;
		$this->dataCriacao = $dataCriacao;
		$this->dataAtualizacao = $dataAtualizacao;
		$this->dataNovaCompra = $dataNovaCompra;
	}
	
	public function getId(){ return $this->id; }
	public function setId($id){ $this->id = $id; }
	
	public function getValidade(){ return $this->validade; }
	public function setValidade($validade){ $this->validade = $validade; }

	public function getQuantidade(){ return $this->quantidade; }
	public function setQuantidade($quantidade){ $this->quantidade = $quantidade; }

	public function getMedicamentoPrecificado(){ return $this->medicamentoPrecificado; }
	public function setMedicamentoPrecificado($medicamentoPrecificado){ $this->medicamentoPrecificado = $medicamentoPrecificado; }

	public function getUsuario(){ return $this->usuario; }
	public function setUsuario($usuario){ $this->usuario = $usuario; }

	public function getDataCriacao(){ return $this->dataCriacao; }
	public function setDataCriacao($dataCriacao){ $this->dataCriacao = $dataCriacao; }

	public function getDataAtualizacao(){ return $this->dataAtualizacao; }
	public function setDataAtualizacao($dataAtualizacao){ $this->dataAtualizacao = $dataAtualizacao;}

	public function getDataNovaCompra(){ return $this->dataNovaCompra; }
	public function setDataNovaCompra($dataNovaCompra){ $this->dataNovaCompra = $dataNovaCompra; }
}

?>