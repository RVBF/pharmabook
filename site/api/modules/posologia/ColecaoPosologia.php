<?php

/**
 *	Coleção de Posologia
 *
 *  @author		Rafael Vinicius Barros
 *  @version	0.1
 */

interface ColecaoPosologia extends Colecao {

	function getTiposDePeriodicidade();

	function getTiposDeAdministracao();

	function getTiposDeUnidades();

	function comIdMedicamentoPessoal($id);
}

?>