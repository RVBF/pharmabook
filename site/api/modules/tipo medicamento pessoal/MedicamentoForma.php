<?php

/**
 *	MedicamentoForma
 *
 *  @author Rafael Vinicius Barros Ferreira
 *  @version	1.0
 */
abstract class MedicamentoForma extends Enum {

	// Formas de medicamento semi-sólida
	const POMADA = 'Pomada';
	const pasta = 'pasta';
	const CREME = 'Creme';
	const GEL = 'Gel';

	//Formas de medicamentos sólidas
	const COMPRIMIDOS = 'Comprimidos';
	const CAPSULAS = 'Cápsulas';
	const PO = 'Pó';

	//Formas de medicamentos sólidas
	const ORAIS = 'Orais';
	const TOPICAS = 'Tópicas';
	const PARENTERAIS = 'Parenterais';
	const SOLUCOES = 'Solucões';
	const EMULSOES = 'Emulsões';
	const SUSPENSOES = 'Suspensões';
}

?>