<?php

use phputil\Session;

/**
 *	Coleção de Login em Banco de Dados Relacional.
 *
 *  @author		Rafael Vinicius Barros Ferreira
 *	@version	0.1
 */

class ColecaoLoginEmBDR implements ColecaoLogin
{
	
	const TABELA = 'usuario';
	
	private $pdoW;
	private $sessao;
	
	function __construct(PDOWrapper $pdoW)
	{
		$this->pdoW = $pdoW;
		$this->sessao = new Session();
	}

	/**
	* 	Método login de usuário.
	*/
	function sessaoAtiva()
	{
		try
		{
			return $this->sessao->name();	
		}
		catch (Exception $e) 
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		
	}

	function logar($obj)
	{
		$this->validarSenha($obj->getSenha());

		$obj->setSenha($this->gerarHashDeSenhaComSaltEmMD5($obj->getSenha()));

		try
		{
			if($this->validarFormatoDeEmail($obj->getIdentificador()))
			{
				$login = $this->comEmail($obj->getIdentificador());

				if(count($login))
				{
					if($login[0]['senha'] === $obj->getSenha())
					{
						if(!$this->sessao->enabled())
						{
							$this->sessao->start();
						}

						$this->sessao->setCookieParams( $lastOneDay = 60 * 60 * 24, $path = '/', Servidor::http(), false, $httponly = true);
						$this->sessao->setName('usuario');
						$this->sessao->set('id', $login[0]['id']);
						$this->sessao->set('nome', $login[0]['nome']);
					}
				}
				else
				{
					throw new Exception("O e-mail inserido não corresponde a nenhuma conta cadastrada no sistema.");
				}
			}
			elseif($this->validarFormatoLogin($obj->getIdentificador()))
			{
				$login = $this->comLogin($obj->getIdentificador());

				if(count($login))
				{

					if($login[0]['senha'] === $obj->getSenha())
					{
						if(!$this->sessao->enabled())
						{
							$this->sessao->start();
						}
						$this->sessao->setCookieParams( $lastOneDay = 60 * 60 * 24, $path = '/', Servidor::http(), false, $httponly = true);
						$this->sessao->setName('usuario');
						$this->sessao->set('id', $login[0]['id']);
						$this->sessao->set('nome', $login[0]['nome']);
					}
				}
				else
				{
					throw new Exception("O login inserido não corresponde a nenhuma conta cadastrada no sistema.");
				}
			}
			
		}
		catch (Exception $e) 
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}
	/**
	 * 	Método logout de usuário.
	 */
	function sair()
	{
		$this->sessao->destroy();
	}

	function construirObjeto(array $row)
	{
		return new Login(
			$row['identificador'],
			$row['senha']
		);
	}

	function contagem() 
	{
		try 
		{
			return $this->pdoW->countRows(self::TABELA);
		} 
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}		
	}


	private function comEmail($email)
	{
		try 
		{
			$sql = 'SELECT * from '. self::TABELA . ' WHERE email = :email';

			return $this->pdoW->query( $sql, ['email' => $email]);
		}
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}	

	private function comLogin($login)
	{
		try 
		{
			$sql = 'SELECT * from '. self::TABELA . ' WHERE login = :login';

			return $this->pdoW->query( $sql, ['login' => $login]);
		}
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}

	/**
	*  Valida o formato de email.
	*  @throws ColecaoException
 	*/
	private function validarFormatoDeEmail($email)
	{
		$conta = "^[a-zA-Z0-9\._-]+@";
		$domino = "[a-zA-Z0-9\._-]+.";
		$extensao = "([a-zA-Z]{2,4})$";

		$pattern = $conta.$domino.$extensao;

		if(ereg($pattern, $email))
		{
			return true;	
		}
		else
		{
			return false;	
		}	
	}
	
	/**
	*  Valida o formato de login.
	*  @throws ColecaoException
 	*/
	private function validarFormatoLogin($login)
	{
		$formato = "[a-zA-Z0-9\. _-]+.";

		if (ereg($formato, $login))
		{
			return true;	
		}
		else
		{
			return false;	
		}	
	}

	/**
	*  Retorna o Hash da senha com um salt.
	*  @throws ColecaoException
	*  @return senha $senha
 	*/
	private function gerarHashDeSenhaComSaltEmMD5($senha)
	{

		$salt = "abchefghjkmnpqrstuvwxyz0123456789abchefghjkmnpqrstuvwxyz0123456789";
		$i = 0;

		while ($i <= 7)
		{
			$senha = $salt . $senha . $salt;
			$i++;
		}

		return md5($senha);
	}

	/**
	*  Valida Senha do usuário.
	*  @throws ColecaoException
 	*/
	private function validarSenha($senha)
	{

		if(!is_string($senha))
		{
			throw new ColecaoException( 'Valor inválido para senha.' );
		}

		$tamSenha = mb_strlen($senha);

		if($tamSenha <= Usuario::TAMANHO_MINIMO_SENHA)
		{
			throw new ColecaoException('O senha deve conter no minímo ' . Usuario::TAMANHO_MINIMO_SENHA . ' caracteres.');
		}
		if ($tamSenha >= Usuario::TAMANHO_MAXIMO_SENHA)
		{
			throw new ColecaoException('O senha deve conter no máximo ' . Usuario::TAMANHO_MAXIMO_SENHA . ' caracteres.');
		}
	}	

}	

?>