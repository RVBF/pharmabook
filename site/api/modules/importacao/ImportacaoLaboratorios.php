<!-- 	<?php

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
				print_r("inserindo valor '$valores[2]')");
				$result = mysql_query(
				utf8_encode(
						"INSERT INTO laboratorio(nome) 
						SELECT '$valores[2]' 
						FROM DUAL WHERE 
						NOT EXISTS(SELECT nome 
						FROM laboratorio 
						WHERE nome = '$valores[2]')"
				));				

			}	
		}
		// Só fechar agora o arquivo
		fclose($arquivo);
	?>
 -->