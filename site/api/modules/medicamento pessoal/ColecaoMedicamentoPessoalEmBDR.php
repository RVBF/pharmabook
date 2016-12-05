<?php

/**
 *	Coleção de MedicamentoPessoal em Banco de Dados Relacional.
 *
 *  @author		Rafael Vinicius Barros Ferreira
 *	@version	0.1
 */

class ColecaoMedicamentoPessoalEmBDR implements ColecaoMedicamentoPessoal
{
	
	const TABELA = 'medicamento_pessoal';
	
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
		// $this->validarMedicamentoPessoal($obj);

		try
		{
			$sql = 'INSERT INTO ' . self::TABELA . ' (
				validade,
				quantidade,
				medicamento_precificado_id,
				usuario_id,
				dataCriacao,
				dataAtualizacao,
				data_nova_compra
			)
			VALUES (
				:validade,
				:quantidade,
				:medicamento_precificado_id,
				:usuario_id,
				:dataCriacao,
				:dataAtualizacao,
				:data_nova_compra
			)';

			$this->pdoW->execute($sql, [
				'validade' => $obj->getValidade(),
				'quantidade' => $obj->getQuantidade(),
				'medicamento_precificado_id' => $obj->getMedicamentoPrecificado()->getId(),
				'usuario_id' => $obj->getUsuario()->getId(),
				'dataCriacao' => $obj->getDataCriacao(),
				'dataAtualizacao' => $obj->getDataAtualizacao(),
				'data_nova_compra' => $obj->getDataNovaCompra()
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
			$sql  = 'SET foreign_key_checks = 0';
			$this->pdoW->execute($sql);

			if($this->pdoW->deleteWithId($id, self::TABELA))
			{
				$sql  = 'SET foreign_key_checks = 1';
				$this->pdoW->execute($sql);
				return true;
			}
			else
			{
				return false;
			}		}catch(\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}		
	}
	
	function atualizar(&$obj)
	{
		// $this->validarMedicamentoPrecificado($obj);
		
		try
		{
			$sql = 'UPDATE ' . self::TABELA . ' SET 
				quantidade = :quantidade,
				dataAtualizacao = :dataAtualizacao 
			 	WHERE id = :id';

			$this->pdoW->execute($sql, [
				'quantidade' => $obj->getQuantidade(),
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
		$dataCriacao = new DataUtil($row['dataCriacao']);
		$dataAtualizacao = new DataUtil($row['dataAtualizacao']);
		$dataNovaCompra = new DataUtil($row['data_nova_compra']);
		$validade = new DataUtil($row['validade']);

		return new MedicamentoPessoal(
			$row['id'],
			$validade->formatarData(),
			$row['quantidade'],
			$row['medicamento_precificado_id'],
			$row['usuario_id'],
			$dataCriacao->formatarData(),
			$dataAtualizacao->formatarData(),
			$dataNovaCompra->formatarData()
		);
	}

	function contagem() 
	{
		try 
		{
			return $this->pdoW->countRows(self::TABELA, 'usuario_id', 'where usuario_id = :usuario_id', ['usuario_id' => $this->getDono()->getId()]);
		} 
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}		
	}

	private function validarMedicamentoPessoal($obj)
	{
		if(!$this->validarMedicamentoPrecificado($obj->getMedicamentoPrecificado()))
		{
			throw new Exception("O medicamento selecionado não foi encontrado na base de dados, corrija os dados e tente novamente.");
		}

		if(!$this->validarUsuario($obj->getUsuario()))
		{
			throw new Exception("Erro ao cadastrar medicamento pessoal, o usuário que executou a ação não existe na base de dados.");
		}		

		$sql = 'SELECT * from '. self::TABELA . ' WHERE medicamento_precificado_id = :medicamento_precificado_id and id <>'. $obj->getId();

		$resultado = $this->pdoW->query($sql, [
			'medicamento_precificado_id' => $obj->getMedicamentoPrecificado()->getId()
		]);

		if(count($resultado) > 0)
		{
			throw new Exception("Erro, o medicamento já cadastrado no estoque.");
		}
	}

	private function validarMedicamentoPrecificado($medicamentoPrecificado)
	{
		$sql = 'SELECT id from '. ColecaoMedicamentoPrecificadoEmBDR::TABELA . ' WHERE id = :id ';

		$resultado = $this->pdoW->query($sql,['id' => $medicamentoPrecificado->getId()]);

		return (count($resultado) == 1) ? true : false;
	}


	private function validarUsuario($usuario)
	{
		$sql = 'SELECT id from '. ColecaoUsuarioEmBDR::TABELA . ' WHERE id = :id ';

		$resultado = $this->pdoW->query($sql,['id' => $usuario->getId()]);

		return (count($resultado) == 1) ? true : false;
	}
}	

?>