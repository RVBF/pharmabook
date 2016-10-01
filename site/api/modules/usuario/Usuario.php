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
	private $email;
	private $login;
	private $senha;
	private $telefone;
	private $dataCriacao;
	private $dataAtualizacao;

	const TABELA = 'usuario';

	const TAMANHO_MINIMO_NOME = 5;
	const TAMANHO_MAXIMO_NOME = 100;

	const TAMANHO_MINIMO_EMAIL = 5;	
	const TAMANHO_MAXIMO_EMAIL = 100; 

	const TAMANHO_MINIMO_LOGIN = 5;
	const TAMANHO_MAXIMO_LOGIN = 14; 

	const TAMANHO_MINIMO_SENHA = 8; 
	const TAMANHO_MAXIMO_SENHA = 50; 

	function __construct(
		$id = '' ,
		$nome = '',
		$email = '',
		$login = '',
		$senha = '',
		$telefone =  '',
		$dataCriacao = '',
		$dataAtualizacao = ''
	)
	{ 
		$this->id = $id;
		$this->nome = $nome;
		$this->email = $email;
		$this->login = $login;
		$this->senha = $senha;
		$this->telefone = $telefone;
		$this->dataCriacao = $dataCriacao;
		$this->dataAtualizacao = $dataAtualizacao;
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
 	
	public function getdataCriacao(){ return $this->dataCriacao; }
	public function setdataCriacao($dataCriacao){ $this->dataCriacao = $dataCriacao; }

	public function getDataAtualizacao(){ return $this->dataAtualizacao; }
	public function setDataAtualizacao($dataAtualizacao){ $this->dataAtualizacao = $dataAtualizacao; }

	public function getTabela(){ return self::TABELA; }
	public function getTamanhoMinimoNome(){ return self::TAMANHO_MINIMO_NOME; } 
	public function getTamanhoMaximoNome(){ return self::TAMANHO_MAXIMO_NOME; } 
	public function getTamanhoMaximoEmail(){ return self::TAMANHO_MAXIMO_EMAIL; }
	public function getTamanhoMinimoEmail(){ return self::TAMANHO_MINIMO_EMAIL; }
	public function getTamanhoMaximoLogin(){ return self::TAMANHO_MAXIMO_LOGIN; }
	public function getTamanhoMinimoLogin(){ return self::TAMANHO_Minimo_LOGIN; }
	public function getTamanhoMaximoSenha(){ return self::TAMANHO_MAXIMO_SENHA; }
	public function getTamanhoMinimoSenha(){ return self::TAMANHO_MINIMO_SENHA; }
}

?>