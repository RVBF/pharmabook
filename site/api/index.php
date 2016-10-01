<?php
/**
 *  @author	Thiago Delgado Pinto
 */

define('REMOTE_IP',	'127.0.0.1' );
define('REMOTE_HOST',	'' );
define('DEBUG_MODE',	true );

require_once 'vendor/autoload.php';

// Realiza ajustes de zona, data e hora do servidor
date_default_timezone_set('America/Sao_Paulo' );

// Cria a aplicação Slim
$app = new \Slim\Slim();

// Realiza ajustes para modo de depuração
if (! DEBUG_MODE)
	{

	// Modifica o retorno default do servidor para 500,
	// pois ele retorna 200 mesmo com erro no PHP
	http_response_code(500 );

	// Desabilita a exibição de erros, por motivos de segurança
	ini_set('display_errors', 0 );

	// Desabilita a tela de diagnóstico do Slim
	$app->config('debug', false );
}

// Checagens de segurança HTTP
/*
$httpSecurity = new HttpSecurity($app );
$httpSecurity->allowCORS(REMOTE_IP, REMOTE_HOST );
$httpSecurity->preventClickJacking();
*/

// CORS
$app->map('/:x+', function($x ) use ($app)
	{
	$app->response->header('Access-Control-Allow-Methods', 'HEAD, GET, POST, PATCH, PUT, DELETE, OPTIONS' );
    $app->response->setStatus(200 );
} )->via('OPTIONS' );

// Seta erro como o status default, ao invés de sucesso!
$app->response->setStatus(400 );

// Checagens de segurança da sessão


$session = new phputil\Session();
$session->start();
/*$app->hook('slim.before.router', function() use ($app, $session)
	{
	// TO-DO
} );*/

// Definição das rotas
require_once 'routes.php';

// Execução
$app->run();
?>
