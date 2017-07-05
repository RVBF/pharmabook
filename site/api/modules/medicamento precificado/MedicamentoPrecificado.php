<?php

/**
 *	MedicamentoPrecificado
 *
 *  @author Rafael Vinicius Barros Ferreira
 *  @version	0.1
 */
class MedicamentoPrecificado {

	private $id;
	private $preco;
	private $farmacia;
	private $medicamento;
	private $criador;
	private $atualizador;
	private $imagem
	private $dataCriacao;
	private $dataAtualizacao;

	const CAMINHO_IMAGEM = '../../../imagens/medicamentos';
	const CAMINHO_IMAGEM = '../../../imagens/medicamentos';
	const LARGURA_MAXIMA_IMAGEM = 150; // Largura máxima em pixels
	const ALTURA_MAXIMA_IMAGEM = 180;// Altura máxima em pixels
	const ALTURA_MAXIMA_IMAGEM = 1000; // Tamanho máximo do arquivo em bytes

	function __construct(
		$id = '',
		$preco = '',
		$farmacia = '',
		$medicamento = '',
		$criador = '',
		$imagem = '',
		$atualizador = '',
		$dataCriacao = '',
		$dataAtualizacao = ''
	)
	{
		$this->id = $id;
		$this->preco = $preco;
		$this->farmacia = $farmacia;
		$this->medicamento = $medicamento;
		$this->criador = $criador;
		$this->imagem = $imagem;
		$this->atualizador = $atualizador;
		$this->dataCriacao = $dataCriacao;
		$this->dataAtualizacao = $dataAtualizacao;
	}

	public function getId(){ return $this->id; }
	public function setId($id){ $this->id = $id; }

	public function getImagem(){ return $this->imagem; }
	public function setImagem($imagem){ $this->imagem = $imagem; }

	public function getPreco(){ return $this->preco; }
	public function setPreco($preco){ $this->preco = $preco; }

	public function getFarmacia(){ return $this->farmacia; }
	public function setFarmacia($farmacia){ $this->farmacia = $farmacia; }

	public function getMedicamento(){ return $this->medicamento; }
	public function setMedicamento($medicamento){ $this->medicamento = $medicamento; }

	public function getCriador(){ return $this->criador; }
	public function setCriador($criador){ $this->criador = $criador; }

	public function getAtualizador(){ return $this->atualizador; }
	public function setAtualizador($atualizador){ $this->atualizador = $atualizador; }

	public function getDataCriacao(){ return $this->dataCriacao; }
	public function setDataCriacao($dataCriacao){ $this->dataCriacao = $dataCriacao; }

	public function getDataAtualizacao(){ return $this->dataAtualizacao; }
	public function setDataAtualizacao($dataAtualizacao){ $this->dataAtualizacao = $dataAtualizacao; }
}

?>