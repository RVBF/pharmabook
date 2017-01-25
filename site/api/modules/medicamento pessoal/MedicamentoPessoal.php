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
	private $quantidadeRecipiente;
	private $quantidadeEstoque;
	private $administracao;
	private $unidadeTipo;
	private $medicamentoForma;
	private $usuario;
	private $medicamento;
	private $dataCriacao;
	private $dataAtualizacao;

	function __construct(
		$id =  '',
		$validade =  '',
		$quantidadeRecipiente =  '',
		$quantidadeEstoque =  '',
		$administracao =  '',
		$unidadeTipo =  '',
		$medicamentoForma =  '',
		$usuario =  '',
		$medicamento =  '',
		$dataCriacao =  '',
		$dataAtualizacao =  ''
	)
	{
		$this->id =  $id;
		$this->validade =  $validade;
		$this->quantidadeRecipiente =  $quantidadeRecipiente;
		$this->quantidadeEstoque =  $quantidadeEstoque;
		$this->administracao =  $administracao;
		$this->unidadeTipo =  $unidadeTipo;
		$this->medicamentoForma =  $medicamentoForma;
		$this->usuario =  $usuario;
		$this->medicamento =  $medicamento;
		$this->dataCriacao =  $dataCriacao;
		$this->dataAtualizacao =  $dataAtualizacao;
	}

	public function getId(){ return $this->id; }
	public function setId($id){ $this->id = $id; }

	public function getValidade(){ return $this->validade; }
	public function setValidade($validade){ $this->validade = $validade; }

	public function getQuantidadeRecipiente(){ return $this->quantidadeRecipiente; }
	public function setQuantidadeRecipiente($quantidadeRecipiente){ $this->quantidadeRecipiente = $quantidadeRecipiente; }

	public function getQuantidadeEstoque(){ return $this->quantidadeEstoque; }
	public function setQuantidadeEstoque($quantidadeEstoque){ $this->quantidadeEstoque = $quantidadeEstoque; }

	public function getMedicamento(){ return $this->medicamento; }
	public function setMedicamento($medicamento){ $this->medicamento = $medicamento; }

	public function getAdministracao(){ return $this->administracao; }
	public function setAdministracao($administracao){ $this->administracao = $administracao; }

	public function getUnidadeTipo(){ return $this->unidadeTipo; }
	public function setUnidadeTipo($unidadeTipo){ $this->unidadeTipo = $unidadeTipo; }

	public function getMedicamentoForma(){ return $this->medicamentoForma; }
	public function setMedicamentoForma($medicamentoForma){ $this->medicamentoForma = $medicamentoForma; }

	public function getUsuario(){ return $this->usuario; }
	public function setUsuario($usuario){ $this->usuario = $usuario; }

	public function getDataCriacao(){ return $this->dataCriacao; }
	public function setDataCriacao($dataCriacao){ $this->dataCriacao = $dataCriacao; }

	public function getDataAtualizacao(){ return $this->dataAtualizacao; }
	public function setDataAtualizacao($dataAtualizacao){ $this->dataAtualizacao = $dataAtualizacao;}
}

?>