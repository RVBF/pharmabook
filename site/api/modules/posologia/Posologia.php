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

	const PERIODICIDADE_MINUTOS = 'Minutos';
	const PERIODICIDADE_MINUTOS_ID = 1;
	const PERIODICIDADE_HORAS = 'Horas';
	const PERIODICIDADE_HORAS_ID = 1;
	const PERIODICIDADE_DIAS = 'Dias';
	const PERIODICIDADE_DIAS_ID = 2;
	const PERIODICIDADE_MESES = 'Meses';
	const PERIODICIDADE_MESES_ID = 3;


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
			['id' =>self::PERIODICIDADE_MINUTOS_ID, 'nome' => self::PERIODICIDADE_MINUTOS],
			['id' =>self::PERIODICIDADE_HORAS_ID, 'nome' => self::PERIODICIDADE_HORAS],
			['id' =>self::PERIODICIDADE_DIAS_ID, 'nome' => self::PERIODICIDADE_DIAS],
			['id' =>self::PERIODICIDADE_MESES_ID, 'nome' => self::PERIODICIDADE_MESES]
		];
	}

	static function getPeriodicidadeTipos($valor)
	{
		$periodadesArray = [
			['id' =>self::PERIODICIDADE_MINUTOS_ID, 'nome' => self::PERIODICIDADE_MINUTOS],
			['id' =>self::PERIODICIDADE_HORAS_ID, 'nome' => self::PERIODICIDADE_HORAS],
			['id' =>self::PERIODICIDADE_DIAS_ID, 'nome' => self::PERIODICIDADE_DIAS],
			['id' =>self::PERIODICIDADE_MESES_ID, 'nome' => self::PERIODICIDADE_MESES]
		];

		return $periodadesArray[$valor];
	}

	static function existePeriodicidadeTipos($valor)
	{
		$periodadesArray = [
			['id' =>self::PERIODICIDADE_MINUTOS_ID, 'nome' => self::PERIODICIDADE_MINUTOS],
			['id' =>self::PERIODICIDADE_HORAS_ID, 'nome' => self::PERIODICIDADE_HORAS],
			['id' =>self::PERIODICIDADE_DIAS_ID, 'nome' => self::PERIODICIDADE_DIAS],
			['id' =>self::PERIODICIDADE_MESES_ID, 'nome' => self::PERIODICIDADE_MESES]
		];

		return isset($periodadesArray[$valor]);
	}

	static function retornarUnidadesTipos()
	{
		return [
			['id' => self::UNIDADES_MG_ID, 'nome' => self::UNIDADES_MG],
			['id' => self::UNIDADES_ML_ID, 'nome' => self::UNIDADES_ML],
			['id' => self::UNIDADES_CC_ID, 'nome' => self::UNIDADES_CC]
		];
	}

	static function getUnidadeTipo($valor)
	{
		$unidadesTiposArray = [
			['id' => self::UNIDADES_MG_ID, 'nome' => self::UNIDADES_MG],
			['id' => self::UNIDADES_ML_ID, 'nome' => self::UNIDADES_ML],
			['id' => self::UNIDADES_CC_ID, 'nome' => self::UNIDADES_CC]
		];

		return $unidadesTiposArray[$valor];
	}

	static function existeUnidadeTipo($valor)
	{
		$unidadesTiposArray = [
			['id' => self::UNIDADES_MG_ID, 'nome' => self::UNIDADES_MG],
			['id' => self::UNIDADES_ML_ID, 'nome' => self::UNIDADES_ML],
			['id' => self::UNIDADES_CC_ID, 'nome' => self::UNIDADES_CC]
		];

		return isset($unidadesTiposArray[$valor]);
	}	

	static function retornarTiposDeAdministracao()
	{
		return [
			['id' => self::ADMINISTRACAO_ORAL_ID, 'nome' => self::ADMINISTRACAO_ORAL],
			['id' => self::ADMINISTRACAO_SUBLINGUAL_ID, 'nome' => self::ADMINISTRACAO_SUBLINGUAL],
			['id' => self::ADMINISTRACAO_RETAL_ID, 'nome' => self::ADMINISTRACAO_RETAL],
			['id' => self::ADMINISTRACAO_INTRA_VENOSA_ID, 'nome' => self::ADMINISTRACAO_INTRA_VENOSA],
			['id' => self::ADMINISTRACAO_INTRA_MUSCULAR_ID, 'nome' => self::ADMINISTRACAO_INTRA_MUSCULAR],
			['id' => self::ADMINISTRACAO_SUBCUTÂNEA_ID, 'nome' => self::ADMINISTRACAO_SUBCUTÂNEA],
			['id' => self::ADMINISTRACAO_INTRADÉRMICA_ID, 'nome' => self::ADMINISTRACAO_INTRADÉRMICA],
			['id' => self::ADMINISTRACAO_INTRA_ARTERIAL_ID, 'nome' => self::ADMINISTRACAO_INTRA_ARTERIAL],
			['id' => self::ADMINISTRACAO_INTRACARDÍACA_ID, 'nome' => self::ADMINISTRACAO_INTRACARDÍACA],
			['id' => self::ADMINISTRACAO_INTRATECAL_ID, 'nome' => self::ADMINISTRACAO_INTRATECAL],
			['id' => self::ADMINISTRACAO_PERIDURAL_ID, 'nome' => self::ADMINISTRACAO_PERIDURAL],
			['id' => self::ADMINISTRACAO_INTRA_ARTICULAR_ID, 'nome' => self::ADMINISTRACAO_INTRA_ARTICULAR],
			['id' => self::ADMINISTRACAO_CUTÂNEA_ID, 'nome' => self::ADMINISTRACAO_CUTÂNEA],
			['id' => self::ADMINISTRACAO_RESPIRATÓRIA_ID, 'nome' => self::ADMINISTRACAO_RESPIRATÓRIA],
			['id' => self::ADMINISTRACAO_CONJUNTIVAL_ID, 'nome' => self::ADMINISTRACAO_CONJUNTIVAL],
			['id' => self::ADMINISTRACAO_GENITURINÁRIA_ID, 'nome' => self::ADMINISTRACAO_GENITURINÁRIA],
			['id' => self::ADMINISTRACAO_INTRACANAL_ID, 'nome' => self::ADMINISTRACAO_INTRACANAL]
		];
	}

	static function getTipoDeAdministracao($valor)
	{
		$periodadesArray = [
			['id' => self::ADMINISTRACAO_ORAL_ID, 'nome' => self::ADMINISTRACAO_ORAL],
			['id' => self::ADMINISTRACAO_SUBLINGUAL_ID, 'nome' => self::ADMINISTRACAO_SUBLINGUAL],
			['id' => self::ADMINISTRACAO_RETAL_ID, 'nome' => self::ADMINISTRACAO_RETAL],
			['id' => self::ADMINISTRACAO_INTRA_VENOSA_ID, 'nome' => self::ADMINISTRACAO_INTRA_VENOSA],
			['id' => self::ADMINISTRACAO_INTRA_MUSCULAR_ID, 'nome' => self::ADMINISTRACAO_INTRA_MUSCULAR],
			['id' => self::ADMINISTRACAO_SUBCUTÂNEA_ID, 'nome' => self::ADMINISTRACAO_SUBCUTÂNEA],
			['id' => self::ADMINISTRACAO_INTRADÉRMICA_ID, 'nome' => self::ADMINISTRACAO_INTRADÉRMICA],
			['id' => self::ADMINISTRACAO_INTRA_ARTERIAL_ID, 'nome' => self::ADMINISTRACAO_INTRA_ARTERIAL],
			['id' => self::ADMINISTRACAO_INTRACARDÍACA_ID, 'nome' => self::ADMINISTRACAO_INTRACARDÍACA],
			['id' => self::ADMINISTRACAO_INTRATECAL_ID, 'nome' => self::ADMINISTRACAO_INTRATECAL],
			['id' => self::ADMINISTRACAO_PERIDURAL_ID, 'nome' => self::ADMINISTRACAO_PERIDURAL],
			['id' => self::ADMINISTRACAO_INTRA_ARTICULAR_ID, 'nome' => self::ADMINISTRACAO_INTRA_ARTICULAR],
			['id' => self::ADMINISTRACAO_CUTÂNEA_ID, 'nome' => self::ADMINISTRACAO_CUTÂNEA],
			['id' => self::ADMINISTRACAO_RESPIRATÓRIA_ID, 'nome' => self::ADMINISTRACAO_RESPIRATÓRIA],
			['id' => self::ADMINISTRACAO_CONJUNTIVAL_ID, 'nome' => self::ADMINISTRACAO_CONJUNTIVAL],
			['id' => self::ADMINISTRACAO_GENITURINÁRIA_ID, 'nome' => self::ADMINISTRACAO_GENITURINÁRIA],
			['id' => self::ADMINISTRACAO_INTRACANAL_ID, 'nome' => self::ADMINISTRACAO_INTRACANAL]
		];

		return $periodicidadeArray[$valor];
	}

	static function existeTipoDeAdmisnitracao($valor)
	{
		$periodadesArray = [
			['id' => self::ADMINISTRACAO_ORAL_ID, 'nome' => self::ADMINISTRACAO_ORAL],
			['id' => self::ADMINISTRACAO_SUBLINGUAL_ID, 'nome' => self::ADMINISTRACAO_SUBLINGUAL],
			['id' => self::ADMINISTRACAO_RETAL_ID, 'nome' => self::ADMINISTRACAO_RETAL],
			['id' => self::ADMINISTRACAO_INTRA_VENOSA_ID, 'nome' => self::ADMINISTRACAO_INTRA_VENOSA],
			['id' => self::ADMINISTRACAO_INTRA_MUSCULAR_ID, 'nome' => self::ADMINISTRACAO_INTRA_MUSCULAR],
			['id' => self::ADMINISTRACAO_SUBCUTÂNEA_ID, 'nome' => self::ADMINISTRACAO_SUBCUTÂNEA],
			['id' => self::ADMINISTRACAO_INTRADÉRMICA_ID, 'nome' => self::ADMINISTRACAO_INTRADÉRMICA],
			['id' => self::ADMINISTRACAO_INTRA_ARTERIAL_ID, 'nome' => self::ADMINISTRACAO_INTRA_ARTERIAL],
			['id' => self::ADMINISTRACAO_INTRACARDÍACA_ID, 'nome' => self::ADMINISTRACAO_INTRACARDÍACA],
			['id' => self::ADMINISTRACAO_INTRATECAL_ID, 'nome' => self::ADMINISTRACAO_INTRATECAL],
			['id' => self::ADMINISTRACAO_PERIDURAL_ID, 'nome' => self::ADMINISTRACAO_PERIDURAL],
			['id' => self::ADMINISTRACAO_INTRA_ARTICULAR_ID, 'nome' => self::ADMINISTRACAO_INTRA_ARTICULAR],
			['id' => self::ADMINISTRACAO_CUTÂNEA_ID, 'nome' => self::ADMINISTRACAO_CUTÂNEA],
			['id' => self::ADMINISTRACAO_RESPIRATÓRIA_ID, 'nome' => self::ADMINISTRACAO_RESPIRATÓRIA],
			['id' => self::ADMINISTRACAO_CONJUNTIVAL_ID, 'nome' => self::ADMINISTRACAO_CONJUNTIVAL],
			['id' => self::ADMINISTRACAO_GENITURINÁRIA_ID, 'nome' => self::ADMINISTRACAO_GENITURINÁRIA],
			['id' => self::ADMINISTRACAO_INTRACANAL_ID, 'nome' => self::ADMINISTRACAO_INTRACANAL]
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