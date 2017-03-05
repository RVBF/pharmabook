<?php
use phputil\TDateTime;

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
		try
		{
			$sql = 'INSERT INTO ' . self::TABELA . '(
				dose,
				descricao,
				periodicidade,
				tipo_periodicidade,
				medicamento_pessoal_id,
				usuario_id
			)
			VALUES (
				:dose,
				:descricao,
				:periodicidade,
				:tipo_periodicidade,
				:medicamento_pessoal_id,
				:usuario_id
			)';

			$this->pdoW->execute($sql, [
				'dose' => $obj->getDose(),
				'descricao' => $obj->getDescricao(),
				'periodicidade' => $obj->getPeriodicidade(),
				'tipo_periodicidade' => $obj->getTipoPeriodicidade(),
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
			$sql  = 'SET foreign_key_checks = 0';
			$this->pdoW->execute($sql);
			$valor = $this->pdoW->deleteWithId($id, self::TABELA);
			$sql  = 'SET foreign_key_checks = 1';
			$this->pdoW->execute($sql);
			return $valor;
		}
		catch(\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}

	function atualizar(&$obj)
	{
		try
		{
			$sql = 'UPDATE ' . self::TABELA . ' SET
				dose,
				descricao,
				periodicidade,
				tipo_periodicidade
		 		WHERE id = :id';

		 	$parametros = [
				'dose' => $obj->getDose(),
				'descricao' => $obj->getDescricao(),
				'periodicidade' => $obj->getPeriodicidade(),
				'tipo_periodicidade' => $obj->getTipoPeriodicidade()
			];

			$this->pdoW->execute($sql, $parametros);
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

	function comIdMedicamentoPessoal($id)
	{
		try
		{
			$sql = "select id from ". self::TABELA . ' WHERE medicamento_pessoal_id = :medicamento_pessoal_id';
			return $this->pdoW->query($sql, ['medicamento_pessoal_id' => $id]);
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
		$dataCriacao = new TDateTime($row['data_criacao']);
		$dataAtualizacao = new TDateTime($row['data_atualizacao']);

		return new Posologia(
			$row['id'],
			$row['dose'],
			$row['descricao'],
			$row['periodicidade'],
			$row['tipo_periodicidade'],
			$row['medicamento_pessoal_id'],
			$this->getDono(),
			$dataCriacao,
			$dataAtualizacao
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