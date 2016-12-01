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
	private $dono;

	function __construct(PDOWrapper $pdoW)
	{
		$this->pdoW = $pdoW;
	}

	function getDono()
	{
		return $this->dono;
	}

	function setDono(Usuario $usuario)
	{
		$this->dono = $usuario;
	}

	function adicionar(&$obj)
	{

		// $this->validar($obj);

		try
		{
			$sql = 'INSERT INTO ' . self::TABELA . '(	
				dose,
				descricao,
				administracao_tipo,
				periodicidade,
				tipo_unidade_dose,
				tipo_periodicidade,
				medicamento_pessoal_id,
				usuario_id
			) 
			VALUES (
				:dose,
				:descricao,
				:administracao_tipo,
				:periodicidade,
				:tipo_unidade_dose,
				:tipo_periodicidade,
				:medicamento_pessoal_id,
				:usuario_id
			)';

			$this->pdoW->execute($sql, [
				'dose' => $obj->getDose(),
				'descricao' => $obj->getDescricao(),
				'administracao_tipo' => $obj->getAdministracao(),
				'periodicidade' => $obj->getPeriodicidade(),
				'tipo_periodicidade' => $obj->getTipoPeriodicidade(),
				'tipo_unidade_dose' => $obj->getTipoUnidadeDose(),
				'medicamento_pessoal_id' => $obj->getMedicamentoPessoal()->getId(),
				'usuario_id' => $obj->getUsuario()->getId()
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
			$sql = 'SELECT * FROM '. self::TABELA . ' where usuario_id = ' . $this->getDono()->getId() . ' ' . $this->pdoW->makeLimitOffset($limite, $pulo);

			return $this->pdoW->queryObjects([$this, 'construirObjeto'], $sql);
		}
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}		
	}


	function construirObjeto(array $row)
	{
		return new Posologia(
			$row['id'],
			$row['dose'],
			$row['descricao'],
			$row['administracao_tipo'],
			$row['periodicidade'],
			$row['tipo_unidade_dose'],
			$row['tipo_periodicidade'],
			$row['medicamento_pessoal_id'],
			$row['usuario_id']		
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