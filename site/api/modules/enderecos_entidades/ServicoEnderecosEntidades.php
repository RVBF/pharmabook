<?php

use phputil\Session;

/**
* Serviço de Endereco
*
* @author	Rafael Vinizcius barros ferreira
*/

class ServicoEnderecosEntidades {

	private $colecao;

	function __construct()
	{
		$this->colecao = DI::instance()->create('ColecaoEnderecosEmBDR');
	}

	
	public function adicionar(&$obj)
	{
		$this->colecao->adicionar($obj);
	}


	public function atualizar(&$obj)
	{
		$this->colecao->atualizar($obj);
	}



}

?>