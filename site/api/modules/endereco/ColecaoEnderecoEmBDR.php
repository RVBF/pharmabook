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
				$sql = 'INSERT INTO ' . self::TABELA . ' (cep, logradouro, latitude, longitude, codigo_ibge, bairro_id) VALUES (:cep, :logradouro, :latitude, :longitude, :codigo_ibge, :bairro);';

				$this->pdoW->execute($sql, [
					'cep' => $obj->getCep(),
					'logradouro' => $obj->getLogradouro(),
					'latitude' => (float) $obj->getLatitude(),
					'longitude' => (float) $obj->getLongitude(),
					'codigo_ibge' => $obj->getCodigoIbge(),
					'bairro' => $obj->getBairro()->getId()
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
					bairro_id := bairro
				WHERE id = :id';

				$this->pdoW->execute($sql, [
					'cep' => $this->retirarCaracteresEspeciais($obj->getCep()),
					'logradouro' => $obj->getLogradouro(),
					'bairro' => $obj->getBairro()->getId(),
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
		return new Endereco(
			$row['id'],
			$row['cep'],
			$row['logradouro'],
			$row['bairro']
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

			return  $this->pdoW->queryObjects([$this, 'construirObjeto'], $sql, ['bairroId'=>$bairroId]);
		}
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}

	public function comLatitudeElongitude($latitude, $longitude)
	{
		try
		{
			$sql = 'SELECT * FROM ' . self::TABELA .' where (SELECT left(endereco.latitude, 6) from ' . self::TABELA . ')  like "%:latitude%" and  (SELECT left(endereco.longitude, 6) from ' . self::TABELA . ') like "%:longitude";';

			return  $this->pdoW->queryObjects([$this, 'construirObjeto'], $sql, ['latitude'=>substr($latitude, 0, 6), 'longitude'=>substr($longitude, 0, 6)]);
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
		if(!is_string($obj->getLogradouro()))
		{
			throw new ColecaoException('Valor inválido para bairro.');
		}

		if($obj->getCep() != '') $this->validarCep($obj->getCep());

		$sql = 'select * from ' . self::TABELA .' where cep like "%:cep%" and(SELECT left(endereco.latitude, 6) from ' . self::TABELA . ') like "%:latitude%" and (SELECT left(endereco.longitude, 6) from ' . self::TABELA . ') like "%:longitude%";';

		$enderecoResposta =  $this->pdoW->queryObjects([$this, 'construirObjeto'], $sql, [
			'cep'=> $obj->getCep(),
			'latitude'=>substr($obj->getLatitude(), 0, 6),
			'longitude'=>substr($obj->getLongitude(), 0, 6)
		]);

		if(!empty($enderecoResposta))
		{
			$enderecoResposta = $enderecoResposta[0];
			$obj->setId($enderecoResposta->getId());
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

		// if (!eregi("^[0-9]{5}-[0-9]{3}$", $cep))
		// {
		// 	throw new Exception(" Cep inválido.");
		// }
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