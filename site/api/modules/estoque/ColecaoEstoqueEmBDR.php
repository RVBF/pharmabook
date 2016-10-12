<?php

/**
 *	Coleção de Estoque em Banco de Dados Relacional.
 *
 *  @author		Rafael Vinicius Barros Ferreira
 *	@version	0.1
 */

class ColecaoEstoqueEmBDR implements ColecaoUsuario
{
	
	const TABELA = 'estoque';
	
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
		try
		{
			$sql = 'INSERT INTO ' . self::TABELA . '(
				usuario_id
			)
			VALUES (
				:usuario_id
			)';

			$today = date('y-m-d H:i:s');     // 05-16-18, 10-03-01, 1631 1618 6 Satpm01

			$this->pdoW->execute($sql, [
				'usuario_id' => $obj->getUsuario()->getId() 
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
			 	usuario_id = :usuario_id
			 	WHERE id = :id';

			$this->pdoW->execute($sql, [
				'usuario_id' => $obj->getUsuario()->getId(),
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
			$row['usuario']
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