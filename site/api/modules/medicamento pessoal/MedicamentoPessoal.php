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
	private $capacidadeRecipiente;
	private $quantidade;
	private $administracao;
	private $tipoUnidade;
	private $medicamentoForma;
	private $usuario;
	private $medicamento;
	private $dataCriacao;
	private $dataAtualizacao;

	function __construct(
		$id =  '',
		$validade =  '',
		$capacidadeRecipiente =  '',
		$quantidade =  '',
		$administracao =  '',
		$tipoUnidade =  '',
		$medicamentoForma =  '',
		$usuario =  '',
		$medicamento =  '',
		$dataCriacao =  '',
		$dataAtualizacao =  ''
	)
	{
		$this->id =  $id;
		$this->validade =  $validade;
		$this->capacidadeRecipiente =  $capacidadeRecipiente;
		$this->quantidade =  $quantidade;
		$this->administracao =  $administracao;
		$this->tipoUnidade =  $tipoUnidade;
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

	public function getCapacidadeRecipiente(){ return $this->capacidadeRecipiente; }
	public function setCapacidadeRecipiente($capacidadeRecipiente){ $this->capacidadeRecipiente = $capacidadeRecipiente; }

	public function getQuantidade(){ return $this->quantidade; }
	public function setQuantidade($quantidade){ $this->quantidade = $quantidade; }

	public function getMedicamento(){ return $this->medicamento; }
	public function setMedicamento($medicamento){ $this->medicamento = $medicamento; }

	public function getAdministracao(){ return $this->administracao; }
	public function setAdministracao($administracao){ $this->administracao = $administracao; }

	public function getTipoUnidade(){ return $this->tipoUnidade; }
	public function setTipoUnidade($tipoUnidade){ $this->tipoUnidade = $tipoUnidade; }

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