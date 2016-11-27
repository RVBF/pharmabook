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

<<<<<<< HEAD
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
=======
	// constantes para os tipos de administração de um medicamento
	const ADMINISTRACAO_ORAL = 'Oral';
	const ADMINISTRACAO_ORAL_ID = 1;
	const ADMINISTRACAO_SUBLINGUAL = 'Sublingual';
	const ADMINISTRACAO_SUBLINGUAL_ID = 2;
	const ADMINISTRACAO_RETAL = 'Retal';
	const ADMINISTRACAO_RETAL_ID = 3;
	const ADMINISTRACAO_INTRA_VENOSA = 'Intra-Venosa';
	const ADMINISTRACAO_INTRA_VENOSA_ID = 3;
	const ADMINISTRACAO_INTRA_MUSCULAR = 'Intra-Muscular';
	const ADMINISTRACAO_INTRA_MUSCULAR_ID = 4;
	const ADMINISTRACAO_SUBCUTÂNEA = 'Subcutânea';
	const ADMINISTRACAO_SUBCUTÂNEA_ID = 5;
	const ADMINISTRACAO_INTRADÉRMICA = 'Intradérmica';
	const ADMINISTRACAO_INTRADÉRMICA_ID = 6;
	const ADMINISTRACAO_INTRA_ARTERIAL =  'Intra-arterial';
	const ADMINISTRACAO_INTRA_ARTERIAL_ID = 7;
	const ADMINISTRACAO_INTRACARDÍACA =  'Intracardíaca';
	const ADMINISTRACAO_INTRACARDÍACA_ID = 8;
	const ADMINISTRACAO_INTRATECAL =  'Intratecal';
	const ADMINISTRACAO_INTRATECAL_ID = 9;
	const ADMINISTRACAO_PERIDURAL =  'Peridural';
	const ADMINISTRACAO_PERIDURAL_ID = 10;
	const ADMINISTRACAO_INTRA_ARTICULAR =  'Intra-articular';
	const ADMINISTRACAO_INTRA_ARTICULAR_ID = 11;
	const ADMINISTRACAO_CUTÂNEA =  'Cutânea';
	const ADMINISTRACAO_CUTÂNEA_ID = 12;
	const ADMINISTRACAO_RESPIRATÓRIA =  'Respiratória';
	const ADMINISTRACAO_RESPIRATÓRIA_ID = 13;
	const ADMINISTRACAO_CONJUNTIVAL =  'Conjuntival';
	const ADMINISTRACAO_CONJUNTIVAL_ID = 14;
	const ADMINISTRACAO_GENITURINÁRIA =  'Geniturinária';
	const ADMINISTRACAO_GENITURINÁRIA_ID = 15;
	const ADMINISTRACAO_INTRACANAL =  'Intracanal';
	const ADMINISTRACAO_INTRACANAL_ID = 16;

	//constantes para os tipos de unidade de medidas usadas nas dosagens de medicamento
	const UNIDADES_MG = 'mg';
	const UNIDADES_MG_ID = 1;
	const UNIDADES_ML = 'ml';
	const UNIDADES_ML_ID = 2;
	const UNIDADES_CC = 'cc';
	const UNIDADES_CC_ID = 3;

	const PERIODICIDADE_HORAS = 'Horas';
	const PERIODICIDADE_DIAS = 'Dias';
	const PERIODICIDADE_MESES = 'Meses';
	const PERIODICIDADE_ANOS = 'Anos';

	const PERIODICIDADE_HORAS_ID = 1;
	const PERIODICIDADE_DIAS_ID = 2;
	const PERIODICIDADE_MESES_ID = 3;
	const PERIODICIDADE_ANOS_ID = 4;
