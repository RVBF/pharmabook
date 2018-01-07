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

	private $formasFarmaceuticas;

	function __construct(PDOWrapper $pdoW)
	{
		$this->pdoW = $pdoW;
	}

	function lerArquivo()
	{
		try
		{
			$formasFarmaceuticas = [];
			$contadorRegistrosTipos = 0;
			$posisaoInicial = null;
			$indiceAtual = 0;

			for($contadorArquivos = 14; $contadorArquivos <= 34; $contadorArquivos++)
			{
				$pasta = realpath(dirname('..\\')) . '\\..\\..\\..\\..\\..\\..\\bd\\vocabulario\\';;
				$dom = new DOMDocument();
				$dom->loadHTMLFile($pasta . $contadorArquivos . ".html");
				$divs = $dom->getElementsByTagName('div');
				$incompleta = false;

				foreach ($divs as  $key => $div)
				{
					$valorAtual = utf8_decode($div->nodeValue);

					if($posisaoInicial != null and $posisaoInicial > 0)
					{
						if(strcmp($valorAtual,'Abreviação:') == 0)
						{
							if(isset($formasFarmaceuticas[$posisaoInicial])) $formasFarmaceuticas[$posisaoInicial]['abreviacao'] = (isset($divs->item($contador + 1)->nodeValue) ) ? utf8_decode($divs->item($key + 1)->nodeValue) : '';
							for($posicaoDiv = $key; $key >= 0; $key--)
							{
								if(isset($formasFarmaceuticas[$posisaoInicial])) $formasFarmaceuticas[$posisaoInicial]['descricao'] .= (isset($divs->item($contador)->nodeValue)) ? utf8_decode($divs->item($contador)->nodeValue) : '';
							}
						}

						$posisaoInicial = null;
					}

					if(strcmp($valorAtual,'Conceito:') == 0)
					{
						$contadorRegistrosTipos ++;
						$formasFarmaceuticas[$indiceAtual]['nome'] = utf8_decode($divs->item($key -1)->nodeValue);
						$contador = $key + 1;
						$formasFarmaceuticas[$indiceAtual]['descricao'] = '';

						while($contador > 0)
						{
							$divAtual =	isset($divs->item($contador)->nodeValue) ? utf8_decode($divs->item($contador)->nodeValue) : '';

							if(strcmp($divAtual, 'Abreviação') == 0)
							{
								if(isset($formasFarmaceuticas[$indiceAtual])) $formasFarmaceuticas[$indiceAtual]['abreviacao'] = (isset($divs->item($contador + 1)->nodeValue) ) ? utf8_decode($divs->item($key + 1)->nodeValue) : '';
								break;
							}
							else
							{
								if(isset($formasFarmaceuticas[$indiceAtual])) $formasFarmaceuticas[$indiceAtual]['descricao'] .= (isset($divs->item($contador)->nodeValue)) ? utf8_decode($divs->item($contador)->nodeValue) : '';
							}

							if($contador + 1 > $divs->length)
							{
								$incompleta = true;
								$posisaoInicial = $indiceAtual;
								break;
							}

							$contador ++;
						}

						if($contadorArquivos <= 25)
						{
							$formasFarmaceuticas[$indiceAtual]['estado'] = FormaFarmaceutica::SOLIDO;
						}
						else if($contadorArquivos <= 32)
						{
							$formasFarmaceuticas[$indiceAtual]['estado'] = FormaFarmaceutica::LIQUIDO;
						}
						else if($contadorArquivos <= 34)
						{
							if($contadorRegistrosTipos == 0) $formasFarmaceuticas[$indiceAtual]['estado'] = FormaFarmaceutica::LIQUIDO;
							else if($contadorRegistrosTipos <= 5) $formasFarmaceuticas[$indiceAtual]['estado'] = FormaFarmaceutica::SEMI_SOLIDO;
							else if($contadorRegistrosTipos == 6) $formasFarmaceuticas[$indiceAtual]['estado'] = FormaFarmaceutica::GASOSO;
						}
					}

					$indiceAtual++;
				}

				if($contadorArquivos == 26 or $contadorArquivos == 33) $contadorRegistrosTipos = 0;
			}
			Debuger::printr($formasFarmaceuticas);

			$this->formasFarmaceuticas = $formasFarmaceuticas;
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