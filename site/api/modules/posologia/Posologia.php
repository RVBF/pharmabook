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
	private $administracao;
	private $periodicidade;
	private $tipoUnidadeDose;
	private $tipoPeriodicidade;

	const ADMINISTRACAO_MEDICAMENTO = [
		'Oral' => 'Oral',
		'Sublingual' => 'Sublingual',
		'Retal' => 'Retal',
		'Intra-Venosa' => 'Intra-Venosa',
		'Intra-Muscular' => 'Intra-Muscular',
		'Subcutânea' => 'Subcutânea',
		'Intradérmica' => 'Intradérmica',
		'Intra-arterial' =>  'Intra-arterial',
		'Intracardíaca' =>  'Intracardíaca',
		'Intratecal' =>  'Intratecal',
		'Peridural' =>  'Peridural',
		'Intra-articular' =>  'Intra-articular',
		'Cutânea' =>  'Cutânea',
		'Respiratória' =>  'Respiratória',
		'Conjuntival' =>  'Conjuntival',
		'Geniturinária' =>  'Geniturinária',
		'Intracanal' =>  'Intracanal'	
	];

	const UNIDADE_MEDIDAS = [
		'mg' => 'mg',
		'ml' => 'ml',
		'cc' => 'cc'
	];

	const PERIODICIDADE_TIPO = [
		'Horas' => 'Horas',
		'Dias' => 'Dias',
		'Meses' => 'Meses',
		'Anos' => 'Anos'
	];

	function __construct(
		$id = '',
		$dose = '',
		$descricao = '',
		$administracao = '',
		$periodicidade = '',
		$tipoUnidadeDose = '',
		$tipoPeriodicidade = ''
	)
	{ 
		$this->id = $id;
		$this->dose = $dose;
		$this->descricao = $descricao;
		$this->administracao = $administracao;
		$this->periodicidade = $periodicidade;
		$this->tipoUnidadeDose = $tipoUnidadeDose;
		$this->tipoPeriodicidade = $tipoPeriodicidade;
	}
	
	public function getId(){ return $this->id; }
	public function setId($id){ $this->id = $id; }
	
	public function getDose(){ return $this->dose; }
	public function setDose($dose){ $this->dose = $dose; }	

	public function getDescricao(){ return $this->descricao; }
	public function setDescricao($descricao){ $this->descricao = $descricao; }	

	public function getAdministracao(){ return $this->administracao; }
	public function setAdministracao($administracao){ $this->administracao = $administracao; }	

	public function getPeriodicidade(){ return $this->periodicidade; }
	public function setPeriodicidade($periodicidade){ $this->periodicidade = $periodicidade; }

	public function getTipoUnidadeDose(){ return $this->tipoUnidadeDose; }
	public function setTipoUnidadeDose($tipoUnidadeDose){ $this->tipoUnidadeDose = $tipoUnidadeDose; }

	public function getTipoPeriodicidade(){ return $this->tipoPeriodicidade; }
	public function setTipoPeriodicidade($tipoPeriodicidade){ $this->tipoPeriodicidade = $tipoPeriodicidade; }
}

?>