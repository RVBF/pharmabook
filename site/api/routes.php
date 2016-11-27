<?php
// ----------------------------------------------------------------------------
// Variáveis do index.php são acessíveis neste arquivo.
// ----------------------------------------------------------------------------

use phputil\Session;

// Início das rotas para Medicamentos Precificados
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
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraMedicamentoPrecificado($geradoraResposta, $params, $sessaoUsuario);
	$ctrl->atualizar();
});

$app->delete('/medicamentos-precificados/:id', function($id) use ($app)
{
	$params = array('id' => $id);
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraMedicamentoPrecificado($geradoraResposta, $params, $sessaoUsuario);
	$ctrl->remover();
});
// Fim das rotas para Medicamentos Precificados

// Início das rotas para Medicamentos Pessoal
$app->get('/medicamentos-pessoais', function() use ($app) 
{
	$params = $app->request->get();
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraMedicamentoPessoal($geradoraResposta, $params, $sessaoUsuario);
	$ctrl->todos();
});

$app->post('/medicamentos-pessoais', function() use ($app)
{
	$params = $app->request->post();
	$geradoraResposta = new GeradoraRespostaComSlim($app );
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraMedicamentoPessoal($geradoraResposta, $params, $sessaoUsuario);
	$ctrl->adicionar();
} );

$app->put('/medicamentos-pessoais/:id', function($id) use ($app)
{
	$params = $app->request->put();
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraMedicamentoPessoal($geradoraResposta, $params, $sessaoUsuario);
	$ctrl->atualizar();
});

$app->delete('/medicamentos-pessoais/:id', function($id) use ($app)
{
	$params = array('id' => $id);
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraMedicamentoPessoal($geradoraResposta, $params, $sessaoUsuario);
	$ctrl->remover();
});
// Fim das rotas para Medicamentos Pessoal

// Início das rotas para Medicamentos 
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

$app->post('/medicamentos/buscar-medicamento', function() use ($app)
{
	$params = $app->request->post();
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraMedicamento($geradoraResposta, $params, $sessaoUsuario);
	$ctrl->getMedicamentoDoSistema();
});
// Fim das rotas para Medicamentos 

// Início das rotas para farmacias 
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
	$ctrl = new ControladoraFarmacia($geradoraResposta, $params, $sessaoUsuario);	
	$ctrl->atualizar();
});

$app->delete('/farmacias/:id', function($id) use ($app)
{
	$params = array('id' => $id);
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraFarmacia($geradoraResposta, $params, $sessaoUsuario);	$ctrl->remover();
});
// Fim das rotas para farmacias 


// Início das rotas para usuários
$app->post('/usuarios', function() use ($app)
{
	$params = $app->request->post();
	$geradoraResposta = new GeradoraRespostaComSlim($app );
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraUsuario($geradoraResposta, $params,$sessaoUsuario);
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
// Fim das rotas para usuários

// Início das rotas para Laborátorios
$app->get('/laboratorios/:id', function($id) use ($app)
{
	$params = $app->request->get();
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraLaboratorio($geradoraResposta, $params, $sessaoUsuario);
	$ctrl->comId($id);
});

$app->post('/laboratorios/pesquisar-laboratorios', function() use ($app)
{
	$params = $app->request->post();
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraLaboratorio($geradoraResposta, $params, $sessaoUsuario);
	$ctrl->autoCompleteLaboratorio();
});
// Fim das rotas para usuários

// Início das rotas para login
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
// Fim das rotas para login

// Início das rotas para sessão
$app->post('/sessao/verificar-sessao', function() use ($app)
{
	$params = $app->request->post();
	$geradoraResposta = new GeradoraRespostaComSlim($app );
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraSessao($geradoraResposta, $params, $sessaoUsuario);
	$ctrl->estaAtiva();
});
// Fim das rotas para sessão

// // Início das rotas para sessão
// $app->put('/trocar-senha', function() use ($app)
// {
// 	$params = $app->request->put();
// 	$geradoraResposta = new GeradoraRespostaComSlim($app );
// 	$ctrl = new ControladoraUsuario($geradoraResposta, $params );
// 	$ctrl->atualizarSenha();
// } );

?>