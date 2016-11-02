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
	private $usuario;
	private $dataCriacao;
	private $dataAtualizacao;

	function __construct(
		$id = '',
		$preco = '',
		$farmacia = '',
		$medicamento = '',
		$usuario = '',
		$dataCriacao = '',
		$dataAtualizacao = ''
	)
	{ 
		$this->id = $id;
		$this->preco = $preco;
		$this->farmacia = $farmacia;
		$this->medicamento = $medicamento;
		$this->usuario = $usuario;
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

	public function getUsuario(){ return $this->usuario; }
	public function setUsuario($usuario){ $this->usuario = $usuario; }

	public function getDataCriacao(){ return $this->dataCriacao; }
	public function setDataCriacao($dataCriacao){ $this->dataCriacao = $dataCriacao; }

	public function getDataAtualizacao(){ return $this->dataAtualizacao; }
	public function setDataAtualizacao($dataAtualizacao){ $this->dataAtualizacao = $dataAtualizacao; }
}

?>