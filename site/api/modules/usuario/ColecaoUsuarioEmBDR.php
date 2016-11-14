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
		$this->validar($obj);

		try
		{
			$sql = 'UPDATE ' . self::TABELA . ' SET 
			 	nome = :nome,
			 	email = :email, 
			 	login = :login, 
			 	senha = :senha,
			 	dataCriacao = :dataCriacao,
			 	dataAtualizacao = :dataAtualizacao
			 	WHERE id = :id';

			$this->pdoW->execute($sql, [
				'nome' => $obj->getNome(), 
				'email' => $obj->getEmail(),
				'login' => $obj->getLogin(),
				'senha' => $obj->getSenha(),
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
	
	private function validarUsuario(Usuario $obj)
	{
		$this->validarNome($obj->getNome());
		$this->validarSobrenome($obj->getSobrenome());
		$this->validarEmail($obj->getEmail());
		$this->validarLogin($obj->getLogin());
		$this->validarSenha($obj->getSenha());

		//verifica se já existe uma login com o mesmo valor no banco de dados.
		$sql = 'SELECT login FROM ' . self::TABELA . ' WHERE login = :login';
		
		$login = $this->pdoW->run( $sql, [
			'login' => $obj->getLogin()			
			] 
		);

		if( $login > 0)
		{
			throw new ColecaoException( 'O login  ' . $obj->getLogin() . ' já está em uso por outro usuário no sistema.' );
		}
		//verifica se já existe um email com o mesmo valor no banco de dados.
		$sql = 'SELECT  email FROM ' . self::TABELA . ' WHERE email = :email';
		
		$email = $this->pdoW->run( $sql, [
			'email' => $obj->getEmail()			
			] 
		);
		
		if( $email > 0)
		{
			throw new ColecaoException( 'O email  ' . $obj->getEmail() . ' já está em uso por outro usuário no sistema.' );
		}			
	}

	private function validarNome($nome)
	{
		if(!is_string( $nome))
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

	private function validarSobrenome($sobrenome)
	{
		if(!is_string( $sobrenome))
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

	private function validarEmail($email)	
	{
		if(!$this->validarFormatoDeEmail($email))
		{
			throw new Exception("Formato de e-mail inválido, o e-mail deve possuir o seguinte formato (exaexemple@domínio.extensão)");
		}

		if(!is_string($email))
		{
			throw new ColecaoException( 'Valor inválido para e-mail, o campo e-mail é um campo do tipo texto.' );
		}
	}	

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