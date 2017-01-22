<?php

/**
 *	TipoMedicamento
 *
 *  @author Rafael Vinicius Barros Ferreira
 *  @version	1.0
 */
class TipoMedicamento {
	private $id;
	private $unidadeMedida;
	private $medicamentosForma;

	function __construct(
		$id = '',
		$unidadeMedida = '',
		$quantidadeTotal = '',
		$medicamentosForma = ''
	)
	{
		$this->id = $id;
		$this->unidadeMedida = $unidadeMedida;
		$this->medicamentosForma = $medicamentosForma;
	}

	public function getId(){ return $this->id; }
	public function setId($id){ $this->id = $id; }

	public function getUnidadeMedida(){ return $this->validade; }
	public function setUnidadeMedida($validade){ $this->validade = $validade; }

	public function getMedicamentosForma(){ return $this->medicamentosForma; }
	public function setMedicamentosForma($medicamentosForma){ $this->medicamentosForma = $medicamentosForma; }
}

?>