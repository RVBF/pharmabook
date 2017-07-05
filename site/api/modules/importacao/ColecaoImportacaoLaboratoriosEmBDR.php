<?php
require_once '../../vendor/autoload.php';

/**
 *	Coleção de MedicamentoPrecificado em Banco de Dados Relacional.
 *
 *  @author		Rafael Vinicius Barros Ferreira
 *	@version	0.1
 */

class ColecaoImportacaoMedicamentosEmBDR implements ColecaoImportacao
{

	const TABELA_LABORATORIO = 'laboratorio';
	const TABELA_MEDICAMENTO = 'medicamento';
	const TABELA_CLASSE_TERAPEUTICA = 'classe_terapeutica';
	const TABELA_PRINCIPIO_ATIVO = 'principio_ativo';

	private $pdoW;

	function __construct(PDOWrapper $pdoW)
	{
		$this->pdoW = $pdoW;
	}

	function importarMedicamentos()
	{
		try
		{
			$arquivo = fopen("xls_conformidade_gov_site_2016_06_20.csv", "r");
			if (!$arquivo)
			{
				throw new Exception("laboratorio não encontrado");
			}
			else
			{
				$contador = 10;

				while($valores = fgetcsv ($arquivo, 25111, ";") and $contador >= 10 and $contador <= 25111)
				{
					$principioAtivo = ucwords(strtolower($this->retirarCaracteresEspeciais($valores[0])));

					$laboratorioNome=  ucwords(strtolower($this->retirarCaracteresEspeciais($valores[2])));

					$classeTerapeutica = ucwords(strtolower(substr($valores[8], 7)));
					$sql  = 'SET foreign_key_checks = 0';
					$this->pdoW->execute($sql);

					$sql = 'insert into '. self::TABELA_MEDICAMENTO . ' (
						ean,
						ggrem,
						registro,
						nome_comercial,
						composicao,
						preco_fabrica,
						preco_maximo_consumidor,
						restricao_hospitalar,
						laboratorio_id,
						classe_terapeutica_id,
						principio_ativo_id) values (
						"'.$valores[5].'",
						"'.$valores[3].'",
						"'.$valores[4].'",
						"'.ucwords( strtolower($valores[6])).'",
						"'.ucwords( strtolower($valores[7])).'",
						"'. str_replace(',', '.', $valores[9]).'",
						"'. str_replace(',', '.', $valores[18]).'",
						"'.ucwords( strtolower($valores[27])).'",
						"'.$laboratorio[0]['id'].'",
						"'.$classeTerapeutica[0]['id'].'",
						"'.$principioAtivo[0]['id'] .'"
					)';

					if(!empty($laboratorio = $this->comNomeLaboratorioNome($laboratorioNome)) and !empty($classeTerapeutica = $this->comNomeClasseTerapeutica($classeTerapeutica)) and !empty($principioAtivo = $this->comNome($principioAtivo)))
					{
						printf($sql);
						$this->pdoW->execute($sql);
					}

				$sql  = 'SET foreign_key_checks = 1';
				$this->pdoW->execute($sql);
				}
			}
		}
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}

	function comNome($nome)
	{
		try
		{
			$sql = 'SELECT  id from '. self::TABELA_PRINCIPIO_ATIVO . ' where nome like "%'.$nome.'%"';
			$resultado  = $this->pdoW->query($sql);
			return $resultado;
		}
		catch(\Exception $e)
		{
		}
	}

	function comNomeLaboratorioNome($nome)
	{
		try
		{
			$sql = 'SELECT  id from '. self::TABELA_LABORATORIO . ' where nome like "%'.$nome.'%"';
			$resultado  = $this->pdoW->query($sql);

			return $resultado;
		}
		catch(\Exception $e)
		{
		}
	}

	function comNomeClasseTerapeutica($nome)
	{
		try
		{
			$sql = 'SELECT  id from '. self::TABELA_CLASSE_TERAPEUTICA . ' where nome like "%'.$nome.'%"';
			$resultado  = $this->pdoW->query($sql);
			return $resultado;
		}
		catch(\Exception $e)
		{
		}
	}

	function construirObjeto(array $row)
	{
		return new PrincipioAtivo(
			$row['id'],
			$row['nome']
		);
	}

	function construirObjetoLaboratorio(array $row)
	{
		return new laboratorio(
			$row['id'],
			$row['nome'],
			$row['cnpj']
		);
	}

	function construirObjetoClasse(array $row)
	{
		return new laboratorio(
			$row['id'],
			$row['nome']
		);
	}

	function retirarCaracteresEspeciais($cep)
	{
		$pontos = ["(", ")", '-'];
		$resultado = str_replace($pontos, "", $cep);

		return $resultado;
	}
}

$colecaoImportacaoLaboratorio = DI::instance()->create('ColecaoImportacao');

$colecaoImportacaoLaboratorio->importarMedicamentos();

?>