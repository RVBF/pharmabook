<!-- 
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
			$sql = "SELECT id FROM laboratorio where nome = '$valores[2]'";
			$dados = mysql_query(utf8_encode( $sql));
			$row = mysql_fetch_array($dados);
			
			print_r('inserindo o medicamento'.utf8_encode($valores[6]).'\n');
			
			$laboratorioId = $row["id"];

			$result = mysql_query(
				utf8_encode("insert into medicamento 
					(
						ean,
						cnpj, 
						ggrem,
						registro, 
						nome_comercial, 
						classe_terapeutica, 
						laboratorio_id
					) VALUES 
					(
						'$valores[5]',
						'$valores[1]',
						'$valores[3]',
						'$valores[4]',
						'$valores[6]',
						'$valores[8]',
						'$laboratorioId'
					)"
				)
			);
		}	
	}
	// Só fechar agora o arquivo
	fclose($arquivo);
?>
 -->