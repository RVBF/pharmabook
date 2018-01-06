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

			for($contadorArquivos = 14; $contadorArquivos <= 58; $contadorArquivos++)
			{
				$pasta = realpath(dirname('..\\')) . '\\..\\..\\..\\..\\..\\..\\bd\\vocabulario\\';;
				$dom = new DOMDocument();
				$dom->loadHTMLFile($pasta . $contadorArquivos . ".html");
				$divs = $dom->getElementsByTagName('div');

				foreach ($divs as  $key => $div)
				{
					$valorAtual = utf8_encode($div->nodeValue);

					if(strcmp($valorAtual,'Conceito:') == 0)
					{
						$registros[]['nome'] = utf8_encode($divs->item($key -1)->nodeValue);
						$contador = $key + 1;
						$indiceAtual = count($registros) - 1;

						$registros[$indiceAtual]['descricao'] = '';

						while($contador > 0)
						{
							$divAtual =	isset($divs->item($contador)->nodeValue) ? utf8_encode($divs->item($contador)->nodeValue) : '';

							if(strcmp($divAtual,'Conceito:') == 0)
							{
								if(isset($registros[$indiceAtual])) $registros[$indiceAtual]['abreviacao'] = (isset($divs->item($contador + 1)->nodeValue) ) ? utf8_encode($divs->item($key + 1)->nodeValue) : '';
								break;
							}
							else
							{
								if(isset($registros[$indiceAtual])) $registros[$indiceAtual]['descricao'] .= (isset($divs->item($contador)->nodeValue)) ? utf8_encode($divs->item($contador)->nodeValue) : '';
							}

							if($contador + 1 > $divs->length)
							{
								break;
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