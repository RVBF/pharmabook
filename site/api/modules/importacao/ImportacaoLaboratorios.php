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
				$laboratorio = ucwords( strtolower($valores[2]));
				$result = mysql_query(
				utf8_encode(
						"INSERT INTO laboratorio(nome) 
						SELECT '$laboratorio' 
						FROM DUAL WHERE 
						NOT EXISTS(SELECT nome 
						FROM laboratorio 
						WHERE nome = '$laboratorio')"
				));
				$ultimoID = "SELECT LAST_INSERT_ID()";
				$sql = mysql_query($ultimoID);
				$rowUltimoid = mysql_fetch_array($sql);
				// print_r($rowUltimoid);
				$laboratorioId = $rowUltimoid[0];
			}	
		}
		// Só fechar agora o arquivo
		fclose($arquivo);
	?>