<?php

/**
 *	Usuario
 *
 *  @authoRafael Vinicius Barros Ferreira
 *  @version	0.1
 */
class Usuario {
	
	private $id;
	private $nome;
	private $email;
	private $login;
	private $senha;
	private $telefone;
	private $criacao;
	private $atualizacao;

	const TAMANHO_MINIMO_NOME = 5;
	const TAMANHO_MAXIMO_NOME = 100;

	const TAMANHO_MINIMO_EMAIL = 5;	
	const TAMANHO_MAXIMO_EMAIL = 100; 

	const TAMANHO_MINIMO_LOGIN = 5;
	const TAMANHO_MAXIMO_LOGIN = 14; 

	const TAMANHO_MINIMO_SENHA = 8; 
	const TAMANHO_MAXIMO_SENHA = 50; 

	function __construct( $id = '' , $nome = '', $email = '', $login = '', $senha = '', $telefone =  '', $criacao = '', $atualizacao = '')
	{ 
		$this->id = $id;
		$this->nome = $nome;
		$this->email = $email;
		$this->login = $login;
		$this->senha = $senha;
		$this->telefone = $telefone;
		$this->criacao = $criacao;
		$this->atualizacao = $atualizacao;
 	}
	
	public function getId(){ return $this->id; }
	public function setId($id){ $this->id = $id; }
	
	public function getNome(){ return $this->nome; }
	public function setNome($nome){ $this->nome = $nome; }

	public function getEmail(){ return $this->email; }
	public function setEmail($email){ $this->email = $email; }

	public function getLogin(){ return $this->login; }
	public function setLogin($login){ $this->login = $login; }


	public function getSenha(){ return $this->senha; }
	public function setSenha($senha){ $this->senha = $senha; }

	public function getTelefone(){ return $this->telefone; }
	public function setTelefone($telefone){ $this->telefone = $telefone; }
 	
	public function getCriacao(){ return $this->criacao; }
	public function setCriacao($criacao){ $this->criacao = $criacao; }

	public function getAtualizacao(){ return $this->atualizacao; }
	public function setAtualizacao($atualizacao){ $this->atualizacao = $atualizacao; }

	public function getTamanhoMinimoNome() { return self::TAMANHO_MINIMO_NOME; }
	public function getTamanhoMaximoNome() { return self::TAMANHO_MAXIMO_NOME; }
	public function getTamanhoMaximoEmail(){ return self::TAMANHO_MAXIMO_EMAIL; }
	public function getTamanhoMinimoEmail(){ return self::TAMANHO_Minimo_EMAIL; }
	public function getTamanhoMaximoLogin(){ return self::TAMANHO_MAXIMO_LOGIN; }
	public function getTamanhoMinimoLogin(){ return self::TAMANHO_Minimo_LOGIN; }
	public function getTamanhoMaximoSenha(){ return self::TAMANHO_MAXIMO_SENHA; }
	public function getTamanhoMinimoSenha(){ return self::TAMANHO_Minimo_SENHA; }
}

?>