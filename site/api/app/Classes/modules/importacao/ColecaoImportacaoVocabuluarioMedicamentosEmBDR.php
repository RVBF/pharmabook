<?php
require_once '../../../../vendor/autoload.php';

/**
 *	Coleção de MedicamentoPrecificado em Banco de Dados Relacional.
 * @author Rafael Vinicius Barros Ferreira
 *	@version	1.0
 */

class ColecaoImportacaoVocabuluarioMedicamentosEmBDR implements ColecaoImportacao
{

	const TABELA = 'unidade_fornecimento';

	private $pdoW;

	private $registros;

	function __construct(PDOWrapper $pdoW)
	{
		$this->pdoW = $pdoW;
	}

	function lerArquivo()
	{
		try
		{

			$registros = [];
			$contadorRegistros = 0;
			for($contador = 14; $contador <= 58; $contador++)
			{
				$pasta = realpath(dirname('..\\')) . '\\..\\..\\..\\..\\..\\..\\bd\\vocabulario\\';;
				$dom = new DOMDocument();
				$dom->loadHTMLFile($pasta . $contador . ".html");
				$divs = $dom->getElementsByTagName('div');

				foreach ($divs as  $key => $div)
				{
					$valorAtual = utf8_encode($div->nodeValue);

					if(strcmp($valorAtual,'Conceito:') == 0)
					{
						$registros[$contadorRegistros]['nome'] = utf8_encode($divs->item($key -1)->nodeValue);

						$contador = $key + 1;

						$registros[$contadorRegistros]['descricao'] = '';

						while($contador > 0)
						{
							$divAtual =	isset($divs->item($contador)->nodeValue) ? utf8_encode($divs->item($contador)->nodeValue) : '';

							print_r(strcmp($divAtual, 'Abreviação:'));
							if(strcmp($divAtual, 'Abreviação:') == 0)
							{

								$registros[$contadorRegistros]['abreviacao'] = (isset($divs->item($contador + 1)->nodeValue) ) ? utf8_encode($divs->item($key + 1)->nodeValue) : '';
								break;
							}
							else
							{
								$registros[$contadorRegistros]['descricao'] .= (isset($divs->item($contador)->nodeValue)) ? utf8_encode($divs->item($contador)->nodeValue) : '';
							}

							$contador ++;
						}
					}
				}
			}


			$this->registros = $registros;
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

$colecao = DI::instance()->create('ColecaoImportacao');

$colecao->lerArquivo();

?>