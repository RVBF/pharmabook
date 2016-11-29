<?php

/**
 *	Coleção de Farmácia
 *
 *  @author		Rafael Vinicius Barros
 *  @version	0.1
 */

interface ColecaoFarmacia extends Colecao {

	function autoCompleteFarmacia($farmacia, $medicamento);
}

?>