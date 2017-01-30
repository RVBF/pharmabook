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
		try
		{
			$sql  = 'SET foreign_key_checks = 0';
			$this->pdoW->execute($sql);

			$sql = 'INSERT INTO ' . self::TABELA . ' (
				validade,
				quantidade,
				capacidade_recipiente,
				tipo_unidade,
				administracao,
				medicamento_forma,
				medicamento_id,
				usuario_id
			)
			VALUES (
				:validade,
				:quantidade,
				:capacidade_recipiente,
				:tipo_unidade,
				:administracao,
				:medicamento_forma,
				:medicamento_id,
				:usuario_id
			)';

			$this->pdoW->execute($sql, [
				'validade' => $obj->getValidade(),
				'quantidade' => $obj->getQuantidade(),
				'capacidade_recipiente' => $obj->getCapacidadeRecipiente(),
				'tipo_unidade' => $obj->getTipoUnidade(),
				'administracao' => $obj->getAdministracao(),
				'medicamento_forma' => $obj->getMedicamentoForma(),
				'medicamento_id' => $obj->getMedicamento()->getId(),
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
			}
		}
		catch(\Exception $e)
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
		$dataCriacao = new DataUtil($row['data_criacao']);
		$dataAtualizacao = new DataUtil($row['data_atualizacao']);
		$validade = new DataUtil($row['validade']);

		return new MedicamentoPessoal(
			$row['id'],
			$validade,
			$row['capacidade_recipiente'],
			$row['quantidade'],
			$row['administracao'],
			$row['tipo_unidade'],
			$row['medicamento_forma'],
			$row['usuario_id'],
			$row['medicamento_id'],
			$dataCriacao,
			$dataAtualizacao
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