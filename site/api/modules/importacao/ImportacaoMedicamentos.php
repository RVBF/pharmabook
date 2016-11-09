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
	}
	else
	{
		$query =  " SET foreign_key_checks = 0; " ;  
	 	$resultado = mysql_query(utf8_encode($query));
		$query = " truncate medicamento; " ;  
	 	$resultado = mysql_query(utf8_encode($query));
		$query = " truncate laboratorio; " ;  
	 	$resultado = mysql_query(utf8_encode($query));
		$query = " truncate principio_ativo; " ;  
	 	$resultado = mysql_query(utf8_encode($query));
		$query = " truncate classe_terapeutica; " ;  
	 	$resultado = mysql_query(utf8_encode($query));
		$query = " SET foreign_key_checks = 1; " ;
	 	$resultado = mysql_query(utf8_encode($query));

		$contador = 9;

		$laboratorios = $classes = $principios = $medicamentos = [];

		while($valores = fgetcsv ($arquivo, 25113, ";") and $contador >= 9 or $contador <= 25111)
		{
			$laboratorio = utf8_encode(ucwords(strtolower($valores[2])));

			if(!in_array($laboratorio, $laboratorios))
			{
				$laboratorios[] = $laboratorio;
			}
		
			$classeTerapeutica = utf8_encode(ucwords(strtolower($valores[8])));
			
			if(!in_array($classeTerapeutica, $classes))
			{
				$classes[] = $classeTerapeutica;
			}

			$principioAtivo = utf8_encode(ucwords(strtolower($valores[8])));
			
			if(!in_array($principioAtivo, $principios))
			{
				$principios[] = $principioAtivo;
			}			

			$medicamentos[] = [
				'ean' => utf8_encode($valores[5]),
				'cnpj' => utf8_encode($valores[1]),
				'ggrem' => utf8_encode($valores[3]),
				'registro' => utf8_encode($valores[4]),
				'nomeComercial' => utf8_encode(ucwords( strtolower($valores[6]))),
				'composicao' => utf8_encode(ucwords( strtolower($valores[7]))),
				'precoFabrica' =>  str_replace(',', '.', $valores[9]),
				'precoMaximoConsumidor' =>  str_replace(',', '.', $valores[18]),
				'restricaoHospitalar' => utf8_encode(ucwords( strtolower($valores[27]))),
				'laboratorio' => $laboratorio,
				'classeTerapeutica' => $classeTerapeutica,
				'principioAtivo' => $principioAtivo
			];

			$contador ++;
		}

		$query = "insert into `laboratorio` (`nome`) values " . "('" . implode("'), ('", $laboratorios) . "');";
		$resultado = mysql_query(utf8_encode($query));
		$query = "insert into `principio_ativo` (`nome`) values " . "('" . implode("'), ('", $principios) . "');";
		$resultado = mysql_query(utf8_encode($query));
		$query = "insert into `classe_terapeutica` (`nome`) values " . "('" . implode("'), ('", $classes) . "');";
		$resultado = mysql_query(utf8_encode($query));

		foreach ($medicamentos as $medicamento) 
		{
			$query = "select `id` from `laboratorio` where  nome = '".$medicamento['laboratorio'] ."';";
			$resultado = mysql_query(utf8_encode($query));
			$rowLaboratorio = mysql_fetch_row($resultado);
			$laboratorioId = $rowLaboratorio[0];

			$query = "select `id` from `classe_terapeutica` where  nome = '".$medicamento['classeTerapeutica'] ."';";
			$resultado = mysql_query(utf8_encode($query));
			$rowclasse = mysql_fetch_row($resultado);
			$classeId = $rowclasse[0];

			$query = "select `id` from `principio_ativo` where  nome = '".$medicamento['principioAtivo'] ."';";
			$resultado = mysql_query(utf8_encode($query));
			$rowprincipio = mysql_fetch_row($resultado);
			$principioId = $rowprincipio[0];

			$query ="insert into `medicamento` (`ean`, `cnpj`,`ggrem`, `registro`, `nome_comercial`, `composicao`, `preco_fabrica`, `preco_maximo_consumidor`, `restricao_hospitalar`, `laboratorio_id`, `classe_terapeutica_id`, `principio_ativo_id`) values  ('".$medicamento['ean']."', '".$medicamento['cnpj']."', '".$medicamento['ggrem']."', '".$medicamento['registro']."', '".$medicamento['nomeComercial']."', '".$medicamento['composicao']."', '".$medicamento['precoFabrica']."', '".$medicamento['precoMaximoConsumidor']."',  '".$medicamento['restricaoHospitalar']."', '".$laboratorioId."', '".$classeId."', '".$principioId."');";

			$resultado = mysql_query(utf8_encode($query));
		}
	}
	// Só fechar agora o arquivo
	fclose($arquivo);
?>
