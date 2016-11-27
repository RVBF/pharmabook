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
		$this->validarMedicamentoPessoal($obj);

		try
		{
			$sql = 'INSERT INTO ' . self::TABELA . '(
				validade,
				quantidade,
				data_nova_compra,
				medicamento_precificado_id,
				posologia_id,
				usuario_id,
				dataCriacao,
				dataAtualizacao
			)
			VALUES (
				:validade,
				:quantidade,
				:data_nova_compra,
				:medicamento_precificado_id,
				:posologia_id,
				:usuario_id,
				:dataCriacao,
				:dataAtualizacao
			)';

			$this->pdoW->execute($sql, [
				'validade' => $obj->getValidade(),
				'quantidade' => $obj->getQuantidade(),
				'data_nova_compra' => $obj->getDataNovaCompra(),
				'medicamento_precificado_id' => $obj->getMedicamentoPrecificado()->getId(),
				'posologia_id' => $obj->getPosologia()->getId(),
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
		$this->validarMedicamentoPessoal($obj);
		
		try
		{
			$sql = 'UPDATE ' . self::TABELA . ' SET 
				validade = :validade,
				quantidade = :quantidade,
				data_nova_compra = :data_nova_compra,
				medicamento_precificado_id = :medicamento_precificado_id,
				posologia_id = :posologia_id,
				usuario_id = :usuario_id,
				dataCriacao = :dataCriacao,
				dataAtualizacao = :dataAtualizacao 
			 	WHERE id = :id';

			$this->pdoW->execute($sql, [
				'validade' => $obj->getValidade(),
				'quantidade' => $obj->getQuantidade(),
				'data_nova_compra' => $obj->getDataNovaCompra(),
				'medicamento_precificado_id' => $obj->getMedicamentoPrecificado()->getId(),
				'posologia_id' => $obj->getPosologia()->getId(),
				'usuario_id' => $obj->getUsuario()->getId(),
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
			$sql = 'SELECT * FROM '.self::TABELA. ' where id = ' . $this->getDono()->getId() . ' ' . $this->pdoW->makeLimitOffset($limite, $pulo);

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

		return new MedicamentoPessoal(
			$row['id'],
			$row['validade'],
			$row['quantidade'],
			$row['dataNovaCompra'],
			$row['medicamentoPrecificado'],
			$row['posologia'],
			$row['usuario'],
			$dataCriacao->formatarData(),
			$dataAtualizacao->formatarData()
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

		if(!$this->validarPosologia($obj->getPosologia()))
		{
			throw new Exception("A farmácia selecionada não foi encontrado na base de dados, corrija os dados e tente novamente.");
		}		

		if(!$this->validarUsuario($obj->getUsuario()))
		{
			throw new Exception("Erro ao cadastrar medicamento pessoal, o usuário que executou a ação não existe na base de dados.");
		}		

		if(!$this->validarDatas($obj->getPreco()))
		{
			throw new Exception("Formato inválido para preço, o preço deve ser um valor do tipo real.");
		}

		$sql = 'SELECT * from '. self::TABELA . ' WHERE posologia_id = :posologia_id and medicamento_precificado_id = :medicamento_precificado_id and id <>'. $obj->getId();

		$resultado = $this->pdoW->query($sql, [
			'posologia_id' => $obj->getPosologia()->getId(),
			'medicamento_precificado_id' => $obj->getMedicamentoPrecificado()->getId()
		]);

		if(count($resultado) > 0)
		{
			throw new Exception("Medicamento já cadastrado no estoque.");
		}
	}

	private function validarMedicamentoPrecificado($medicamentoPrecificado)
	{
		$sql = 'SELECT id from '. ColecaoMedicamentoPrecificadoEmBDR::TABELA . ' WHERE id = :id ';

		$resultado = $this->pdoW->query($sql,['id' => $medicamentoPrecificado->getId()]);

		return (count($resultado) == 1) ? true : false;
	}

	private function validarPosologia($posologia)
	{
		$sql = 'SELECT id from '. ColecaoPosologiaEmBDR::TABELA . ' WHERE id = :id ';

		$resultado = $this->pdoW->query($sql,['id' => $posologia->getId()]);

		return (count($resultado) == 1) ? true : false;
	}	

	private function validarUsuario($usuario)
	{
		$sql = 'SELECT id from '. ColecaoUsuarioEmBDR::TABELA . ' WHERE id = :id ';

		$resultado = $this->pdoW->query($sql,['id' => $usuario->getId()]);

		return (count($resultado) == 1) ? true : false;
	}

	private function validarDatas($obj)
	{
		if($obj->getDataCriacao() === DataUtil::retornarDataAtual())
		{
			throw new Exception("Data inválida.");
		}		

		if($obj->getDataAtualizacao() === DataUtil::retornarDataAtual())
		{
			throw new Exception("Data inválida.");
		}
	}
}	

?>