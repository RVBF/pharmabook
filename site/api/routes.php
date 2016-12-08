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

$app->post('/medicamentos-precificados/pesquisar-medicamentoPrecificado', function() use ($app)
{
	$params = $app->request->post();
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraMedicamentoPrecificado($geradoraResposta, $params, $sessaoUsuario);
	$ctrl->autoCompleteMedicamentoPrecificado();
});

$app->post('/medicamentos-precificados/buscar-medicamentoPrecificado', function() use ($app)
{
	$params = $app->request->post();
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraMedicamentoPrecificado($geradoraResposta, $params, $sessaoUsuario);
	$ctrl->getMedicamentosPrecificados();
});

// Início das rotas para posologias
$app->get('/posologias', function() use ($app) 
{
	$params = $app->request->get();
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraPosologia($geradoraResposta, $params, $sessaoUsuario);
	$ctrl->todos();
});

$app->post('/posologias', function() use ($app)
{
	$params = $app->request->post();
	$geradoraResposta = new GeradoraRespostaComSlim($app );
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraPosologia($geradoraResposta, $params, $sessaoUsuario);
	$ctrl->adicionar();
} );

$app->get('/posologias/tipos-periodicidades', function() use ($app) 
{
	$params = $app->request->get();
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraPosologia($geradoraResposta, $params, $sessaoUsuario);
	$ctrl->getTiposDePeriodicidade();
});

$app->get('/posologias/tipos-administracoes', function() use ($app) 
{	$params = $app->request->get();
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraPosologia($geradoraResposta, $params, $sessaoUsuario);
	$ctrl->getTiposDeAdministracao();
});

$app->get('/posologias/tipos-unidades', function() use ($app) 
{
	$params = $app->request->get();
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraPosologia($geradoraResposta, $params, $sessaoUsuario);
	$ctrl->getTiposDeUnidades();
});

$app->put('/posologias/:id', function($id) use ($app)
{
	$params = $app->request->put();
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraPosologia($geradoraResposta, $params, $sessaoUsuario);
	$ctrl->atualizar();
});

$app->get('/posologias/:id', function($id) use ($app)
{
	$params = $app->request->put();
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraPosologia($geradoraResposta, $params, $sessaoUsuario);
	$ctrl->comId($id);
});

$app->delete('/posologias/:id', function($id) use ($app)
{
	$params = array('id' => $id);
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraPosologia($geradoraResposta, $params, $sessaoUsuario);
	$ctrl->remover();
});
// Fim das rotas para posologias

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

$app->put('/medicamentos-pessoais', function() use ($app)
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

$app->post('/farmacias/pesquisar-farmacias', function() use ($app)
{
	$params = $app->request->post();
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraFarmacia($geradoraResposta, $params, $sessaoUsuario);
	$ctrl->autoCompleteFarmacia();
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

$app->get('/usuarios/get-usuario-sessao', function() use ($app)
{
	$params = $app->request->post();
	$geradoraResposta = new GeradoraRespostaComSlim($app );
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraUsuario($geradoraResposta, $params,$sessaoUsuario);
	$ctrl->getUsuarioSessao();
} );


$app->put('/usuarios/:id', function($id) use ($app)
{
	$params = $app->request->put();
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraUsuario($geradoraResposta, $params, $sessaoUsuario);
	$ctrl->atualizar();
});

$app->post('/usuarios/nova-senha', function() use ($app)
{
	$params = $app->request->post();
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraUsuario($geradoraResposta, $params, $sessaoUsuario);
	$ctrl->novaSenha();
});

$app->delete('/usuarios/:id', function($id) use ($app)
{
	$params = array('id' => $id);
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraUsuario($geradoraResposta, $params, $sessaoUsuario);
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
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$geradoraResposta = new GeradoraRespostaComSlim($app );
	$ctrl = new ControladoraLogin($geradoraResposta, null, $sessaoUsuario );
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

//Inicio Rotas para Favoritos
$app->get('/favorito', function() use ($app) 
{
	$params = $app->request->get();
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraFavorito($geradoraResposta, $params, $sessaoUsuario);
	$ctrl->todos();
});

$app->post('/favorito', function() use ($app)
{
	$params = $app->request->post();
	$geradoraResposta = new GeradoraRespostaComSlim($app );
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraFavorito($geradoraResposta, $params, $sessaoUsuario);
	$ctrl->adicionar();
} );

$app->post('/favorito/esta-nos-favoritos', function() use ($app)
{
	$params = $app->request->post();
	$geradoraResposta = new GeradoraRespostaComSlim($app );
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraFavorito($geradoraResposta, $params, $sessaoUsuario);
	$ctrl->estaNosFavoritos();
} );

$app->put('/favorito', function() use ($app)
{
	$params = $app->request->put();
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraFavorito($geradoraResposta, $params, $sessaoUsuario);
	$ctrl->atualizar();
});

$app->delete('/favorito/:id', function($id) use ($app)
{
	$params = array('id' => $id);
	$geradoraResposta = new GeradoraRespostaComSlim($app);
	$session = new Session();
	$sessaoUsuario = new Sessao($session);
	$ctrl = new ControladoraFavorito($geradoraResposta, $params, $sessaoUsuario);
	$ctrl->remover();
});

?>