>>>>>>> e635367d577e49665ce371c9ce3ca7e3ee5ca188

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

	static function retornarPeriodicidadesTipos()
	{
		return [
			['id' =>self::PERIODICIDADE_HORAS_ID, 'nome' => self::PERIODICIDADE_HORAS],
			['id' =>self::PERIODICIDADE_DIAS_ID, 'nome' => self::PERIODICIDADE_DIAS],
			['id' =>self::PERIODICIDADE_MESES_ID, 'nome' => self::PERIODICIDADE_MESES],
			['id' =>self::PERIODICIDADE_ANOS_ID, 'nome' => self::PERIODICIDADE_ANOS]
		];
	}

	static function getPeriodicidadeTipos($valor)
	{
		$periodadesArray = [
			['id' =>self::PERIODICIDADE_HORAS_ID, 'nome' => self::PERIODICIDADE_HORAS],
			['id' =>self::PERIODICIDADE_DIAS_ID, 'nome' => self::PERIODICIDADE_DIAS],
			['id' =>self::PERIODICIDADE_MESES_ID, 'nome' => self::PERIODICIDADE_MESES],
			['id' =>self::PERIODICIDADE_ANOS_ID, 'nome' => self::PERIODICIDADE_ANOS]
		];

		return $periodicidadeArray[$valor];
	}

	static function existePeriodicidadeTipos($valor)
	{
		$periodadesArray = [
			['id' =>self::PERIODICIDADE_HORAS_ID, 'nome' => self::PERIODICIDADE_HORAS],
			['id' =>self::PERIODICIDADE_DIAS_ID, 'nome' => self::PERIODICIDADE_DIAS],
			['id' =>self::PERIODICIDADE_MESES_ID, 'nome' => self::PERIODICIDADE_MESES],
			['id' =>self::PERIODICIDADE_ANOS_ID, 'nome' => self::PERIODICIDADE_ANOS]
		];

		return isset($periodicidadeArray[$valor]);
	}

	static function retornarUnidadesTipos()
	{
		return [
			self::UNIDADES_MG => self::UNIDADES_MG,
			self::UNIDADES_ML => self::UNIDADES_ML,
			self::UNIDADES_CC => self::UNIDADES_CC		
		];
	}

	static function getUnidadeTipo($valor)
	{
		$periodadesArray = [
			self::UNIDADES_MG => self::UNIDADES_MG,
			self::UNIDADES_ML => self::UNIDADES_ML,
			self::UNIDADES_CC => self::UNIDADES_CC		
		];

		return $periodicidadeArray[$valor];
	}

	static function existeUnidadeTipo($valor)
	{
		$periodadesArray = [
			self::UNIDADES_MG => self::UNIDADES_MG,
			self::UNIDADES_ML => self::UNIDADES_ML,
			self::UNIDADES_CC => self::UNIDADES_CC		
		];

		return isset($periodicidadeArray[$valor]);
	}	

	static function retornarTiposDeAdministracao()
	{
		return [
			self::ADMINISTRACAO_ORAL => self::ADMINISTRACAO_ORAL,
			self::ADMINISTRACAO_SUBLINGUAL => self::ADMINISTRACAO_SUBLINGUAL,
			self::ADMINISTRACAO_RETAL => self::ADMINISTRACAO_RETAL,
			self::ADMINISTRACAO_INTRA_VENOSA => self::ADMINISTRACAO_INTRA_VENOSA,
			self::ADMINISTRACAO_INTRA_MUSCULAR => self::ADMINISTRACAO_INTRA_MUSCULAR,
			self::ADMINISTRACAO_SUBCUTÂNEA => self::ADMINISTRACAO_SUBCUTÂNEA,
			self::ADMINISTRACAO_INTRADÉRMICA => self::ADMINISTRACAO_INTRADÉRMICA,
			self::ADMINISTRACAO_INTRA_ARTERIAL => self::ADMINISTRACAO_INTRA_ARTERIAL,
			self::ADMINISTRACAO_INTRACARDÍACA => self::ADMINISTRACAO_INTRACARDÍACA,
			self::ADMINISTRACAO_INTRATECAL => self::ADMINISTRACAO_INTRATECAL,
			self::ADMINISTRACAO_PERIDURAL => self::ADMINISTRACAO_PERIDURAL,
			self::ADMINISTRACAO_INTRA_ARTICULAR => self::ADMINISTRACAO_INTRA_ARTICULAR,
			self::ADMINISTRACAO_CUTÂNEA => self::ADMINISTRACAO_CUTÂNEA,
			self::ADMINISTRACAO_RESPIRATÓRIA => self::ADMINISTRACAO_RESPIRATÓRIA,
			self::ADMINISTRACAO_CONJUNTIVAL => self::ADMINISTRACAO_CONJUNTIVAL,
			self::ADMINISTRACAO_GENITURINÁRIA => self::ADMINISTRACAO_GENITURINÁRIA,
			self::ADMINISTRACAO_INTRACANAL => self::ADMINISTRACAO_INTRACANAL
		];
	}

	static function getTipoDeAdministracao($valor)
	{
		$periodadesArray = [
			self::ADMINISTRACAO_ORAL => self::ADMINISTRACAO_ORAL,
			self::ADMINISTRACAO_SUBLINGUAL => self::ADMINISTRACAO_SUBLINGUAL,
			self::ADMINISTRACAO_RETAL => self::ADMINISTRACAO_RETAL,
			self::ADMINISTRACAO_INTRA_VENOSA => self::ADMINISTRACAO_INTRA_VENOSA,
			self::ADMINISTRACAO_INTRA_MUSCULAR => self::ADMINISTRACAO_INTRA_MUSCULAR,
			self::ADMINISTRACAO_SUBCUTÂNEA => self::ADMINISTRACAO_SUBCUTÂNEA,
			self::ADMINISTRACAO_INTRADÉRMICA => self::ADMINISTRACAO_INTRADÉRMICA,
			self::ADMINISTRACAO_INTRA_ARTERIAL => self::ADMINISTRACAO_INTRA_ARTERIAL,
			self::ADMINISTRACAO_INTRACARDÍACA => self::ADMINISTRACAO_INTRACARDÍACA,
			self::ADMINISTRACAO_INTRATECAL => self::ADMINISTRACAO_INTRATECAL,
			self::ADMINISTRACAO_PERIDURAL => self::ADMINISTRACAO_PERIDURAL,
			self::ADMINISTRACAO_INTRA_ARTICULAR => self::ADMINISTRACAO_INTRA_ARTICULAR,
			self::ADMINISTRACAO_CUTÂNEA => self::ADMINISTRACAO_CUTÂNEA,
			self::ADMINISTRACAO_RESPIRATÓRIA => self::ADMINISTRACAO_RESPIRATÓRIA,
			self::ADMINISTRACAO_CONJUNTIVAL => self::ADMINISTRACAO_CONJUNTIVAL,
			self::ADMINISTRACAO_GENITURINÁRIA => self::ADMINISTRACAO_GENITURINÁRIA,
			self::ADMINISTRACAO_INTRACANAL => self::ADMINISTRACAO_INTRACANAL
		];

		return $periodicidadeArray[$valor];
	}

	static function existeTipoDeAdmisnitracao($valor)
	{
		$periodadesArray = [
			self::ADMINISTRACAO_ORAL => self::ADMINISTRACAO_ORAL,
			self::ADMINISTRACAO_SUBLINGUAL => self::ADMINISTRACAO_SUBLINGUAL,
			self::ADMINISTRACAO_RETAL => self::ADMINISTRACAO_RETAL,
			self::ADMINISTRACAO_INTRA_VENOSA => self::ADMINISTRACAO_INTRA_VENOSA,
			self::ADMINISTRACAO_INTRA_MUSCULAR => self::ADMINISTRACAO_INTRA_MUSCULAR,
			self::ADMINISTRACAO_SUBCUTÂNEA => self::ADMINISTRACAO_SUBCUTÂNEA,
			self::ADMINISTRACAO_INTRADÉRMICA => self::ADMINISTRACAO_INTRADÉRMICA,
			self::ADMINISTRACAO_INTRA_ARTERIAL => self::ADMINISTRACAO_INTRA_ARTERIAL,
			self::ADMINISTRACAO_INTRACARDÍACA => self::ADMINISTRACAO_INTRACARDÍACA,
			self::ADMINISTRACAO_INTRATECAL => self::ADMINISTRACAO_INTRATECAL,
			self::ADMINISTRACAO_PERIDURAL => self::ADMINISTRACAO_PERIDURAL,
			self::ADMINISTRACAO_INTRA_ARTICULAR => self::ADMINISTRACAO_INTRA_ARTICULAR,
			self::ADMINISTRACAO_CUTÂNEA => self::ADMINISTRACAO_CUTÂNEA,
			self::ADMINISTRACAO_RESPIRATÓRIA => self::ADMINISTRACAO_RESPIRATÓRIA,
			self::ADMINISTRACAO_CONJUNTIVAL => self::ADMINISTRACAO_CONJUNTIVAL,
			self::ADMINISTRACAO_GENITURINÁRIA => self::ADMINISTRACAO_GENITURINÁRIA,
			self::ADMINISTRACAO_INTRACANAL => self::ADMINISTRACAO_INTRACANAL
		];

		return isset($periodicidadeArray[$valor]);
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