<?php

/**
 *	Coleção de Endereço em Banco de Dados Relacional.
 *
 *  @author		Rafael Vinicius Barros Ferreira
 *	@version	0.1
 */

class ColecaoEnderecoEmBDR implements ColecaoEndereco
{
	
	const TABELA = 'endereco';
	
	private $pdoW;
	
	function __construct(PDOWrapper $pdoW)
	{
		$this->pdoW = $pdoW;
	}

	function adicionar(&$obj)
	{
		try
		{
			$sql = 'INSERT INTO ' . self::TABELA . '(
				logradouro,
				bairro,
				cidade,
				estado,
				numero,
				complemento,
				referencia,
				dataCriacao,
				dataAtualizacao
			 )
			VALUES (
				:logradouro,
				:bairro,
				:cidade,
				:estado,
				:numero,
				:complemento,
				:referencia,
				:dataCriacao,
				:dataAtualizacao
			)';
								
			$this->pdoW->execute($sql, [
				'logradouro' => $obj->getLogradouro,
				'bairro' => $obj->getBairro,
				'cidade' => $obj->getCidade,
				'estado' => $obj->getEstado,
				'numero' => $obj->getNumero,
				'complemento' => $obj->getComplemento,
				'referencia' => $obj->getReferencia,
				'dataCriacao' => $obj->getDataCriacao,
				'dataAtualizacao' => $obj->getDataAtualizacao
			]);

			$obj->setId($this->pdoW->lastInsertId());
		} 
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}

	function atualizar(&$obj)
	{
		try
		{
			$sql = 'UPDATE' . self::TABELA . '
				logradouro = :logradouro,
				bairro = :bairro,
				cidade = :cidade,
				estado = :estado,
				numero = :numero,
				complemento = :complemento,
				referencia = :referencia,
				dataCriacao = :dataCriacao,
				dataAtualizacao = :dataAtualizacao
			 	WHERE id = :id' ;

			$this->pdoW->execute($sql, [
				'logradouro' => $obj->getLogradouro,
				'bairro' => $obj->getBairro,
				'cidade' => $obj->getCidade,
				'estado' => $obj->getEstado,
				'numero' => $obj->getNumero,
				'complemento' => $obj->getComplemento,
				'referencia' => $obj->getReferencia,
				'dataCriacao' => $obj->getDataCriacao,
				'dataAtualizacao' => $obj->getDataAtualizacao
				'id' => $obj->getId()
			]);
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
		return new Endereco(
			$row['id'],
			$row['logradouro'],
			$row['bairro'],
			$row['cidade'],
			$row['estado'],
			$row['numero'],
			$row['complemento'],
			$row['referencia'],
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