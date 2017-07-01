<?php 
	// $token = '8cfd3192acdb84f2abd5ac5b7e2af680';
	// $url = 'http://www.cepaberto.com/api/v2/cities.json?estado=AM';
	// $ch = curl_init();
	// curl_setopt($ch, CURLOPT_URL, $url);
	// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Token token="' . $token . '"'));
	// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	// $output = curl_exec($ch);
	// echo  $output;

	$conexao = mysql_connect("localhost", "root", "");

	mysql_select_db("pharmabook");

	mysql_set_charset('UTF8', $conexao);

	mysql_query("SET NAMES 'utf8'");

	$arquivo = fopen("states.csv", "r");

	if (!$arquivo)
	{
		echo ('<p>Arquivo não encontrado</p>');
	}
	else
	{
		$query =  " SET foreign_key_checks = 0; " ;
	 	utf8_encode($query);
	 	$resultado = mysql_query($query);
		$query = " truncate estado; " ;
	 	utf8_encode($query);
	 	$resultado = mysql_query($query);

		$contador = 0;

		$query = 'insert into `estado`  (`id`, `nome`, `pais_id`) values ';
		while($valores = fgetcsv ($arquivo, 25113, ",") and $contador >= 0 or $contador <= 26)
		{	
			$query .= "(" . utf8_encode($valores[0]) .", '" . utf8_encode($valores[1]) ."', " . 1 .')';

			if($contador != 26) $query .= ",";
			$contador ++;
		}

		$query .= ';';
		echo $query;

	 	utf8_encode($query);
	 	mysql_query($query);

		$query = " SET foreign_key_checks = 1; " ;
	 	utf8_encode($query);
	 	$resultado = mysql_query($query);
	}
	// Só fechar agora o arquivo
	fclose($arquivo);
?>