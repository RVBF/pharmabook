<?php

/**
 *	NotificaoMedicamentoPessoal
 *
 *  @author Rafael Vinicius Barros Ferreira
 *  @version	1.0
 */
class NotificaoMedicamentoPessoal {

	private $id;
	private $mensagem;
	private $data;
	private $medicamentoPessoal;
	private $dataCriacao;
	private $dataAtualizacao;

	function __construct(
		$id = 0,
		$mensagem = '',
		$data = '',
		$medicamentoPessoal = '',
		$dataCriacao = '',
		$dataAtualizacao = ''
	)
	{
		$this->id = $id;
		$this->mensagem = $mensagem;
		$this->data = $data;
		$this->medicamentoPessoal = $medicamentoPessoal;
		$this->dataCriacao = $dataCriacao;
		$this->dataAtualizacao = $dataAtualizacao;
	}

	public function getId(){ return $this->id; }
	public function setId($id){ $this->id = $id; }

	public function getMensagem(){ return $this->mensagem; }
	public function setMensagem($mensagem){ $this->mensagem = $mensagem; }

	public function getData(){ return $this->data; }
	public function setData($data){ $this->data = $data; }

	public function getMedicamentoPessoal(){ return $this->medicamentoPessoal; }
	public function setMedicamentoPessoal($medicamentoPessoal){ $this->medicamentoPessoal = $medicamentoPessoal; }

	public function getDataCriacao(){ return $this->dataCriacao; }
	public function setDataCriacao($dataCriacao){ $this->dataCriacao = $dataCriacao; }

	public function getDataAtualizacao(){ return $this->dataAtualizacao; }
	public function setDataAtualizacao($dataAtualizacao){ $this->dataAtualizacao = $dataAtualizacao;}
}

?>