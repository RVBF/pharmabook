<?php

/**
 *	MedicamentoPrecificado
 *
 *  @author Rafael Vinicius Barros Ferreira
 *  @version	0.1
 */
class MedicamentoPrecificado {

	private $id;
	private $preco;
	private $farmacia;
	private $medicamento;
	private $criador;
	private $atualizador;
	private $dataCriacao;
	private $dataAtualizacao;

	function __construct(
		$id = '',
		$preco = '',
		$farmacia = '',
		$medicamento = '',
		$criador = '',
		$atualizador = '',
		$dataCriacao = '',
		$dataAtualizacao = ''
	)
	{
		$this->id = $id;
		$this->preco = $preco;
		$this->farmacia = $farmacia;
		$this->medicamento = $medicamento;
		$this->criador = $criador;
		$this->atualizador = $atualizador;
		$this->dataCriacao = $dataCriacao;
		$this->dataAtualizacao = $dataAtualizacao;
	}

	public function getId(){ return $this->id; }
	public function setId($id){ $this->id = $id; }

	public function getPreco(){ return $this->preco; }
	public function setPreco($preco){ $this->preco = $preco; }

	public function getFarmacia(){ return $this->farmacia; }
	public function setFarmacia($farmacia){ $this->farmacia = $farmacia; }

	public function getMedicamento(){ return $this->medicamento; }
	public function setMedicamento($medicamento){ $this->medicamento = $medicamento; }

	public function getCriador(){ return $this->criador; }
	public function setCriador($criador){ $this->criador = $criador; }

	public function getAtualizador(){ return $this->atualizador; }
	public function setAtualizador($atualizador){ $this->atualizador = $atualizador; }

	public function getDataCriacao(){ return $this->dataCriacao; }
	public function setDataCriacao($dataCriacao){ $this->dataCriacao = $dataCriacao; }

	public function getDataAtualizacao(){ return $this->dataAtualizacao; }
	public function setDataAtualizacao($dataAtualizacao){ $this->dataAtualizacao = $dataAtualizacao; }
}

?>