<?php

use phputil\Session;

/**
* Serviço de Endereco
*
* @author	Rafael Vinicius barros ferreira
*/

class ServicoEndereco {

	private $colecao;

	function __construct()
	{
		$this->colecao = DI::instance()->create('ColecaoEnderecoEmBDR');
	}

	private function consultarCepOnline($cep)
	{
		$reg = simplexml_load_file("http://cep.republicavirtual.com.br/web_cep.php?formato=xml&cep=" . $cep);

		$dados['sucesso'] = (string) $reg->resultado;
		$dados['rua']     = (string) $reg->tipo_logradouro . ' ' . $reg->logradouro;
		$dados['bairro']  = (string) $reg->bairro;
		$dados['cidade']  = (string) $reg->cidade;
		$dados['estado']  = (string) $reg->uf;

		return $dados;
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