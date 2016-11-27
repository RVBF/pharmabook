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
	private $dataNovaCompra;
	private $medicamentoPrecificado;
	private $posologia;
	private $usuario;
	private $dataCriacao;
	private $dataAtualizacao;

	function __construct(
		$id = '',
		$validade = '',
		$quantidade = '',
		$dataNovaCompra = '',
		$medicamentoPrecificado = '',
		$posologia = '',
		$usuario = '',
		$dataCriacao = '',
		$dataAtualizacao = ''
	)
	{ 
		$this->id = (int) $id;
		$this->validade = $validade;
		$this->quantidade = $quantidade;
		$this->dataNovaCompra = $dataNovaCompra;
		$this->medicamentoPrecificado = $medicamentoPrecificado;
		$this->posologia = $posologia;
		$this->usuario = $usuario;
		$this->dataCriacao = $dataCriacao;
		$this->dataAtualizacao = $dataAtualizacao;
	}
	
	public function getId(){ return $this->id; }
	public function setId($id){ $this->id = $id; }
	
	public function getValidade(){ return $this->validade; }
	public function setValidade($validade){ $this->validade = $validade; }

	public function getQuantidade(){ return $this->quantidade; }
	public function setQuantidade($quantidade){ $this->quantidade = $quantidade; }

	public function getDataNovaCompra(){ return $this->dataNovaCompra; }
	public function setDataNovaCompra($dataNovaCompra){ $this->dataNovaCompra = $dataNovaCompra; }

	public function getMedicamentoPrecificado(){ return $this->medicamentoPrecificado; }
	public function setMedicamentoPrecificado($medicamentoPrecificado){ $this->medicamentoPrecificado = $medicamentoPrecificado; }

	public function getPosologia(){ return $this->posologia; }
	public function setPosologia($posologia){ $this->posologia = $posologia; }

	public function getUsuario(){ return $this->usuario; }
	public function setUsuario($usuario){ $this->usuario = $usuario; }

	public function getDataCriacao(){ return $this->dataCriacao; }
	public function setDataCriacao($dataCriacao){ $this->dataCriacao = $dataCriacao; }

	public function getDataAtualizacao(){ return $this->dataAtualizacao; }
	public function setDataAtualizacao($dataAtualizacao){ $this->dataAtualizacao = $dataAtualizacao;}
}

?>