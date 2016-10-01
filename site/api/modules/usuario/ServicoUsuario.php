<?php

use phputil\Session;

/**
* Serviço de Login
*
* @author	Rafael Vinicius barros ferreira
*/

class ServicoUsuario {
	
	private $sessao;
	private $colecao;
	
	function __construct(ColecaoUsuario $colecao, Session $sessao)
	{
		$this->colecao = $colecao;
		$this->sessao = $sessao;
	}
	
	/**
	*  Método que recebe a identificação (E-mail) e faz a validação de acordo 
	* com os requisitos específicos.
	* 
	* @param string $login E-mail a ser validada.
	* @throws ServicoException.
	*/
	private function validarEmail($login)
	{
		if (! filter_var($login, FILTER_VALIDATE_EMAIL))
		{
			throw new ServicoException('Por favor, informe o email.');
		}
		
		$tamEmail = mb_strlen($login);

		if ($tamEmail < Usuario::TAMANHO_MINIMO_EMAIL)
		{
			throw new ServicoException('O e-mail deve possuir no mímino ' . Usuario::TAMANHO_MINIMO_EMAIL . ' caracteres.');
		}
		
		if ($tamEmail > Usuario::TAMANHO_MAXIMO_EMAIL)
		{
			throw new ServicoException('O e-mail deve possuir no máximo ' . Usuario::TAMANHO_MAXIMO_EMAIL . ' caracteres.');
		}
	}

	/**
	*  Método que recebe a identificação (E-mail) e faz a validação de acordo 
	* com os requisitos específicos.
	* 
	* @param string $login E-mail a ser validada.
	* @throws ServicoException.
	*/
	private function validarLogin($login)
	{
		if (! filter_var($login, FILTER_VALIDATE_EMAIL))
		{
			throw new ServicoException('Por favor, informe o email.');
		}
		
		$tamEmail = mb_strlen($login);

		if ($tamEmail < Usuario::TAMANHO_MINIMO_LOGIN)
		{
			throw new ServicoException('O login deve possuir no mímino ' . Usuario::TAMANHO_MINIMO_LOGIN . ' caracteres.');
		}
		
		if ($tamEmail > Usuario::TAMANHO_MAXIMO_LOGIN)
		{
			throw new ServicoException('O login deve possuir no máximo ' . Usuario::TAMANHO_MAXIMO_LOGIN . ' caracteres.');
		}
	}
	
	/**
	*  Método que recebe a senha e faz a validação de acordo com os requisitos específicos.
	* 
	* @param string $senha Senha a ser validada.
	* @throws ServicoException.
	*/
	private function validarSenha($senha)
	{
		$tamSenha = mb_strlen($senha);

		if ($tamSenha < Usuario::TAMANHO_MINIMO_SENHA)
		{
			throw new ServicoException('A senha deve possuir no mínimo ' . Usuario::TAMANHO_MINIMO_SENHA . ' caracteres.');
		}
		if ($tamSenha > Usuario::TAMANHO_MAXIMO_SENHA)
		{
			throw new ServicoException('A senha deve possuir no máximo ' . Usuario::TAMANHO_MAXIMO_SENHA . ' caracteres.');
		}
	}

	/**
	* Método que recebe a identificação e retorna verdadeiro caso a mesma seja um e-mail.
	* 
	* @param string $login E-mail ou Siape a ser verificada.
	* @return true.
	*/
	private function ehEmail($login)
	{
		return mb_strstr($login, '@' ) !== false;
	}

	/**
	* Método que recebe a identificação (SIAPE ou e-mail)  e senha e busca um Usuario 
	* ativo, caso o não econtra-lo é lançada uma ServicoException.
	* 
	* @param string $login SIAPE/E-mail a ser procurada.
	* @param string $senha Senha a ser procurada.
	* @return $Usuario  Caso encontrado, retorna um Objeto Usuario.
	* @throws ServicoException.
	*/
	private function comloginESenha($login, $senha)
	{

		$ehEmail = $this->ehEmail($login);

		if ($ehEmail)
		{
			$this->validaEmail($login);
		}
		else 
		{
			$this->validaSiape($login);
		}
	
		$this->validaSenha($senha);
	
		$Usuario = ($ehEmail ) ? $this->colecao->comEmailESenha($login, $senha ) : $this->colecao->comLoginESenha($login, $senha);
	
		if (null === $Usuario)
		{
			throw new ServicoException('Identificação ou senha inválidos ou você ainda não foi ativado.');
		}
	
		return $Usuario;
	}

	/**
	*  Método que recebe a identificação (login ou e-mail)  e senha . Caso o usuário(Usuario) 
	* for encontrado o mesmo irá logar no sistema.
	*
	* @param string $login SIAPE/E-mail a ser procurada.
	* @param string $senha Senha a ser procurada.
	*/
	function logar($login, $senha)
	{
		$Usuario = $this->comloginESenha($login, $senha);
		$this->sessao->set('id', $Usuario->getId());
		$this->sessao->set('nome', $Usuario->getNome());
		$this->sessao->set('login', $Usuario->getLogin());
	}
	
	/**
	* 	Método logout de usuário(Usuario).
	*/
	function sair()
	{
		$this->sessao->destroy();
	}

	/**
	*  Método que recebe as senhas atual, nova e de confirmacao, e caso as senhas nova e
	* de confirmacao sejam iguais e a senha atual esteja correta, utiliza o método
	* atualizarSenha da colecao para substituir a senha atual pela nova.
	* @param string $atual senha atual
	* @param string $nova senha nova
	* @param string $confirmacao senha confirmacao
	* @throws ServicoException.
	*/
	function atualizarSenha($atual, $nova, $confirmacao)
	{
		try
		{
			$this->validaSenha($atual);
			$this->validaSenha($nova);
			$this->validaSenha($confirmacao);

			if(!$this->saoSenhasIguais($nova, $confirmacao))
			{
				throw new ServicoException("A senha nova e a senha de confirmação são diferentes!");
			}

			if(!$this->colecao->senhaAtualEstaCorreta($this->sessao->get('id'), $atual))
			{
				throw new ServicoException("A senha atual está incorreta!");
			}

			$this->colecao->atualizarSenha($this->sessao->get('id'), $nova);
		}
		catch(\Exception $e)
		{
			throw new ServicoException($e->getMessage(), $e->getCode(), $e);
		}
	}

	/**
	*  Método que recebe duas senhas e verifica se elas são iguais.
	* @param string $senha1 senha
	* @param string $senha2 senha
	* @return boolean
	* @throws ServicoException.
	*/
	private function saoSenhasIguais($senha1, $senha2)
	{
		return $senha1 === $senha2;
	}
}

?>