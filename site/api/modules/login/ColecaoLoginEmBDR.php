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

	private function comEmail($email)
	{
		try 
		{
			$sql = 'SELECT email as identificador, senha from '. self::TABELA . ' WHERE email = :email';

			return $this->pdoW->queryObjects([ $this, 'construirObjeto' ], $sql, [
				'email' => $email
			]);
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
			$sql = 'SELECT login as identificador, senha from '. self::TABELA . ' WHERE login = :login';

			return $this->pdoW->queryObjects([ $this, 'construirObjeto' ], $sql, [
				'login' => $login
			]);
		}
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}

	function logar(&$obj)
	{
		try
		{
			if($this->validarFormatoDeEmail($obj->getIdentificador()))
			{
				$login = $this->comEmail($obj->getIdentificador());

				if(count($login))
				{
					if($login->getSenha() == $obj->getSenha())
					{
						Debuger::printr($this->sessao);
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
					if($login[0]->getSenha() === $obj->getSenha())
					{
						// $this->sessao->setName( $login[0]->getIdentificador() ); // (optional) "PHPSESSID" session cookie key becomes "myapp"
						// $this->sessao->setCookieParams( $lastOneDay = 60 * 60 * 24 ); // (optional) cookie will last one day
						$this->sessao->status();					
					}
				}
				else
				{
					throw new Exception("O login inserido não corresponde a nenhuma conta cadastrada no sistema.");
				}
			}
			
		} catch (Exception $e) 
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
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

	/**
	*  Valida o usuário, lançando uma exceção caso haja algo inválido.
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
}	

?>