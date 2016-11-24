<?php
// ----------------------------------------------------------------------------
// Variáveis do index.php são acessíveis neste arquivo.
// ----------------------------------------------------------------------------

use phputil\Session;

// Medicamentos Precificados
$app->get('/medicamentos-precificados', function() use ($app) 
{	
	$params = $app->request->get();
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraMedicamentoPrecificado($geradoraResposta, $params, $sessaoUsuario);
	$ctrl->todos();
});

$app->post('/medicamentos-precificados', function() use ($app)
{
	$params = $app->request->post();
	$geradoraResposta = new GeradoraRespostaComSlim($app );
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraMedicamentoPrecificado($geradoraResposta, $params, $sessaoUsuario);
	$ctrl->adicionar();
} );

$app->put('/medicamentos-precificados/:id', function($id) use ($app)
{
	$params = $app->request->put();
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$ctrl = new ControladoraMedicamentoPrecificado($geradoraResposta, $params);
	$ctrl->atualizar();
});

$app->delete('/medicamentos-precificados/:id', function($id) use ($app)
{
	$params = array('id' => $id);
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$ctrl = new ControladoraMedicamentoPrecificado($geradoraResposta, $params);
	$ctrl->remover();
});

//Medicamentos Precificados

// Medicamentos

$app->get('/medicamentos/:id', function($id) use ($app) 
{	
	$params = $app->request->get();
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraMedicamento($geradoraResposta, $params, $sessaoUsuario);
	$ctrl->comId($id);
});

$app->post('/medicamentos/pesquisar-medicamento', function() use ($app)
{
	$params = $app->request->post();
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraMedicamento($geradoraResposta, $params, $sessaoUsuario);
	$ctrl->autoCompleteMedicamento();
});

$app->post('/medicamentos/pesquisar-laboratorio', function() use ($app)
{
	$params = $app->request->post();
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraMedicamento($geradoraResposta, $params, $sessaoUsuario);
	$ctrl->autoCompleteLaboratorioDoMedicamento();
});

// Medicamentos

// Farmacia

$app->get('/farmacias', function() use ( $app ) {
	$params = $app->request->get();
	$geradoraResposta = new GeradoraRespostaComSlim( $app );
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraFarmacia( $geradoraResposta, $params, $sessaoUsuario);
	$ctrl->todos();
} );


$app->post('/farmacias', function() use ($app)
{
	$params = $app->request->post();
	$geradoraResposta = new GeradoraRespostaComSlim($app );
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraFarmacia($geradoraResposta, $params, $sessaoUsuario);
	$ctrl->adicionar();
} );


$app->put('/farmacias/:id', function($id) use ($app)
{
	$params = $app->request->put();
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraFarmacia($geradoraResposta, $params, $sessaoUsuario);	$ctrl->atualizar();
});

$app->delete('/farmacias/:id', function($id) use ($app)
{
	$params = array('id' => $id);
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraFarmacia($geradoraResposta, $params, $sessaoUsuario);	$ctrl->remover();
});

// Usuario

$app->post('/usuarios', function() use ($app)
{
	$params = $app->request->post();
	$geradoraResposta = new GeradoraRespostaComSlim($app );
	$ctrl = new ControladoraUsuario($geradoraResposta, $params);
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



// Laborátorios
$app->post('/laboratorio/pesquisar-laboratorio', function() use ($app)
{
	$params = $app->request->post();
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$ctrl = new ControladoraLaboratorio($geradoraResposta, $params);
	$ctrl->autoCompleteMedicamento();
});
// Laborátorios

//Login
$app->post('/login', function() use ($app)
{	
	$params = $app->request->post();
	$geradoraResposta = new GeradoraRespostaComSlim($app );
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraLogin($geradoraResposta, $params, $sessaoUsuario);
	$ctrl->logar();
} );

$app->delete('/logout', function() use ($app)
{
	$geradoraResposta = new GeradoraRespostaComSlim($app );
	$ctrl = new ControladoraLoginUsuario($geradoraResposta, null );
	$ctrl->sair();
} );

// Sessao
$app->post('/sessao/verificar-sessao', function() use ($app)
{
	$params = $app->request->post();
	$geradoraResposta = new GeradoraRespostaComSlim($app );
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraSessao($geradoraResposta, $params, $sessaoUsuario);
	$ctrl->estaAtiva();
});
//Sessao

// Trocar-senha
$app->put('/trocar-senha', function() use ($app)
{
	$params = $app->request->put();
	$geradoraResposta = new GeradoraRespostaComSlim($app );
	$ctrl = new ControladoraUsuario($geradoraResposta, $params );
	$ctrl->atualizarSenha();
} );

?>