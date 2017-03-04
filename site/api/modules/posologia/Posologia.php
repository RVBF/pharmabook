<?php

/**
 *	Posologia
 *
 *  @authoRafael Vinicius Barros Ferreira
 *  @version	0.1
 */
class Posologia {

	private $id;
	private $dose;
	private $descricao;
	private $periodicidade;
	private $tipoPeriodicidade;
	private $medicamentoPessoal;
	private $dataCriacao;
	private $dataAtualizacao;

	function __construct(
		$id = '',
		$dose = '',
		$descricao = '',
		$periodicidade = '',
		$tipoPeriodicidade = '',
		$medicamentoPessoal = '',
		$dataCriacao = '',
		$dataAtualização = ''
	)
	{
		$this->id = $id;
		$this->dose = $dose;
		$this->descricao = $descricao;
		$this->periodicidade = $periodicidade;
		$this->tipoPeriodicidade = $tipoPeriodicidade;
		$this->medicamentoPessoal = $medicamentoPessoal;
		$this->dataCriacao = $dataCriacao;
		$this->dataAtualizacao = $dataAtualizacao;
	}

	public function getId(){ return $this->id; }
	public function setId($id){ $this->id = $id; }

	public function getDose(){ return $this->dose; }
	public function setDose($dose){ $this->dose = $dose; }

	public function getDescricao(){ return $this->descricao; }
	public function setDescricao($descricao){ $this->descricao = $medicamentoPessoal; }

	public function getMedicamentoPessoal(){ return $this->medicamentoPessoal; }
	public function setMedicamentoPessoal($medicamentoPessoal){ $this->medicamentoPessoal = $medicamentoPessoal; }

	public function getPeriodicidade(){ return $this->periodicidade; }
	public function setPeriodicidade($periodicidade){ $this->periodicidade = $periodicidade; }

	public function getTipoPeriodicidade(){ return $this->tipoPeriodicidade; }
	public function setTipoPeriodicidade($tipoPeriodicidade){ $this->tipoPeriodicidade = $tipoPeriodicidade; }

	public function getDataCriacao(){ return $this->dataCriacao; }
	public function setDataCriacao($dataCriacao){ $this->dataCriacao = $dataCriacao; }

	public function getDataAtualizacao(){ return $this->dataAtualizacao; }
	public function setDataAtualizacao($dataAtualizacao){ $this->dataAtualizacao = $dataAtualizacao;}
}
?>