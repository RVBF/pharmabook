<?php

use phputil\Session;

/**
 * Serviço de Login
 *
 * @author	Rafael Vinicius Barros Ferreira
 */

class ServicoUsuario{
	
	private $sessao;
	private $colecao;
	
	function __construct(ColecaoUsuario $colecao, Session $sessao)
	{
		$this->colecao = $colecao;
		$this->sessao = $sessao;
	}
	
	/**
	 *	  Recebe um email ou senha e valida
	 *
	 * @param string $identificacao E-mail a ser validada.
	 * @throws ServicoException.
	 */
	private function validadarEmail($identificacao)
	{
		if (! filter_var($identificacao, FILTER_VALIDATE_EMAIL))
		{
			throw new ServicoException('Por favor, informe o email.');
		}
		
		$tamEmail = mb_strlen($identificacao);

		if ($tamEmail < Usuario::TAMANHO_MINIMO_EMAIL)
		{
			throw new ServicoException('O e-mail deve ter pelo menos ' . Usuario::TAM_MIN_EMAIL . ' caracteres.');
		}
		
		if ($tamEmail > Usuario::TAM_MAX_EMAIL)
		{
			throw new ServicoException('O e-mail deve ter no máximo ' . Usuario::TAM_MAX_EMAIL . ' caracteres.');
		}
	}
	
	/**
	 *	  Método que recebe o Login e valida de acordo com os requisitos
	 * 
	 * @param string $login Login a ser validada.
	 * @throws ServicoException.
	 */
	private function validaLogin($login)
	{
		$tamanhoLogin = mb_strlen($login);

		if ($tamanhoLogin >= Usuario::TAMANHO_MINIMO_LOGIN)
		{
			throw new ServicoException('A senha deve ter pelo menos ' . Usuario::TAM_MIN_SENHA . ' caracteres.');
		}
		if ($tamanhoLogin > Usuario::TAMANHO_MAXIMO_LOGIN)
		{
			throw new ServicoException('A senha deve ter no máximo ' . Usuario::TAMANHO_MAXIMO_LOGIN . ' caracteres.');
		}
	}	

	/**
	 *	  Método que recebe a senha e faz a validação de acordo com os requisitos específicos.
	 * 
	 * @param string $senha Senha a ser validada.
	 * @throws ServicoException.
	 */
	private function validaSenha($senha)
	{
		$tamSenha = mb_strlen($senha);

		if ($tamSenha < Usuario::TAM_MIN_SENHA)
		{
			throw new ServicoException('A senha deve ter pelo menos ' . Usuario::TAM_MIN_SENHA . ' caracteres.');
		}
		if ($tamSenha > Usuario::TAM_MAX_SENHA)
		{
			throw new ServicoException('A senha deve ter no máximo ' . Usuario::TAM_MAX_SENHA . ' caracteres.');
		}
	}
	
	/**
	 *	  Método que recebe a identificação e retorna verdadeiro caso a mesma seja um e-mail.
	 * 
	 * @param string $identificacao E-mail ou Siape a ser verificada.
	 * @return true.
	 */
	private function ehEmail($identificacao)
	{
		return (mb_strstr($identificacao, '@') !== false) ? true : false;
	}

	/**
	 *	  Método que recebe a identificação (EMAIL ou LOGIN)  e senha e busca um Usuario 
	 * ativo, caso o não econtra-lo é lançada uma ServicoException.
	 * 
	 * @param string $identificacao EMAIL/Login a ser procurada.
	 * @param string $senha Senha a ser procurada.
	 * @return $professor  Caso encontrado, retorna um Objeto Professor.
	 * @throws ServicoException.
	 */
	private function validarAcesso($identificacao, $senha)
	{
		if ($this->ehEmail($identificacao))
		{
			$this->validaEmail($identificacao);
		} 
		else
		{
			$this->validaLogin($identificacao);
		}
	
		$this->validaSenha($senha);
	
		$usuario = ($ehEmail)
		? $this->colecao->logarComEmail($identificacao, $senha)
		: $this->colecao->logarComLogin($identificacao, $senha);
	
		if (null === $usuario)
		{
		throw new ServicoException('Identificação ou senha inválidos ou você ainda não foi ativado.');
		}
	
		return $usuario;
	}

	/**
	 *	  Método que recebe a identificação (SIAPE ou e-mail)  e senha . Caso o usuário(Professor) 
	 * for encontrado o mesmo irá logar no sistema.
	 *
	 * @param string $identificacao SIAPE/E-mail a ser procurada.
	 * @param string $senha Senha a ser procurada.
	 */
	function logar($identificacao, $senha)
	{
		$professor = $this->comIdentificacaoESenha($identificacao, $senha);
		$this->sessao->set('id', $usuario->getId());
		$this->sessao->set('nome', $usuario->getNome());
	}
	
	/**
	 * 	Método logout de usuário(Professor).
	 */
	function sair()
	{
		$this->sessao->destroy();
	}

	/**
	 *	  Método que recebe as senhas atual, nova e de confirmacao, e caso as senhas nova e
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
		catch (\Exception $e)
		{
			throw new ServicoException($e->getMessage(), $e->getCode(), $e);
		}
	}

	/**
	 *	  Método que recebe duas senhas e verifica se elas são iguais.
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