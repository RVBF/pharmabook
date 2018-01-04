<?php
use phputil\TDateTime;
/**
 *	Coleção de NotificacaoMedicamentoPessoal em Banco de Dados Relacional.
 *
 *  @author		Rafael Vinicius Barros Ferreira
 *	@version	0.1
 */

class ColecaoNotificacaoMedicamentoPessoalEmBDR implements ColecaoNotificaoMedicamentoPessoal
{
	const TABELA = 'notificacao_medicamento_pessoal';

	private $pdoW;
	private $dono;

	function __construct(PDOWrapper $pdoW)
	{
		$this->pdoW = $pdoW;
	}

	/**
	* 	Retorna  um  o usuário responsável pelo medicamento pessoal em que está ocorrendo o procedimento
	*  @return $usuario
	*  @throws ColecaoException
	*/
	function getDono()
	{
		return $this->dono;
	}

	/**
	* 	Seta o medicamento pessoal que está notiicandoem que está ocorrendo o procedimento
	*  @param $usuario
	*  @throws ColecaoException
	*/
	function setDono(MedicamentoPessoal $medicamentoPessoal)
	{
		$this->dono = $medicamentoPessoal;
	}

	/**
	* 	Valida e adiciona os dados do objeto no banco de dados.
	*  @param $id
	*	@return true or false
	*  @throws ColecaoException
	*/
	function adicionar(&$obj)
	{

		try
		{
			$sql = 'INSERT INTO ' . self::TABELA . ' (
				mensagem,
				data,
				medicamento_pessoal_id
			)
			VALUES (
				mensagem,
				data,
				medicamento_pessoal_id
			)';

			$data = new DataUtil($obj->getData());

			$this->pdoW->execute($sql, [
				'data' => $data->formatarDataParaBanco(),
				'mensagem' => $obj->getMensagem(),
				'medicamento_pessoal_id' => $obj->getMedicamentoPessoal()->getId()
 			]);

			$obj->setId($this->pdoW->lastInsertId());
		}
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}

	/**
	* 	Remove a notificação referente ao id passado
	*  @param $id
	*	@return true or false
	*  @throws ColecaoException
	*/
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

	/**
	* 	Atualiza uma notificação
	*  @param $obj
	*	@return $obj
	*  @throws ColecaoException
	*/
	function atualizar(&$obj)
	{
		$this->validarMedicamentoPessoal($obj);
		try
		{
			$sql = 'UPDATE ' . self::TABELA . ' SET
				mensagem,
				data
			 	WHERE id = :id';
			$validade = new DataUtil($obj->getValidade());
			$this->pdoW->execute($sql, [
				'data' => $data->formatarDataParaBanco(),
				'mensagem' => $obj->getMensagem(),
				'id' => $obj->getId()
			]);
		}
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}

	/**
	* 	Retorna o objeto corresponde ao id passado
	*  @param $id
	*	@return $obj
	*  @throws ColecaoException
	*/
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
	*  Retorna todos as notificações de um derterminado usuário logado.
	*	@param $limite, $pulo
	*  @return array $obj
	*  @throws ColecaoException
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

	/**
	*  Cria um objeto do tipo NotificaçãoMedicamentoPessoal.
	*  @throws ColecaoException
	*/
	function construirObjeto(array $row)
	{
		$dataCriacao = new TDateTime($row['data_criacao']);
		$dataAtualizacao = new TDateTime($row['data_atualizacao']);

		return new MedicamentoPessoal(
			$row['id'],
			$row['mensagem'],
			$row['data'],
			$row['medicamentoPessoal'],
			$dataCriacao,
			$dataAtualizacao
		);
	}

	/**
	*  Retorna a contagem de registro na base de dados.
	*  @throws ColecaoException
	*/
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
}
?>