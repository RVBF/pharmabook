<?php

/**
 *	Farmácia
 *
 *  @authoRafael Vinicius Barros Ferreira
 *  @version	0.1
 */
class Farmacia {

	private $id;
	private $nome;
	private $telefone;
	private $endereco;
	private $dataCriacao;
	private $dataAtualizacao;

	const TAMANHO_MAXIMO_NOME = 80;
	const TAMANHO_MINIMO_NOME = 3;
	const TAMANHO_TELEFONE = 12;

	function __construct(
		$id = '',
		$nome = '',
		$telefone = '',
		$endereco = '',
		$dataCriacao = '',
		$dataAtualizacao = ''
	)
	{
		$this->id = $id;
		$this->nome = $nome;
		$this->telefone = $telefone;
		$this->endereco = $endereco;
		$this->dataCriacao = $dataCriacao;
		$this->dataAtualizacao = $dataAtualizacao;
	}

	public function getId(){ return $this->id; }
	public function setId($id){ $this->id = $id; }

	public function getNome(){ return $this->nome; }
	public function setNome($nome){ $this->nome = $nome; }

	public function getTelefone(){ return $this->telefone; }
	public function setTelefone($telefone){ $this->telefone = $telefone; }

	public function getEndereco(){ return $this->endereco; }
	public function setEndereco($endereco){ $this->endereco = $endereco; }


	public function getDataCriacao(){ return $this->dataCriacao; }
	public function setDataCriacao($dataCriacao){ $this->dataCriacao = $dataCriacao; }

	public function getDataAtualizacao(){ return $this->dataAtualizacao; }
	public function setDataAtualizacao($dataAtualizacao){ $this->dataAtualizacao = $dataAtualizacao;}
}

?>