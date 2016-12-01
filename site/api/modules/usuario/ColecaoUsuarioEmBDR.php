<?php

/**
 *	Coleção de Usuario em Banco de Dados Relacional.
 *
 *  @author		Rafael Vinicius Barros Ferreira
 *	@version	0.1
 */

class ColecaoUsuarioEmBDR implements ColecaoUsuario
{
	
	const TABELA = 'usuario';
	
	private $pdoW;
	private $usuarioValidade;
	
	function __construct(PDOWrapper $pdoW)
	{
		$this->pdoW = $pdoW;
	}
	
	/**
	 * @inheritDoc
	 */
	function adicionar(&$obj)
	{
		$this->validarUsuario($obj);

		$hash = new HashSenha($obj->getSenha());

		$obj->setSenha($hash->gerarHashDeSenhaComSaltEmMD5());

		try
		{
			$sql = 'INSERT INTO ' . self::TABELA . '(
				nome,
				sobrenome,
				email,
				login,
				senha,
				dataCriacao,
				dataAtualizacao
			)
			VALUES (
				:nome,
				:sobrenome,
				:email,
				:login,
				:senha,
				:dataCriacao,
				:dataAtualizacao
			)';


			$this->pdoW->execute($sql, [
				'nome' => $obj->getNome(), 
				'sobrenome' => $obj->getSobrenome(), 
				'email' => $obj->getEmail(),
				'login' => $obj->getLogin(),
				'senha' => $obj->getSenha(),
				'dataCriacao' => $obj->getDataCriacao(),
				'dataAtualizacao' => $obj->getDataAtualizacao()
			]);

			$obj->setId($this->pdoW->lastInsertId());
		} 
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}
	
	/**
	 * @inheritDoc
	 */
	function atualizar(&$obj)
	{
		$this->validarUsuario($obj);

		try
		{
			$sql = 'UPDATE ' . self::TABELA . ' SET 
			 	nome = :nome,
			 	email = :email, 
			 	login = :login, 
			 	dataCriacao = :dataCriacao,
			 	dataAtualizacao = :dataAtualizacao
			 	WHERE id = :id';

			$this->pdoW->execute($sql, [
				'nome' => $obj->getNome(), 
				'email' => $obj->getEmail(),
				'login' => $obj->getLogin(),
				'dataCriacao' => $obj->getdataCriacao(),
				'dataAtualizacao' => $obj->getDataAtualizacao(),
				'id' => $obj->getId()
			]);
		} 
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}		
	}
	
	/**
	 * @inheritDoc
	 */
	function remover($id)
	{
		try
		{
			return $this->pdoW->deleteWithId($id, self::TABELA);
		} catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}		
	}
	
	/**
	 * @inheritDoc
	 */
	function comId($id)
	{
		try
		{
			return $this->pdoW->objectWithId([$this, 'construirObjeto'], $id, self::TABELA);
		} catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}		
	}

	function comEmail($email)
	{		
		try 
		{
			$sql = 'SELECT * from '. self::TABELA . ' WHERE email = :email';

			return $this->pdoW->queryObjects( [$this, 'construirObjeto'],$sql, ['email' => $email]);
		}
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}	

	function comLogin($login)
	{
		try 
		{
			$sql = 'SELECT * from '. self::TABELA . ' WHERE login = :login';

			return $this->pdoW->queryObjects( [$this, 'construirObjeto'],$sql, ['login' => $login]);
		}
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}

	/**
	 * @inheritDoc
	 */
	function todos($limite = 0, $pulo = 0)
	{
		try
		{
			return $this->pdoW->allObjects([$this, 'construirObjeto'], self::TABELA);
		} catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}		
	}

	function construirObjeto(array $row)
	{
		return new Usuario(

			$row['id'],
			$row['nome'],
			$row['sobrenome'],
			$row['email'],
			$row['login'],
			$row['senha'],
			$row['dataCriacao'],
			$row['dataAtualizacao']
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
	private function validarUsuario($obj)
	{
		$this->validarNome($obj->getNome());
		$this->validarSobrenome($obj->getSobrenome());
		$this->validarEmail($obj->getEmail());
		$this->validarLogin($obj->getLogin());
		if($obj->getSenha() != '')
		{
			$this->validarSenha($obj->getSenha());	
		}

		if(count($this->comLogin($obj->getLogin())) > 0)
		{
			throw new ColecaoException( 'O login  ' . $obj->getLogin() . ' já está em uso por outro usuário no sistema.' );
		}
		
		if(count($this->comEmail($obj->getEmail())) > 0)
		{
			throw new ColecaoException( 'O email  ' . $obj->getEmail() . ' já está em uso por outro usuário no sistema.' );
		}			
	}

	/**
	*  Valida o nome do usuário, lançando uma exceção caso haja algo inválido.
	*  @throws ColecaoException
	*/
	private function validarNome($nome)
	{
		if(!is_string($nome))
		{
			throw new ColecaoException( 'Valor inválido para nome.' );
		}
		
		$tamNome = mb_strlen($nome);

		if($tamNome <= Usuario::TAMANHO_MINIMO_NOME)
		{
			throw new ColecaoException('O nome deve conter no minímo ' . Usuario::TAMANHO_MINIMO_NOME . ' caracteres.');
		}
		if ($tamNome >= Usuario::TAMANHO_MAXIMO_NOME)
		{
			throw new ColecaoException('O nome deve conter no máximo ' . Usuario::TAMANHO_MAXIMO_NOME . ' caracteres.');
		}
	}		

	/**
	*  Valida o Sobrenome do usuário, lançando uma exceção caso haja algo inválido.
	*  @throws ColecaoException
	*/
	private function validarSobrenome($sobrenome)
	{
		if(!is_string($sobrenome))
		{
			throw new ColecaoException( 'Valor inválido para sobrenome.' );
		}

		$tamSobrenome = mb_strlen($sobrenome);

		if($tamSobrenome <= Usuario::TAMANHO_MINIMO_SOBRENOME)
		{
			throw new ColecaoException('O sobrenome deve conter no minímo ' . Usuario::TAMANHO_MINIMO_SOBRENOME . ' caracteres.');
		}
		if ($tamSobrenome >= Usuario::TAMANHO_MAXIMO_SOBRENOME)
		{
			throw new ColecaoException('O sobrenome deve conter no máximo ' . Usuario::TAMANHO_MAXIMO_SOBRENOME . ' caracteres.');
		}
	}


	/**
	*  Valida o e-mail do usuário, lançando uma exceção caso haja algo inválido.
	*  @throws ColecaoException
	*/
	private function validarEmail($email)	
	{
		if(!$this->validarFormatoDeEmail($email))
		{
			throw new Exception("Formato de e-mail inválido, o e-mail deve possuir o seguinte formato (exemplo@domínio.extensão)");
		}

		if(!is_string($email))
		{
			throw new ColecaoException( 'Valor inválido para e-mail, o campo e-mail é um campo do tipo texto.' );
		}
	}


	/**
	*  Valida o login do usuário, lançando uma exceção caso haja algo inválido.
	*  @throws ColecaoException
	*/
	private function validarLogin($login)	
	{
		if(!$this->validarFormatoLogin($login))
		{
			throw new Exception("Formato de Login inválido.");
		}

		if(!is_string($login))
		{
			throw new ColecaoException( 'Valor inválido para login, o campo login é um campo do tipo texto.' );
		}

		$tamLogin = mb_strlen($login);

		if($tamLogin <= Usuario::TAMANHO_MINIMO_LOGIN)
		{
			throw new ColecaoException('O sobrenome deve conter no minímo ' . Usuario::TAMANHO_MINIMO_LOGIN . ' caracteres.');
		}
		if ($tamLogin >= Usuario::TAMANHO_MAXIMO_LOGIN)
		{
			throw new ColecaoException('O sobrenome deve conter no máximo ' . Usuario::TAMANHO_MAXIMO_LOGIN . ' caracteres.');
		}
	}

	/**
	*  Valida o senha do usuário, lançando uma exceção caso haja algo inválido.
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

	/**
	*  Valida o formato do e-mail do usuário, lançando uma exceção caso haja algo inválido.
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
	*  Valida formato do login do usuário, lançando uma exceção caso haja algo inválido.
	*  @throws ColecaoException
	*/
	private function validarFormatoLogin($email)
	{
		$formato = "[a-zA-Z0-9\. _-]+.";

		if (ereg($formato, $email))
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