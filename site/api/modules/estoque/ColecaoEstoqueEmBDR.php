<?php

/**
 *	Coleção de Estoque em Banco de Dados Relacional.
 *
 *  @author		Rafael Vinicius Barros Ferreira
 *	@version	0.1
 */

class ColecaoEstoqueEmBDR implements ColecaoEstoque
{
	
	const TABELA = 'estoque_pessoal';
	
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
		$this->validarEstoque($obj);

		try
		{
			$sql = 'INSERT INTO ' . self::TABELA . '(usuario_id) VALUES ( :usuario_id)';

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

	private function validarEstoque($obj)
	{
		//verifica se já existe uma usuário com o mesmo valor no banco de dados.
		$sql = 'SELECT * FROM ' . ColecaoUsuarioEmBDR::TABELA . ' WHERE id = :usuarioId';
		
		$usuario = $this->pdoW->run( $sql, [
			'usuarioId' => $obj->getUsuario()->getId()			
			] 
		);

		if($usuario == 0)
		{
			throw new ColecaoException( 'Erro ao criar estoque.' );
		}		
	}
}	

?>