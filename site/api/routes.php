<?php
// ----------------------------------------------------------------------------
// Variáveis do index.php são acessíveis neste arquivo.
// ----------------------------------------------------------------------------

use phputil\Session;

$app->get('/test', function()
{
	echo '{ "nome" : "Bob" }';
});


// Usuario

$app->get('/usuarios', function() use ($app) 
{	
	$params = $app->request->get();
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$ctrl = new ControladoraUsuario($geradoraResposta, $params);
	$ctrl->todos();
});

$app->post('/usuarios', function() use ($app)
	{
	$params = $app->request->post();
	$geradoraResposta = new GeradoraRespostaComSlim($app );
	$ctrl = new ControladoraUsuario($geradoraResposta, $params );
	$ctrl->adicionar();
} );

$app->post('/usuarios/:id', function($idCurso) use ($app)
{
	$params = $app->request->post();
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$ctrl = new ControladoraUsuario($geradoraResposta, $params);
	$obj = (array) $ctrl->adicionar();
	$valores = array_values($obj);
	$chaves = array('id', 'nome', 'siape', 'email', 'senha', 'ativo', 'confirmado', 'administrador', 'root');
	if(count($valores) == count($chaves)){
		$usuario = array_combine($chaves, $valores);
		$session = new Session();
		$params = array('id' => $idCurso, 'idusuarioCadastrado' => $usuario['id']);
		$ctrl = new ControladoraCurso($geradoraResposta , $params, $session);
		$ctrl->vincular();
	}
});

$app->put('/usuarios/:id', function($id) use ($app)
{
	$params = $app->request->put();
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$ctrl = new ControladoraUsuario($geradoraResposta, $params);
	$ctrl->atualizar();
});

$app->delete('/usuarios/:id', function($id) use ($app)
{
	$params = array('id' => $id);
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$ctrl = new ControladoraUsuario($geradoraResposta, $params);
	$ctrl->remover();
});

// Medicamento	
$app->get('/medicamentos', function() use ($app) 
{	
	$params = $app->request->get();
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$ctrl = new ControladoraMedicamento($geradoraResposta, $params);
	$ctrl->todos();
});

$app->post('/medicamentos/:term', function($term) use ($app)
{
	$params = ['valor' => $term];
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$ctrl = new ControladoraMedicamento($geradoraResposta, $params);
	$ctrl->pesquisarMedicamentos();
});

//Login
$app->post('/login', function() use ($app)
	{
	$params = $app->request->post();
	$geradoraResposta = new GeradoraRespostaComSlim($app );
	$ctrl = new ControladoraUsuario($geradoraResposta, $params );
	$ctrl->logar();
} );

$app->delete('/logout', function() use ($app)
	{
	$geradoraResposta = new GeradoraRespostaComSlim($app );
	$ctrl = new ControladoraUsuario($geradoraResposta, null );
	$ctrl->sair();
} );

// Trocar-senha
$app->put('/trocar-senha', function() use ($app)
	{
	$params = $app->request->put();
	$geradoraResposta = new GeradoraRespostaComSlim($app );
	$ctrl = new ControladoraUsuario($geradoraResposta, $params );
	$ctrl->atualizarSenha();
} );


?>