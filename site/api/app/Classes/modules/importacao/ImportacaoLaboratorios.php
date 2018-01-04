<?php

	function retirarCaracteresEspeciais($cep)
	{
		$pontos = ["(", ")", '-'];
		$resultado = str_replace($pontos, "", $cep);

		return $resultado;
	}

	$conexao = mysql_connect("localhost", "root", "");

	mysql_select_db("pharmabook");

	mysql_set_charset('UTF8', $conexao);

	mysql_query("SET NAMES 'utf8'");

	$arquivo = fopen("xls_conformidade_gov_site_2016_06_20.csv", "r");

	if (!$arquivo)
	{
		echo ('<p>Arquivo não encontrado</p>');
	}
	else
	{
		$query = " truncate laboratorio; " ;
	 	utf8_encode($query);
	 	$resultado = mysql_query($query);
		$query =  " SET foreign_key_checks = 1; " ;


		$contador = 10;
		$laboratorios = [];

		while($valores = fgetcsv ($arquivo, 25113, ";") and $contador >= 10 and $contador <= 100)
		{
			$laboratorioNome=  utf8_encode(ucwords(strtolower(retirarCaracteresEspeciais($valores[2]))));
			$laboratorioCNPJ = utf8_encode(ucwords(strtolower(retirarCaracteresEspeciais($valores[1]))));
			if(!in_array($laboratorioNome, $laboratorios))
			{
				$laboratorios[] = ['nome' => $laboratorioNome , 'cnpj'=> $laboratorioCNPJ];
			}
		}

		$query = "insert into `laboratorio` (laboratorio.nome, laboratorio.cnpj) values ";
		foreach ($laboratorios as $key => $laboratorio)
		{
			$query .= (($key + 1) == count($laboratorios)) ? '("'. $laboratorio['nome'] . '", "' . $laboratorio['cnpj']. '");' :  '("'. $laboratorio['nome'] . '", "' . $laboratorio['cnpj'] . '"),';
		}
		utf8_encode($query);
	 	mysql_query($query);
	}
	// Só fechar agora o arquivo
	fclose($arquivo);
?>
