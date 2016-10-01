<?php

use phputil\Session;

/**
 * Serviço de Login
 *
 * @author	Rafael Vinicius barros ferreira
 */

class ServicoUsuario{
	
	private $sessao;
	private $colecao;
	
	function __construct(ColecaoProfessor $colecao, Session $sessao)
	{
		$this->colecao = $colecao;
		$this->sessao = $sessao;
	}
	
	/**
	 *	  Método que recebe a identificação (E-mail) e faz a validação de acordo 
	 * com os requisitos específicos.
	 * 
	 * @param string $login E-mail a ser validada.
	 * @throws ServicoException.
	 */
	private function validaEmail($login)
	{
		if (! filter_var($login, FILTER_VALIDATE_EMAIL ))
		{
			throw new ServicoException('Por favor, informe o email.' );
		}
		
		$tamEmail = mb_strlen($login );
		if ($tamEmail < Professor::TAM_MIN_EMAIL)
		{
			throw new ServicoException('O e-mail deve ter pelo menos ' . Professor::TAM_MIN_EMAIL . ' caracteres.' );
		}
		
		if ($tamEmail > Professor::TAM_MAX_EMAIL)
		{
			throw new ServicoException('O e-mail deve ter no máximo ' . Professor::TAM_MAX_EMAIL . ' caracteres.' );
		}

		$dominio = explode('@', $login );
		if(strcasecmp($dominio[ 1 ], Professor::DOMINIO_CEFET ) != 0)
		{
			throw new ServicoException('Por favor, informe o e-mail com domínio do cefet.' );
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
		$tamSenha = mb_strlen($senha );
		if ($tamSenha < Professor::TAM_MIN_SENHA)
		{
			throw new ServicoException('A senha deve ter pelo menos ' . Professor::TAM_MIN_SENHA . ' caracteres.' );
		}
		if ($tamSenha > Professor::TAM_MAX_SENHA)
		{
			throw new ServicoException('A senha deve ter no máximo ' . Professor::TAM_MAX_SENHA . ' caracteres.' );
		}
	}
	
	/**
	 *	  Método que recebe a identificação (SIAPE) e faz a validação de acordo 
	 * com os requisitos específicos.
	 * 
	 * @param string $login SIAPE a ser validada.
	 * @throws ServicoException.
	 */
	private function validaSiape($siape)
	{
		if (! is_numeric($siape ))
		{
			throw new ServicoException('Por favor, informe a matrícula SIAPE.' );
		}
		
		$tamSiape = mb_strlen($siape );
		if ($tamSiape < Professor::TAM_MIN_SIAPE)
		{
			throw new ServicoException('A matrícula SIAPE deve ter pelo menos ' . Professor::TAM_MIN_SIAPE . ' caracteres.' );
		}
		if ($tamSiape > Professor::TAM_MAX_SIAPE)
		{
			throw new ServicoException('A matrícula SIAPE deve ter no máximo ' . Professor::TAM_MAX_SIAPE . ' caracteres.' );
		}
	}

	/**
	 *	  Método que recebe a identificação e retorna verdadeiro caso a mesma seja um e-mail.
	 * 
	 * @param string $login E-mail ou Siape a ser verificada.
	 * @return true.
	 */
	private function ehEmail($login)
	{
		return mb_strstr($login, '@' ) !== false;
	}

	/**
	 *	  Método que recebe a identificação (SIAPE ou e-mail)  e senha e busca um professor 
	 * ativo, caso o não econtra-lo é lançada uma ServicoException.
	 * 
	 * @param string $login SIAPE/E-mail a ser procurada.
	 * @param string $senha Senha a ser procurada.
	 * @return $professor  Caso encontrado, retorna um Objeto Professor.
	 * @throws ServicoException.
	 */
	private function comloginESenha($login, $senha)
	{

		$ehEmail = $this->ehEmail($login );

		if ($ehEmail)
		{
			$this->validaEmail($login );
		}
		else 
		{
			$this->validaSiape($login );
		}
	
		$this->validaSenha($senha );
	
		$professor = ($ehEmail )
		? $this->colecao->comEmailESenha($login, $senha )
		: $this->colecao->comSiapeESenha($login, $senha );
	
		if (null === $professor)
		{
		throw new ServicoException('Identificação ou senha inválidos ou você ainda não foi ativado.' );
		}
	
		return $professor;
	}

	/**
	 *	  Método que recebe a identificação (SIAPE ou e-mail)  e senha . Caso o usuário(Professor) 
	 * for encontrado o mesmo irá logar no sistema.
	 *
	 * @param string $login SIAPE/E-mail a ser procurada.
	 * @param string $senha Senha a ser procurada.
	 */
	function logar($login, $senha)
	{
		$professor = $this->comloginESenha($login, $senha );
		$this->sessao->set('id', $professor->getId() );
		$this->sessao->set('nome', $professor->getNome() );
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

		try {
			$this->validaSenha($atual );
			$this->validaSenha($nova );
			$this->validaSenha($confirmacao );

			if(!$this->saoSenhasIguais($nova, $confirmacao ))
	{
				throw new ServicoException("A senha nova e a senha de confirmação são diferentes!" );
			}

			if(!$this->colecao->senhaAtualEstaCorreta($this->sessao->get('id'), $atual ))
	{
				throw new ServicoException("A senha atual está incorreta!" );
			}

			$this->colecao->atualizarSenha($this->sessao->get('id'), $nova );
			
		} catch (\Exception $e)
	{
			throw new ServicoException($e->getMessage(), $e->getCode(), $e );
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