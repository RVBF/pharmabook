<?php

/**
 *	Usuario
 *
 *  @author Rafael Vinicius Barros Ferreira
 *  @version	0.1
 */
class Usuario {

	private $id;
	private $nome;
	private $sobrenome;
	private $email;
	private $login;
	private $senha;
	private $endereco;
	private $dataCriacao;
	private $dataAtualizacao;

	const TABELA = 'usuario';

	const TAMANHO_MINIMO_NOME = 2;
	const TAMANHO_MAXIMO_NOME = 100;

	const TAMANHO_MINIMO_SOBRENOME = 2;
	const TAMANHO_MAXIMO_SOBRENOME = 100;

	const TAMANHO_MINIMO_LOGIN = 5;
	const TAMANHO_MAXIMO_LOGIN = 30;

	const TAMANHO_MINIMO_SENHA = 8;
	const TAMANHO_MAXIMO_SENHA = 50;

	function __construct(
		$id = '' ,
		$nome = '',
		$sobrenome = '',
		$email = '',
		$login = '',
		$senha = '',
		$endereco = (object) [],
		$dataCriacao = '',
		$dataAtualizacao = ''
	)
	{
		$this->id = $id;
		$this->nome = $nome;
		$this->sobrenome = $sobrenome;
		$this->email = $email;
		$this->login = $login;
		$this->senha = $senha;
		$this->endereco = $endereco;
		$this->dataCriacao = $dataCriacao;
		$this->dataAtualizacao = $dataAtualizacao;
 	}

	public function getId(){ return $this->id; }
	public function setId($id){ $this->id = $id; }

	public function getNome(){ return $this->nome; }
	public function setNome($nome){ $this->nome = $nome; }

	public function getSobrenome(){ return $this->sobrenome; }
	public function setSobresobrenome($sobrenome){ $this->sobrenome = $sobrenome; }

	public function getEmail(){ return $this->email; }
	public function setEmail($email){ $this->email = $email; }

	public function getLogin(){ return $this->login; }
	public function setLogin($login){ $this->login = $login; }

	public function getSenha(){ return $this->senha; }
	public function setSenha($senha){ $this->senha = $senha; }

	public function getEndereco() { return $this->endereco; }
	public function setEndereco($endereco) { $this->endereco = $endereco; }

	public function getDataCriacao(){ return $this->dataCriacao; }
	public function setDataCriacao($dataCriacao){ $this->dataCriacao = $dataCriacao; }

	public function getDataAtualizacao(){ return $this->dataAtualizacao; }
	public function setDataAtualizacao($dataAtualizacao){ $this->dataAtualizacao = $dataAtualizacao; }
}
?>