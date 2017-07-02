<?php
use phputil\TDateTime;
/**
 *	Coleção de Endereço em Banco de Dados Relacional.
 *
 *  @author		Rafael Vinicius Barros Ferreira
 *	@version	0.1
 */

class ColecaoEnderecoEmBDR implements ColecaoEndereco
{

	const TABELA = 'endereco';

	private $pdoW;

	function __construct(PDOWrapper $pdoW)
	{
		$this->pdoW = $pdoW;
	}

	function adicionar(&$obj)
	{
		if($this->validarEndereco($obj))
		{
			try
			{
				$sql = 'INSERT INTO ' . self::TABELA . '(
					cep,
					logradouro,
					bairro_id,
					tipo_logradouro_id
				) VALUES (
					:cep,
					:logradouro,
					:bairro,
					:tipo_logradouro_id
				)';

				$this->pdoW->execute($sql, [
					'cep' => $obj->getCep(),
					'logradouro' => $obj->getLogradouro(),
					'bairro' => $obj->getBairro()->getId(),
					'tipo_logradouro_id' => $obj->getTipoLogradouro()->getId()
				]);

				$obj->setId($this->pdoW->lastInsertId());
			}
			catch (\Exception $e)
			{
				throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
			}
		}
	}

	function atualizar(&$obj)
	{
		if($this->validarEndereco($obj))
		{
			try
			{
				$sql  = 'SET foreign_key_checks = 0';
				$this->pdoW->execute($sql);

				$sql = 'UPDATE ' . self::TABELA . ' SET
					cep := cep,
					logradouro :=logradouro,
					bairro_id := bairro,
					tipo_logradouro_id := tipo_logradouro_id
				WHERE id = :id';

				$this->pdoW->execute($sql, [
					'cep' => $this->retirarCaracteresEspeciais($obj->getCep()),
					'logradouro' => $obj->getLogradouro(),
					'bairro' => $obj->getBairro()->getId(),
					'tipo_logradouro_id' => $obj->getTipoLogradouro()->getId(),
					'id' => $obj->getId()
				]);

				$sql  = 'SET foreign_key_checks = 1';
				$this->pdoW->execute($sql);

			}
			catch (\Exception $e)
			{
				throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
			}
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
		$dataCriacao = new TDateTime($row['data_criacao']);
		$dataAtualizacao = new TDateTime($row['data_atualizacao']);

		return new Endereco(
			$row['id'],
			$row['cep'],
			$row['logradouro'],
			$row['bairro'],
			$row['tipo_logradouro_id'],
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

	public function comBairroECep( $cep, $bairroId)
	{
		try
		{
			$sql = 'SELECT *  FROM ' . self::TABELA .' as endereco join '. ColecaoBairroEmBDR::TABELA .' as bairro on endereco.bairro_id = bairro.id WHERE endereco.cep like "%'. $cep .'%" and bairro.id = :bairroId;';

			return  $this->pdoW->queryObjects([$this, 'construirObjeto'],$sql, ['bairroId'=>$bairroId]);
		}
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}

	public function comCep($cep)
	{
		try
		{
			$sql = 'SELECT *  FROM ' . self::TABELA .' as endereco WHERE endereco.cep like "%'. $cep .'%" ;';

			return  $this->pdoW->queryObjects([$this, 'construirObjeto'],$sql);
		}
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}

	/**
	*  Valida o endereco, lançando uma exceção caso haja algo inválido.
	*  @throws ColecaoException
	*/
	private function validarEndereco(&$obj)
	{
		$this->validarLogradouro($obj->getLogradouro());
		if($obj->getCep() != '') $this->validarCep($obj->getCep());

		$sql = 'select cep from ' . self::TABELA .' where cep like "%:cep%";';

		$resultado = $this->pdoW->execute($sql, ['cep' => $this->retirarCaracteresEspeciais($obj->getCep())]);

		if(!empty($resultado[0]))
		{
			$obj->setId($resultado['id']);
			return false;
		}

		else return true;
	}

	/**
	*  Valida o pais, lançando uma exceção caso haja algo inválido.
	*  @throws ColecaoException
	*/
	private function validarCep($cep)
	{
		if(!is_string($cep))
		{
			throw new ColecaoException('Valor inválido para cep.');
		}

		if (!eregi("^[0-9]{5}-[0-9]{3}$", $cep))
		{
			throw new Exception(" Cep inválido.");
		}
	}

	/**
	*  Remove os caracteres especiais do telefone.
	*  @throws ColecaoException
	*/

	private function retirarCaracteresEspeciais($cep)
	{
		$pontos = ["(", ")", '-'];
		$resultado = str_replace($pontos, "", $cep);

		return $resultado;
	}
}

?>