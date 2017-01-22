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
	private $quantidade;
	private $medicamento;
	private $tipoMedicamento;
	private $usuario;
	private $dataCriacao;
	private $dataAtualizacao;

	function __construct(
		$id=  '',
		$validade=  '',
		$quantidade=  '',
		$quantidadeTotal=  '',
		$medicamento=  '',
		$tipoMedicamento=  '',
		$usuario=  '',
		$dataCriacao=  '',
		$dataAtualizacao=  ''
	)
	{
		$this->id = $id;
		$this->validade = $validade;
		$this->quantidade = $quantidade;
		$this->quantidadeTotal = $quantidadeTotal;
		$this->medicamento = $medicamento;
		$this->tipoMedicamento = $tipoMedicamento;
		$this->usuario = $usuario;
		$this->dataCriacao = $dataCriacao;
		$this->dataAtualizacao = $dataAtualizacao;
	}

	public function getId(){ return $this->id; }
	public function setId($id){ $this->id = $id; }

	public function getValidade(){ return $this->validade; }
	public function setValidade($validade){ $this->validade = $validade; }

	public function getQuantidadeTotal(){ return $this->quantidadeTotal; }
	public function setQuantidadeTotal($quantidadeTotal){ $this->quantidadeTotal = $quantidadeTotal; }

	public function getQuantidade(){ return $this->quantidade; }
	public function setQuantidade($quantidade){ $this->quantidade = $quantidade; }

	public function getMedicamento(){ return $this->medicamento; }
	public function setMedicamento($medicamento){ $this->medicamento = $medicamento; }

	public function getTipoMedicamento(){ return $this->tipoMedicamento; }
	public function setTipoMedicamento($tipoMedicamento){ $this->tipoMedicamento = $tipoMedicamento; }

	public function getUsuario(){ return $this->usuario; }
	public function setUsuario($usuario){ $this->usuario = $usuario; }

	public function getDataCriacao(){ return $this->dataCriacao; }
	public function setDataCriacao($dataCriacao){ $this->dataCriacao = $dataCriacao; }

	public function getDataAtualizacao(){ return $this->dataAtualizacao; }
	public function setDataAtualizacao($dataAtualizacao){ $this->dataAtualizacao = $dataAtualizacao;}
}

?>