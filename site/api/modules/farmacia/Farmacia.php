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
	private $preco;
	private $endereco;
	private $dataCriacao;
	private $dataAtualizacao;

	function __construct($id = '', $nome = '', $preco = '', $dataCriacao = '', $dataAtualizacao = '')
	{ 
		$this->id = $id;
		$this->nome = $nome;
		$this->preco = $preco;
		$this->endereco = $endereco;
		$this->dataCriacao = $dataCriacao;
		$this->dataAtualizacao = $dataAtualizacao;		
 	}
	
	public function getId(){ return $this->id; }
	public function setId($id){ $this->id = $id; }
	
	public function getNome(){ return $this->nome; }
	public function setNome($nome){ $this->nome = $nome; }

	public function getPreco(){ return $this->preco; }
	public function setPreco($preco){ $this->preco = $preco; }

	public function getEndereco(){ return $this->endereco; }
	public function setEndereco($endereco){ $this->endereco = $endereco; }


	public function getDataCriacao(){ return $this->dataCriacao; }
	public function setDataCriacao($dataCriacao){ $this->dataCriacao = $dataCriacao; }

	public function getDataAtualizacao(){ return $this->dataAtualizacao; }
	public function setDataAtualizacao($dataAtualizacao){ $this->dataAtualizacao = $dataAtualizacao;}
}

?>