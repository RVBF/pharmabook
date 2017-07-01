<?php

/**
 *	Coleção de Laboratorio em Banco de Dados Relacional.
 *
 *  @author	Rafael Vinicius Barros Ferreira
 *	@version	0.1
 */

class ColecaoBairroEmBDR implements ColecaoBairro
{

	const TABELA = 'bairro';

	private $pdoW;

	function __construct(PDOWrapper $pdoW)
	{
		$this->pdoW = $pdoW;
	}

	function adicionar(&$obj)
	{
		try
		{
			$sql = 'INSERT INTO ' . self::TABELA . '(nome,cidade_id)
			VALUES (
				:nome,
				:cidade_id
			)';

			$this->pdoW->execute($sql, [
				'nome' => $obj->getNome(),
				'cidade_id' => $obj->getCidade()->getId()
			]);

			$obj->setId($this->pdoW->lastInsertId());
		}
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}

	function remover($id)
	{
		try
		{
			return $this->pdoW->deleteWithId($id, self::TABELA);
		}
		catch(\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}

	function atualizar(&$obj)
	{
		try
		{
			$sql = 'UPDATE ' . self::TABELA . ' SET
			 	nome = :nome,
			 	cidade_id = :cidade_id
			 	WHERE id = :id';

			$this->pdoW->execute($sql, [
				'nome' => $obj->getNome(),
				'cidade_id' => $obj->getCidade()->getId(),
				'id' => $obj->getId()
			]);
		}
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}

	function comId($id)
	{
		try
		{
			return $this->pdoW->objectWithId([$this, 'construirObjeto'], $id, self::TABELA);
		}
		catch(\Exception $e)
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
			return $this->pdoW->allObjects([$this, 'construirObjeto'], self::TABELA, $limite, $pulo);
		}
		catch(\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}

	function construirObjeto(array $row)
	{
		return new Bairro(
			$row['id'],
			$row['nome'],
			$row['cidade_id']
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