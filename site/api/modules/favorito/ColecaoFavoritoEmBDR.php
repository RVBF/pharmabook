<?php

/**
 *	Coleção de Favorito em Banco de Dados Relacional.
 *
 *  @author	Rafael Vinicius Barros Ferreira
 *	@version	0.1
 */

class ColecaoFavoritoEmBDR implements ColecaoFavorito
{
	
	const TABELA = 'favorito';
	
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
		$this->validarFavorito($obj);
		
		try
		{			

			$sql  = 'SET foreign_key_checks = 0';
			$this->pdoW->execute($sql);

			$sql = 'INSERT INTO ' . self::TABELA . '(medicamento_precificado_id, usuario_id)
			VALUES (
				:medicamento_precificado_id,
				:usuario_id
			)';
		
			$this->pdoW->execute($sql, [
				'medicamento_precificado_id' => $obj->getMedicamentoPrecificado()->getId(), 
				'usuario_id' => $obj->getUsuario()->getId() 
			]);

			$obj->setId($this->pdoW->lastInsertId());

			$sql  = 'SET foreign_key_checks = 1';
			$this->pdoW->execute($sql);

		} 
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}

	function atualizar(&$obj){}

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
		return new Favorito(
			$row['id'],
			$row['medicamento_precificado_id'],
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

	function estaNosFavoritos($medicamentoPrecificadoId = 0)
	{
		try 
		{
			$sql = 'SELECT * from ' . self::TABELA . ' where medicamento_precificado_id = :medicamento_precificado_id and usuario_id = :usuario_id'; 

			return $this->pdoW->query($sql, [
				'medicamento_precificado_id' => $medicamentoPrecificadoId,				
				'usuario_id' => $this->getDono()->getId()				
			]);
		} 
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}		
	}

	private function validarFavorito($obj)
	{
		$sql = 'SELECT id from ' . self::TABELA . ' where usuario_id = :usuario_id and medicamento_precificado_id = :medicamento_precificado_id';
	
		$resultado = $this->pdoW->query($sql, [
			'usuario_id' => $obj->getUsuario()->getId(), 
			'medicamento_precificado_id' => $obj->getMedicamentoPrecificado()->getId() 
		]);

		if(count($resultado) == 1)
		{
			throw new Exception("O medicamento já foi adicionado aos favoritos.");
		}
	}
}	

?>