<?php

/**
 *	Coleção de Laboratorio em Banco de Dados Relacional.
 *
 *  @author	Rafael Vinicius Barros Ferreira
 *	@version	0.1
 */

class ColecaoLaboratorioEmBDR implements ColecaoLaboratorio
{

	const TABELA = 'laboratorio';

	private $pdoW;

	function __construct(PDOWrapper $pdoW)
	{
		$this->pdoW = $pdoW;
	}

	function adicionar(&$obj)
	{
		try
		{
			$sql = 'INSERT INTO ' . self::TABELA . '(nome)
			VALUES (
				:nome,
			)';

			$this->pdoW->execute($sql, [
				'nome' => $obj->getNome()
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
		}catch(\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}

	function atualizar(&$obj)
	{
		try
		{
			$sql = 'UPDATE ' . self::TABELA . ' SET
			 	nome = :nome
			 	WHERE id = :id';

			$this->pdoW->execute($sql, [
				'nome' => $obj->getNome(),
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
		}catch(\Exception $e)
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

	function getLaboratoriosDoMedicamento($medicamento, $composicao)
	{
		try
		{
			$query = 'SELECT DISTINCT l.nome, l.id FROM '.self::TABELA.' as l';
			$query .= ' join '.ColecaoMedicamentoEmBDR::TABELA.' as m on l.id = m.laboratorio_id';
			$query .= ' where m.nome_comercial = :medicamento';
			$query .= '  and m.composicao = :composicao';
			$query .= ' ORDER BY l.nome ASC';

			return  $this->pdoW->queryObjects([$this, 'construirObjeto'], $query,  [
				'medicamento' => $medicamento,
				'composicao' => $composicao
			]);
		}
		catch(\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}

	function construirObjeto(array $row)
	{
		return new Laboratorio(
			$row['id'],
			$row['nome']
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