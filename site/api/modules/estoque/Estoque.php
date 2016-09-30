<?php

/**
 *	Estoque
 *
 *  @authoRafael Vinicius Barros Ferreira
 *  @version	0.1
 */
class Estoque {
	
	private $id;
	private $quantidade;
	private $dataEntrada;
	private $dataSaida;

	function __construct ($id = '', $quantidade = '', $dataEntrada = '', $dataSaida = '')
	{ 
		$this->id = $id;
		$this->quantidade = $quantidade;
		$this->dataEntrada = $dataEntrada;
		$this->dataSaida = $dataSaida;
 	}
	
	public function getId(){ return $this->id; }
	public function setId($id){ $this->id = $id; }
	
	public function getQuantidade(){ return $this->quantidade; }
	public function setQuantidade($quantidade){ $this->quantidade = $quantidade; }

	public function getDataEntrada(){ return $this->dataEntrada; }
	public function setDataEntrada($dataEntrada){ $this->dataEntrada = $dataEntrada; }

	public function getDataSaida(){ return $this->dataSaida; }
	public function setDataSaida($dataSaida){ $this->dataSaida = $dataSaida; }
}

?>