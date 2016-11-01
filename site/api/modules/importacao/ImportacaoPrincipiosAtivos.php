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
				$value = ucwords( strtolower($valores[0]));
				print_r($value);
				$result = mysql_query(
				utf8_encode(
						"INSERT INTO principio_ativo(nome) 
						SELECT '$value' 
						FROM DUAL WHERE 
						NOT EXISTS(SELECT nome 
						FROM principio_ativo 
						WHERE nome = '$value')"
				));				

			}	
		}
		// Só fechar agora o arquivo
		fclose($arquivo);
	?>