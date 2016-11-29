<?php

/**
 *	Coleção de Posologia em Banco de Dados Relacional.
 *
 *  @author		Rafael Vinicius Barros Ferreira
 *	@version	0.1
 */

class ColecaoPosologiaEmBDR implements ColecaoPosologia
{
	
	const TABELA = 'posologia';
	
	private $pdoW;
	
	function __construct(PDOWrapper $pdoW)
	{
		$this->pdoW = $pdoW;
	}

	function adicionar(&$obj)
	{

		$this->validar($obj);

		try
		{
			$sql = 'INSERT INTO ' . self::TABELA . '(	
				dose,
				descricao,
				administracao,
				periodicidade,
				tipo_periodicidade,
				tipo_unidade_dose
			) 
			VALUES (
				:dose,
				:descricao,
				:administracao,
				:periodicidade,
				:tipo_periodicidade,
				:tipo_unidade_dose
			)';

			$this->pdoW->execute($sql, [
				'dose' => getDose(),
				'descricao' => getDescricao(),
				'administracao' => getAdministracao(),
				'periodicidade' => getPeriodicidade(),
				'tipo_periodicidade' => getTipoPeriodicidade(),
				'tipo_unidade_dose' => getTipoUnidadeDose()
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
		$this->validar($obj);

		try
		{
			$sql = 'UPDATE ' . self::TABELA . ' SET 
			 	dose = :dose,
				unidadeMedida = :unidadeMedida,
				descricao = :descricao,
				administracao = :administracao,
				periodicidade = :periodicidade,
				tipoPeriodicidade = :tipoPeriodicidade 
			 	WHERE id = :id';

			$this->pdoW->execute($sql, [
				'dose' => $obj->getDose(),
				'unidadeMedida' => $obj->getUnidadeMedida(),
				'descricao' => $obj->getDescricao(),
				'administracao' => $obj->getAdministracao(),
				'periodicidade' => $obj->getPeriodicidade(),
				'tipoPeriodicidade' => $obj->getTipoPeriodicidad(),
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
		return new Posologia(
			$row['id'],
			$row['dose'],
			$row['unidadeMedida'],
			$row['descricao'],
			$row['administracao'],
			$row['periodicidade'],
			$row['tipoPeriodicidade']
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

	function getTiposDePeriodicidade()
	{
		try 
		{
			return Posologia::retornarPeriodicidadesTipos();
		} 
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}		
	}

	function getTiposDeAdministracao()
	{
		try 
		{
			return Posologia::retornarTiposDeAdministracao();
		} 
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}		
	}

	function getTiposDeUnidades()
	{
		try 
		{
			return Posologia::retornarUnidadesTipos();
		} 
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}		
	}
}	

?>