<?php

/**
 *	Coleção de Farmácia em Banco de Dados Relacional.
 *
 *  @author		Rafael Vinicius Barros Ferreira
 *	@version	0.1
 */

class ColecaoFarmaciaEmBDR implements ColecaoFarmacia
{
	
	const TABELA = 'farmacia';
	
	private $pdoW;
	
	function __construct(PDOWrapper $pdoW)
	{
		$this->pdoW = $pdoW;
	}

	function adicionar(&$obj)
	{
		try
		{
			$sql = 'INSERT INTO ' . self::TABELA . '( nome, telefone, endereco_id)
			VALUES (
				:nome,
				:telefone,
				:endereco_id
			)';

			$this->pdoW->execute($sql, [
				'nome' => $obj->getNome(),
				'telefone' => $obj->getTelefone(),
				'endereco_id' => $obj->getEndereco()->getId(),
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
				nome = :nome,
				telefone = :telefone,
				endereco = :endereco,
				dataCriacao = :dataCriacao,
				dataAtualizacao = :dataAtualizacao
			 	WHERE id = :id';

			$this->pdoW->execute($sql, [
				'nome' => $obj->getNome(),
				'telefone' => $obj->getTelefone(),
				'endereco' => $obj->getEndereco(),
				'dataCriacao' => $obj->getDataCriacao(),
				'dataAtualizacao' => $obj->getDataAtualizacao(),
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

	function construirObjeto(array $row)
	{
		return new Farmacia(
			$row['id'],
			$row['nome'],
			$row['telefone'],
			$row['endereco'],
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