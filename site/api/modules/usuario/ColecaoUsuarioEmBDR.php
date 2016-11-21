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
		$validarUsuario = new UsuarioValidate($obj);
		$validarUsuario->validarUsuario();

		$obj->setSenha($this->gerarHashDeSenhaComSaltEmMD5($obj->getSenha()));

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
		$validarUsuario = new UsuarioValidate($obj);
		$validarUsuario->validarUsuario();

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
}	

?>