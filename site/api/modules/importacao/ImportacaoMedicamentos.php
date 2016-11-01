<
<?php

	$conexao = mysql_connect("localhost", "root", "");

	mysql_select_db("farmabook");

	mysql_set_charset('UTF8', $conexao);

	mysql_query("SET NAMES 'utf8'");

	$arquivo = fopen("xls_conformidade_gov_site_2016_06_20.csv", "r");

	if (!$arquivo)
	{
		echo ('<p>Arquivo não encontrado</p>');
	}else
	{
	
		while ($valores = fgetcsv ($arquivo, 25113, ";")) 
		{
			$classeTerapeutica = utf8_encode(ucwords(strtolower($valores[8])));
			print_r($classeTerapeutica);
			$sqlClaseTerapeutica = "SELECT id FROM classe_terapeutica where nome = '$classeTerapeutica'";
			$dadosClasse = mysql_query(utf8_encode($sqlClaseTerapeutica ));
			$rowClasse = mysql_fetch_array($dadosClasse);
			$classeId = $rowClasse["id"];

			$laboratorio = utf8_encode(ucwords(strtolower($valores[2])));
			$sqlLaboratorio = "SELECT id FROM laboratorio where nome = '$laboratorio'";
			$dadosLaboratorio = mysql_query(utf8_encode($sqlLaboratorio));
			$rowLaboratorio = mysql_fetch_array($dadosLaboratorio);
			$laboratorioId = $rowLaboratorio["id"];
			
			$principioAtivo = utf8_encode(ucwords(strtolower($valores[0])));
			$sqlPrincipioAtivo = "SELECT id FROM principio_ativo where nome = '$principioAtivo'";
			$dadosPrincipioAtivo = mysql_query(utf8_encode($sqlPrincipioAtivo ));
			$rowPrincipioAtivo = mysql_fetch_array($dadosPrincipioAtivo);
			$idPrincipioAtivo = $rowPrincipioAtivo["id"];
			
			$ean = ucwords( strtolower($valores[5]));
			$cnpj = ucwords( strtolower($valores[1]));
			$ggrem = ucwords( strtolower($valores[3]));
			$registro = ucwords( strtolower($valores[4]));
			$nomeComercial = ucwords( strtolower($valores[6]));
			$composicao = ucwords( strtolower($valores[7]));
			$result = mysql_query(
				utf8_encode("insert into medicamento 
					(
						ean,
						cnpj, 
						ggrem,
						registro, 
						nome_comercial, 
						composicao,
						laboratorio_id, 
						classe_terapeutica_id, 
						principio_ativo_id
					) VALUES 
					(
						'$ean',
						'$cnpj',
						'$registro',
						'$nomeComercial',
						'$composicao',
						'$laboratorioId',
						'$classeId',
						'$idPrincipioAtivo'
					)"
				)
			);
		}	
	}
	// Só fechar agora o arquivo
	fclose($arquivo);
?>
