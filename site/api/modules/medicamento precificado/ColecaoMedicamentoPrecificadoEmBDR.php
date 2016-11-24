<?php

/**
 *	Coleção de MedicamentoPrecificado em Banco de Dados Relacional.
 *
 *  @author		Rafael Vinicius Barros Ferreira
 *	@version	0.1
 */

class ColecaoMedicamentoPrecificadoEmBDR implements ColecaoMedicamentoPrecificado
{
	
	const TABELA = 'medicamento_precificado';
	
	private $pdoW;
	
	function __construct(PDOWrapper $pdoW)
	{
		$this->pdoW = $pdoW;
	}

	function adicionar(&$obj)
	{

		$this->validarMedicamentoPrecificado($obj);

		try
		{
			$sql = 'INSERT INTO ' . self::TABELA . '(
				preco,
				farmacia_id,
				medicamento_id,
				usuario_id,
				dataCriacao,
				dataAtualizacao
			)
			VALUES (
				:preco,
				:farmacia_id,
				:medicamento_id,
				:usuario_id,
				:dataCriacao,
				:dataAtualizacao
			)';

			$this->pdoW->execute($sql, [
				'preco' => $obj->getPreco(),
				'farmacia_id' => $obj->getFarmacia()->getId(),
				'medicamento_id' => $obj->getMedicamento()->getId(),
				'usuario_id' => $obj->getUsuario()->getId(),
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
		$this->validarMedicamentoPrecificado($obj);
		
		try
		{
			$sql = 'UPDATE ' . self::TABELA . ' SET 
				preco = :preco,
				farmacia_id = :farmacia_id,
				medicamento_id = :medicamento_id,
				usuario_id = :usuario_id,
				dataAtualizacao = :dataAtualizacao
			 	WHERE id = :id';

			$this->pdoW->execute($sql, [
				'preco' => $obj->getPreco(),
				'farmacia_id' => $obj->getFarmacia()->getId(),
				'medicamento_id' => $obj->getMedicamento()->getId(),
				'usuario_id' => $obj->getUsuario()->getId(),
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
			$sql = 'SELECT * FROM '.self::TABELA. $this->pdoW->makeLimitOffset($limite, $pulo);
			return $this->pdoW->queryObjects([$this, 'construirObjeto'], $sql);
		}
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}		
	}

	function construirObjeto(array $row)
	{
		return new MedicamentoPrecificado(
			$row['id'],
			$row['preco'],
			$row['farmacia_id'],
			$row['medicamento_id'],
			$row['usuario_id'],
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

	private function validarMedicamentoPrecificado($obj)
	{
		if(!$this->validarMedicamentoAnvisa($obj->getMedicamento()))
		{
			throw new Exception("O medicamento seleciona não foi encontrado na base de dados, corrija os dados e tente novamente.");
		}

		if(!$this->validarFarmacia($obj->getFarmacia()))
		{
			throw new Exception("A farmácia seleciona não foi encontrado na base de dados, corrija os dados e tente novamente.");
		}		

		if(!$this->validarUsuario($obj->getUsuario()))
		{
			throw new Exception("Erro ao cadastrar medicamento precificado, o usuário que executou a ação não existe na base de dados.");
		}		

		if(!$this->validarPreco($obj->getPreco()))
		{
			throw new Exception("Formato inválido para preço, o preço deve ser um valor do tipo real.");
		}

		$sql = 'SELECT * from '. self::TABELA . ' WHERE farmacia_id = :farmacia_id and medicamento_id = :medicamento_id';

		$resultado = $this->pdoW->query($sql, [
			'farmacia_id' => $obj->getFarmacia()->getId(),
			'medicamento_id' => $obj->getMedicamento()->getId()
		]);

		if(count($resultado) > 0)
		{
			throw new Exception("Não foi possível cadastrar medicamento, pois ele já está precificado no sistema.");
		}

	}

	private function validarMedicamentoAnvisa($medicamento)
	{
		$sql = 'SELECT id from '. ColecaoMedicamentoEmBDR::TABELA . ' WHERE id = :id ';

		$resultado = $this->pdoW->query($sql,['id' => $medicamento->getId()]);

		return (count($resultado) == 1) ? true : false;
	}


	private function validarFarmacia($farmacia)
	{
		$sql = 'SELECT id from '. ColecaoFarmaciaEmBDR::TABELA . ' WHERE id = :id ';

		$resultado = $this->pdoW->query($sql,['id' => $farmacia->getId()]);


		return (count($resultado) == 1) ? true : false;
	}	

	private function validarUsuario($usuario)
	{
		$sql = 'SELECT id from '. ColecaoUsuarioEmBDR::TABELA . ' WHERE id = :id ';

		$resultado = $this->pdoW->query($sql,['id' => $usuario->getId()]);

		return (count($resultado) == 1) ? true : false;
	}

	private function validarPreco($preco)
	{
		// Debuger::printr($preco);
		return is_float($preco);
	}
}	

?>