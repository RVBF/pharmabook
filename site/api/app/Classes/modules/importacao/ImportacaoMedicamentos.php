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
		$query =  " SET foreign_key_checks = 0; " ;
	 	utf8_encode($query);
	 	$resultado = mysql_query($query);
		$query = " truncate medicamento; " ;
	 	utf8_encode($query);
	 	$resultado = mysql_query($query);
		$query = " truncate laboratorio; " ;
	 	utf8_encode($query);
	 	$resultado = mysql_query($query);
		$query = " truncate principio_ativo; " ;
	 	utf8_encode($query);
	 	$resultado = mysql_query($query);
		$query = " truncate classe_terapeutica; " ;
	 	utf8_encode($query);
	 	$resultado = mysql_query($query);
		$query = " SET foreign_key_checks = 1; " ;
	 	utf8_encode($query);
	 	$resultado = mysql_query($query);

		$contador = 10;
		$laboratorios = $classes = $principios = $medicamentos = [];

		while($valores = fgetcsv ($arquivo, 25113, ";") and $contador >= 10 and $contador <= 25111)
		{
			$laboratorioNome=  utf8_encode(ucwords(strtolower(retirarCaracteresEspeciais($valores[2])))); //nome laborátorio
			$laboratorioCNPJ = utf8_encode(ucwords(strtolower(retirarCaracteresEspeciais($valores[1])))); //CBNPJ

			if(!in_array($laboratorioNome, $laboratorios))
			{
				$laboratorios[] = ['nome' => $laboratorioNome , 'cnpj'=> $laboratorioCNPJ];
			}
			// var_dump($laboratorios);

			$classeTerapeutica = utf8_encode(ucwords(strtolower(substr($valores[8], 7))));

			if(!in_array($classeTerapeutica, $classes))
			{
				$classes[] = $classeTerapeutica;
			}

			$principioAtivo = utf8_encode(ucwords(strtolower($valores[0])));

			if(!in_array($principioAtivo, $principios))
			{
				$principios[] = $principioAtivo;
			}

			$medicamentos[] = [
				'ean' => utf8_encode($valores[5]),
				'ggrem' => utf8_encode($valores[3]),
				'registro' => utf8_encode($valores[4]),
				'nomeComercial' => utf8_encode(ucwords( strtolower($valores[6]))),
				'composicao' => utf8_encode(ucwords( strtolower($valores[7]))),
				'precoFabrica' =>  str_replace(',', '.', $valores[9]),
				'precoMaximoConsumidor' =>  str_replace(',', '.', $valores[18]),
				'restricaoHospitalar' => utf8_encode(ucwords( strtolower($valores[27]))),
				'laboratorio' => $laboratorioNome,
				'classeTerapeutica' => $classeTerapeutica,
				'principioAtivo' => $principioAtivo
			];

			$contador ++;
		}

		$query = "insert into `laboratorio` (laboratorio.nome, laboratorio.cnpj) values ";
		foreach ($laboratorios as $key => $laboratorio)
		{
			$query .= (($key + 1) == count($laboratorios)) ? '("'. $laboratorio['nome'] . '", "' . $laboratorio['cnpj']. '");' :  '("'. $laboratorio['nome'] . '", "' . $laboratorio['cnpj'] . '"),';
		}

	 	utf8_encode($query);
	 	$resultado = mysql_query($query);

		$query = "insert into `principio_ativo` (principio_ativo.nome) values " . "('" . implode("'), ('", $principios) . "');";

	 	utf8_encode($query);
	 	$resultado = mysql_query($query);
		$query = "insert into `classe_terapeutica` (classe_terapeutica.nome) values " . "('" . implode("'), ('", $classes) . "');";
	 	utf8_encode($query);
	 	$resultado = mysql_query($query);

		foreach ($medicamentos as $medicamento)
		{
			$query =  " SET foreign_key_checks = 0; " ;
		 	utf8_encode($query);
		 	$resultado = mysql_query($query);

			$query = "select `id` from `laboratorio` where  laboratorio.nome = '".$medicamento['laboratorio'] ."';";
		 	utf8_encode($query);
		 	$resultado = mysql_query($query);
			$rowLaboratorio = mysql_fetch_row($resultado);
			$laboratorioId = $rowLaboratorio[0];

			$query = "select `id` from `classe_terapeutica` where  classe_terapeutica.nome = '".$medicamento['classeTerapeutica'] ."';";
		 	utf8_encode($query);
		 	$resultado = mysql_query($query);
			$rowclasse = mysql_fetch_row($resultado);
			$classeId = $rowclasse[0];

			$query = "select `id` from `principio_ativo` where  principio_ativo.nome = '".$medicamento['principioAtivo'] ."';";
		 	utf8_encode($query);
		 	$resultado = mysql_query($query);
			$rowprincipio = mysql_fetch_row($resultado);
			$principioId = $rowprincipio[0];

			$query ="insert into `medicamento` (medicamento.ean,medicamento.ggrem, medicamento.registro, medicamento.nome_comercial, medicamento.composicao, medicamento.preco_fabrica, medicamento.preco_maximo_consumidor, medicamento.restricao_hospitalar, medicamento.laboratorio_id, medicamento.classe_terapeutica_id, medicamento.principio_ativo_id) values  ('".$medicamento['ean']."', '".$medicamento['ggrem']."', '".$medicamento['registro']."', '".$medicamento['nomeComercial']."', '".$medicamento['composicao']."', '".$medicamento['precoFabrica']."', '".$medicamento['precoMaximoConsumidor']."', '".$medicamento['restricaoHospitalar']."',  '".$laboratorioId."', '".$classeId."', '".$principioId."');";

		 	utf8_encode($query);
		 	echo $query;
		 	$resultado = mysql_query($query);
			$query =  " SET foreign_key_checks = 1; " ;
		 	utf8_encode($query);
		 	$resultado = mysql_query($query);
		}

	}
	// Só fechar agora o arquivo
	fclose($arquivo);
?>
