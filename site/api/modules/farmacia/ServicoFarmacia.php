<?php

use phputil\Session;

/**
* Serviço de Farmacia
*
* @author	Rafael Vinicius barros ferreira
*/

class ServicoFarmacia {
	
	private $colecao;
	
	function __construct()
	{
		$this->colecao = DI::instance()->create('ColecaoFarmaciaEmBDR');	
	}
	
	// private function validar( Farmacia $obj ) {
		
	// 	if (! is_string( $obj->getNome() ) )
	// 	{
	// 		throw new ColecaoException( 'Dado inválido para nome.' );
	// 	}
	// 	$tamNome = mb_strlen( $obj->getNome() );
	// 	if ( $tamNome < Professor::TAM_MIN_NOME ) {
	// 		throw new ColecaoException( 'O nome deve ter pelo menos ' . Professor::TAM_MIN_NOME . ' caracteres.' );
	// 	}
	// 	if ( $tamNome > Professor::TAM_MAX_NOME ) {
	// 		throw new ColecaoException( 'O nome deve ter no máximo ' . Professor::TAM_MAX_NOME . ' caracteres.' );
	// 	}
	// 	// verifica se é email.
	// 	if ( ! filter_var($obj->getEmail(), FILTER_VALIDATE_EMAIL) ) {
	// 		throw new ColecaoException( 'Por favor, informe o email.' );
	// 	}
	// 	$tamEmail = mb_strlen( $obj->getEmail() );
	// 	if ( $tamEmail < Professor::TAM_MIN_EMAIL ) {
	// 		throw new ColecaoException( 'O email deve ter pelo menos ' . Professor::TAM_MIN_EMAIL . ' caracteres.' );
	// 	}
	// 	if ( $tamEmail > Professor::TAM_MAX_EMAIL ) {
	// 		throw new ColecaoException( 'O email deve ter no máximo ' . Professor::TAM_MAX_EMAIL . ' caracteres.' );
	// 	}
	// 	//verifica se é email do dominio cefet.
	// 	$dominio = explode( '@', $obj->getEmail() );
	// 	if( strcasecmp( $dominio[1], Professor::DOMINIO_CEFET ) != 0) {
	// 		throw new ColecaoException( 'Por favor, informe o email do domínio do cefet.' );
	// 	}
	// 	if ( ! is_numeric( $obj->getSiape() ) ) {
	// 		throw new ColecaoException( 'Por favor, informe a matrícula SIAPE.' );
	// 	}
	// 	$tamSiape = mb_strlen( $obj->getSiape() );
	// 	if ( $tamSiape < Professor::TAM_MIN_SIAPE ) {
	// 		throw new ColecaoException( 'A matrícula SIAPE deve ter pelo menos ' . Professor::TAM_MIN_SIAPE . ' caracteres.' );
	// 	}
	// 	if ( $tamSiape > Professor::TAM_MAX_SIAPE ) {
	// 		throw new ColecaoException( 'A matrícula SIAPE deve ter no máximo ' . Professor::TAM_MAX_SIAPE . ' caracteres.' );
	// 	}
	// 	$tamSenha = mb_strlen( $obj->getSenha() );
	// 	if ( $tamSenha < Professor::TAM_MIN_SENHA ) {
	// 		throw new ColecaoException( 'A senha deve ter pelo menos ' . Professor::TAM_MIN_SENHA . ' caracteres.' );
	// 	}
	// 	if ( $tamSenha > Professor::TAM_MAX_SENHA ) {
	// 		throw new ColecaoException( 'A senha deve ter no máximo ' . Professor::TAM_MAX_SENHA . ' caracteres.' );
	// 	}
	// 	//verifica se já existe uma siape com o mesmo valor no banco de dados.
	// 	$sql = 'SELECT siape FROM ' . self::TABELA . ' WHERE siape = :siape';
	// 		$siape = $this->pdoW->run( $sql, array( 
	// 			'siape' => $obj->getSiape()			
	// 			) 
	// 		);
	// 	if( $siape > 0 ){
	// 		throw new ColecaoException( 'A siape  ' . $obj->getSiape() . ' já está cadastrado.' );
	// 	}
	// 	//verifica se já existe um email com o mesmo valor no banco de dados.
	// 	$sql = 'SELECT  email FROM ' . self::TABELA . ' WHERE email = :email';
	// 		$email = $this->pdoW->run( $sql, array( 
	// 			'email' => $obj->getEmail()			
	// 			) 
	// 		);
		
	// 	if( $email > 0 ){
	// 		throw new ColecaoException( 'O email  ' . $obj->getEmail() . ' já está cadastrado.' );
	// 	}			
	// }
}

?>