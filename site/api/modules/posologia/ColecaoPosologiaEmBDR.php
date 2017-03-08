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

	/**
	* 	Retorna  um  o usuário dono posologia em que está ocorrendo o procedimento
	*  @return $usuario
	*  @throws ColecaoException
	*/
	function getDono()
	{
		return $this->dono;
	}

	/**
	* 	Seta  um  o usuário dono da posologia em que está ocorrendo o procedimento
	*  @param $usuario
	*  @throws ColecaoException
	*/
	function setDono(Usuario $usuario)
	{
		$this->dono = $usuario;
	}

	/**
	* 	Valida e adiciona os dados do objeto no banco de dados.
	*  @param $id
	*	@return true or false
	*  @throws ColecaoException
	*/
	function adicionar(&$obj)
	{
		$this->validarPosologia($obj);
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
				'tipo_periodicidade' => TempoUnidade::getValor($obj->getTipoPeriodicidade()),
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

/**
	* 	Remove a posologia referente ao id passado
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

	/**
	* 	Atualiza uma posologia
	*  @param $obj
	*	@return $obj
	*  @throws ColecaoException
	*/
	function atualizar(&$obj)
	{
		try
		{
			$sql = 'UPDATE ' . self::TABELA . ' SET
				dose = :dose,
				descricao = :descricao,
				periodicidade = :periodicidade,
				tipo_periodicidade = :tipo_periodicidade
		 	WHERE id = :id';

		 	$parametros = [
				'dose' => $obj->getDose(),
				'descricao' => $obj->getDescricao(),
				'periodicidade' => $obj->getPeriodicidade(),
				'tipo_periodicidade' => TempoUnidade::getValor($obj->getTipoPeriodicidade()),
				'id' => $obj->getId()
			];

			$this->pdoW->execute($sql, $parametros);
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
		}catch(\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}

	/**
	*  Retorna a posologia de um determinado medicamento pessoal.
	*	@param $id
	*  @return array $obj
	*  @throws ColecaoException
	*/
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
	*  Retorna todos os medicamentos pessoais refente ao usuário logado.
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
	*  Cria um objeto de medicamento Pessoal.
	*  @throws ColecaoException
	*/
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

	/**
	*  Retorna a contagem de registro na base de dados.
	*  @throws ColecaoException
	*/
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

	/**
	*  Valida as posologias, lançando uma exceção caso haja algo inválido.
	*  @throws ColecaoException
	*/
	private function validarPosologia($obj)
	{
		if($obj->getId() == 0)
		{
			$this->validarMedicamentoPessoal($obj->getMedicamentoPessoal());
		}

		$this->validarTipoPeriodicidade($obj->getTipoPeriodicidade());
		$this->validarDose($obj->getDose());
		if($obj->getDescricao() != null) { $this->validarDescricao($obj->getDescricao()); }
	}

	/**
	*  Valida se o medicamento pessoa selecionado já está relacionado com algum cadastro de posologia, lançando uma exceção caso haja algo inválido.
	*  @throws ColecaoException
	*/
	private function validarMedicamentoPessoal($medicamentoPessoal)
	{
		try
		{
			$sql =  'select medicamento_pessoal_id from ' . self::TABELA . ' where medicamento_pessoal_id = :medicamento_pessoal_id';

			$resultado  = $this->pdoW->query($sql, ['medicamento_pessoal_id' => $medicamentoPessoal->getId()]);

			if(count($resultado) == 1){ throw new Exception("O medicamento selecionado já possui uma posologia cadastrada."); }
		}
		catch(\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}

	/**
	*  Valida o formato e se existe uma chave corresponte a periodicidade relacionada.
	*  @throws ColecaoException
	*/
	private function validarTipoPeriodicidade($tipoPeriodicidade)
	{
		try
		{
			if(!is_string($tipoPeriodicidade))
			{
				throw new Exception("Formato inválido para tipo de unidade.");
			}

			if(TempoUnidade::getChave($tipoPeriodicidade))
			{
				throw new Exception("O valor não corresponde a nenhuma undiade do sistema.");
			}
		}
		catch(\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}

	/**
	*  Valida o formato do parametro  e se a descrição está dentro dos limites permitidos no banco de dados.
	*  @throws ColecaoException
	*/
	private function validarDescricao($descricao)
	{
		try
		{
			if(!is_string($descricao))
			{
				throw new Exception("Formato inválido para a descricão.");
			}

			$tamDecricao = mb_strlen($descricao);

			if($tamDecricao > Posologia::TAMANHO_MAXIMO_DESCRICAO)
			{
				throw new ColecaoException('A descrição deve conter no máximo '. Posologia::TAMANHO_MAXIMO_DESCRICAO . ' caracteres.');
			}
		}
		catch(\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}

	/**
	*  Valida se a dose é maior que 0.
	*  @throws ColecaoException
	*/
	private function validarDose($dose)
	{
		try
		{
			if($dose <= 0)
			{
				throw new Exception("A dose deve ser maior que 0.");
			}
		}
		catch(\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}
}

?>