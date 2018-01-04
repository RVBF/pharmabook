<?php
// ----------------------------------------------------------------------------
// Variáveis do index.php são acessíveis neste arquivo.
// ----------------------------------------------------------------------------

// Início das rotas para Medicamentos Precificados
$app->get('/medicamentos-precificados', 'ControladoraMedicamentoPrecificado:todos');
$app->post('/medicamentos-precificados', 'ControladoraMedicamentoPrecificado:adicionar');
$app->put('/medicamentos-precificados', 'ControladoraMedicamentoPrecificado:atualizar');
$app->get('/medicamentos-precificados/{id}', 'ControladoraMedicamentoPrecificado:comId');
$app->delete('/medicamentos-precificados/{id}', 'ControladoraMedicamentoPrecificado:remover');
$app->post('/medicamentos-precificados/pesquisar-medicamentoPrecificado', 'ControladoraMedicamentoPrecificado:autoCompleteMedicamentoPrecificado');
$app->post('/medicamentos-precificados/buscar-medicamentoPrecificado', 'ControladoraMedicamentoPrecificado:getMedicamentosPrecificados');
// Fim das rotas para Medicamentos Precificados

// // Início das rotas para Medicamentos Pessoal
$app->get('/medicamentos-pessoais', 'ControladoraMedicamentoPessoal:todos');
$app->get('/medicamentos-pessoais/administracoes', 'ControladoraMedicamentoPessoal:getAdministracoes');
$app->get('/medicamentos-pessoais/medicamentos-formas', 'ControladoraMedicamentoPessoal:getMedicamentosFormas');
$app->get('/medicamentos-pessoais/unidades-solidas', 'ControladoraMedicamentoPessoal:getUnidadesSolidas');
$app->get('/medicamentos-pessoais/unidades-inteiras', 'ControladoraMedicamentoPessoal:getUnidadesInteiras');
$app->post('/medicamentos-pessoais', 'ControladoraMedicamentoPessoal:adicionar');
$app->post('/medicamentos-pessoais', 'ControladoraMedicamentoPessoal:atualizar');
$app->get('/medicamentos-pessoais/{id}', 'ControladoraMedicamentoPessoal:comId');
$app->delete('/medicamentos-pessoais/{id}', 'ControladoraMedicamentoPessoal:remover');
// // Fim das rotas para Medicamentos Pessoal

// // Início das rotas para posologias
// $app->get('/posologias', function() use ($app)
// {
// 	$params = $app->request->get();
// 	$geradoraResposta = new GeradoraRespostaComSlim($app);
// 	$session = new Session();
// 	$sessaoUsuario = new Sessao($session);
// 	$ctrl = new ControladoraPosologia($geradoraResposta, $params, $sessaoUsuario);
// 	$ctrl->todos();
// });

// $app->get('/posologias/tempo-unidades', function() use ($app)
// {
// 	$params = $app->request->get();
// 	$geradoraResposta = new GeradoraRespostaComSlim($app);
// 	$session = new Session();
// 	$sessaoUsuario = new Sessao($session);
// 	$ctrl = new ControladoraPosologia($geradoraResposta, $params, $sessaoUsuario);
// 	$ctrl->getTempoUnidade();
// });

// $app->post('/posologias', function() use ($app)
// {
// 	$params = $app->request->post();
// 	$geradoraResposta = new GeradoraRespostaComSlim($app );
// 	$session = new Session();
// 	$sessaoUsuario = new Sessao($session);
// 	$ctrl = new ControladoraPosologia($geradoraResposta, $params, $sessaoUsuario);
// 	$ctrl->adicionar();
// });

// $app->put('/posologias/:id', function($id) use ($app)
// {
// 	$params = $app->request->put();
// 	$geradoraResposta = new GeradoraRespostaComSlim($app);
// 	$session = new Session();
// 	$sessaoUsuario = new Sessao($session);
// 	$ctrl = new ControladoraPosologia($geradoraResposta, $params, $sessaoUsuario);
// 	$ctrl->atualizar();
// });

// $app->get('/posologias/:id', function($id) use ($app)
// {
// 	$params = ['id' => $id];
// 	$geradoraResposta = new GeradoraRespostaComSlim($app);
// 	$session = new Session();
// 	$sessaoUsuario = new Sessao($session);
// 	$ctrl = new ControladoraPosologia($geradoraResposta, $params, $sessaoUsuario);
// 	$ctrl->comId($id);
// });

// $app->delete('/posologias/:id', function($id) use ($app)
// {
// 	$params = array('id' => $id);
// 	$geradoraResposta = new GeradoraRespostaComSlim($app);
// 	$session = new Session();
// 	$sessaoUsuario = new Sessao($session);
// 	$ctrl = new ControladoraPosologia($geradoraResposta, $params, $sessaoUsuario);
// 	$ctrl->remover();
// });

// // Fim das rotas para posologias

// // Início das rotas para Medicamentos
// $app->get('/medicamentos/:id', function($id) use ($app)
// {
// 	$params = $app->request->get();
// 	$geradoraResposta = new GeradoraRespostaComSlim($app);
// 	$session = new Session();
// 	$sessaoUsuario = new Sessao($session);
// 	$ctrl = new ControladoraMedicamento($geradoraResposta, $params, $sessaoUsuario);
// 	$ctrl->comId($id);
// });

// $app->post('/medicamentos/pesquisar-medicamento', function() use ($app)
// {
// 	$params = $app->request->post();
// 	$geradoraResposta = new GeradoraRespostaComSlim($app);
// 	$session = new Session();
// 	$sessaoUsuario = new Sessao($session);
// 	$ctrl = new ControladoraMedicamento($geradoraResposta, $params, $sessaoUsuario);
// 	$ctrl->pesquisarMedicamentoParaAutoComplete();
// });

// $app->post('/medicamentos/buscar-medicamento', function() use ($app)
// {
// 	$params = $app->request->post();
// 	$geradoraResposta = new GeradoraRespostaComSlim($app);
// 	$session = new Session();
// 	$sessaoUsuario = new Sessao($session);
// 	$ctrl = new ControladoraMedicamento($geradoraResposta, $params, $sessaoUsuario);
// 	$ctrl->getMedicamentoDoSistema();
// });
// // Fim das rotas para Medicamentos

// // Início das rotas para farmacias
// $app->get('/farmacias', function() use ($app)
// {
// 	$params = $app->request->get();
// 	$geradoraResposta = new GeradoraRespostaComSlim($app);
// 	$session = new Session();
// 	$sessaoUsuario = new Sessao($session);
// 	$ctrl = new ControladoraFarmacia( $geradoraResposta, $params, $sessaoUsuario);
// 	$ctrl->todos();
// });

// $app->post('/farmacias', function() use ($app)
// {
// 	$params = $app->request->post();
// 	$geradoraResposta = new GeradoraRespostaComSlim($app );
// 	$session = new Session();
// 	$sessaoUsuario = new Sessao($session);
// 	$ctrl = new ControladoraFarmacia($geradoraResposta, $params, $sessaoUsuario);
// 	$ctrl->adicionar();
// });

// $app->put('/farmacias/:id', function($id) use ($app)
// {
// 	$params = $app->request->put();
// 	$geradoraResposta = new GeradoraRespostaComSlim($app);
// 	$session = new Session();
// 	$sessaoUsuario = new Sessao($session);
// 	$ctrl = new ControladoraFarmacia($geradoraResposta, $params, $sessaoUsuario);
// 	$ctrl->atualizar();
// });

// $app->get('/farmacias/:id', function($id) use ($app)
// {
// 	$params = ['id' => $id];
// 	$geradoraResposta = new GeradoraRespostaComSlim($app);
// 	$session = new Session();
// 	$sessaoUsuario = new Sessao($session);
// 	$ctrl = new ControladoraFarmacia($geradoraResposta, $params, $sessaoUsuario);
// 	$ctrl->comId();
// });

// $app->delete('/farmacias/:id', function($id) use ($app)
// {
// 	$params = array('id' => $id);
// 	$geradoraResposta = new GeradoraRespostaComSlim($app);
// 	$session = new Session();
// 	$sessaoUsuario = new Sessao($session);
// 	$ctrl = new ControladoraFarmacia($geradoraResposta, $params, $sessaoUsuario);
// 	$ctrl->remover();
// });

// $app->post('/farmacias/pesquisar-farmacias', function() use ($app)
// {
// 	$params = $app->request->post();
// 	$geradoraResposta = new GeradoraRespostaComSlim($app);
// 	$session = new Session();
// 	$sessaoUsuario = new Sessao($session);
// 	$ctrl = new ControladoraFarmacia($geradoraResposta, $params, $sessaoUsuario);
// 	$ctrl->autoCompleteFarmacia();
// });
// // Fim das rotas para farmacias


// // Início das rotas para usuários
// $app->post('/usuarios', function() use ($app)
// {
// 	$params = $app->request->post();
// 	$geradoraResposta = new GeradoraRespostaComSlim($app );
// 	$session = new Session();
// 	$sessaoUsuario = new Sessao($session);
// 	$ctrl = new ControladoraUsuario($geradoraResposta, $params, $sessaoUsuario);
// 	$ctrl->adicionar();
// });

// $app->get('/usuarios/get-usuario-sessao', function() use ($app)
// {
// 	$params = $app->request->post();
// 	$geradoraResposta = new GeradoraRespostaComSlim($app );
// 	$session = new Session();
// 	$sessaoUsuario = new Sessao($session);
// 	$ctrl = new ControladoraUsuario($geradoraResposta, $params,$sessaoUsuario);
// 	$ctrl->getUsuarioSessao();
// });

// $app->put('/usuarios/:id', function($id) use ($app)
// {
// 	$params = $app->request->put();
// 	$geradoraResposta = new GeradoraRespostaComSlim($app);
// 	$session = new Session();
// 	$sessaoUsuario = new Sessao($session);
// 	$ctrl = new ControladoraUsuario($geradoraResposta, $params, $sessaoUsuario);
// 	$ctrl->atualizar();
// });

// $app->get('/usuarios/:id', function($id) use ($app)
// {
// 	$params = ['id' => $id];
// 	$geradoraResposta = new GeradoraRespostaComSlim($app);
// 	$session = new Session();
// 	$sessaoUsuario = new Sessao($session);
// 	$ctrl = new ControladoraUsuario($geradoraResposta, $params,$sessaoUsuario);
// 	$ctrl->comId();
// });

// $app->post('/usuarios/nova-senha', function() use ($app)
// {
// 	$params = $app->request->post();
// 	$geradoraResposta = new GeradoraRespostaComSlim($app);
// 	$session = new Session();
// 	$sessaoUsuario = new Sessao($session);
// 	$ctrl = new ControladoraUsuario($geradoraResposta, $params, $sessaoUsuario);
// 	$ctrl->novaSenha();
// });

// $app->delete('/usuarios/:id', function($id) use ($app)
// {
// 	$params = array('id' => $id);
// 	$geradoraResposta = new GeradoraRespostaComSlim($app);
// 	$session = new Session();
// 	$sessaoUsuario = new Sessao($session);
// 	$ctrl = new ControladoraUsuario($geradoraResposta, $params, $sessaoUsuario);
// 	$ctrl->remover();
// });
// // Fim das rotas para usuários

// // Início das rotas para Laborátorios
// $app->get('/laboratorios/:id', function($id) use ($app)
// {
// 	$params = $app->request->get();
// 	$geradoraResposta = new GeradoraRespostaComSlim($app);
// 	$session = new Session();
// 	$sessaoUsuario = new Sessao($session);
// 	$ctrl = new ControladoraLaboratorio($geradoraResposta, $params, $sessaoUsuario);
// 	$ctrl->comId($id);
// });

// $app->post('/laboratorios/laboratorios-do-medicamento', function() use ($app)
// {
// 	$params = $app->request->post();
// 	$geradoraResposta = new GeradoraRespostaComSlim($app);
// 	$session = new Session();
// 	$sessaoUsuario = new Sessao($session);
// 	$ctrl = new ControladoraLaboratorio($geradoraResposta, $params, $sessaoUsuario);
// 	$ctrl->getLaboratoriosDoMedicamento();
// });
// // Fim das rotas para usuários

// // Início das rotas para login
// $app->post('/login', function() use ($app)
// {
// 	$params = $app->request->post();
// 	$geradoraResposta = new GeradoraRespostaComSlim($app );
// 	$session = new Session();
// 	$sessaoUsuario = new Sessao($session);
// 	$ctrl = new ControladoraLogin($geradoraResposta, $params, $sessaoUsuario);
// 	$ctrl->logar();
// });

// $app->delete('/logout', function() use ($app)
// {
// 	$session = new Session();
// 	$sessaoUsuario = new Sessao($session);
// 	$geradoraResposta = new GeradoraRespostaComSlim($app );
// 	$ctrl = new ControladoraLogin($geradoraResposta, null, $sessaoUsuario );
// 	$ctrl->sair();
// });
// // Fim das rotas para login

// // Início das rotas para sessão
// $app->post('/sessao/verificar-sessao', function() use ($app)
// {
// 	$params = $app->request->post();
// 	$geradoraResposta = new GeradoraRespostaComSlim($app );
// 	$session = new Session();
// 	$sessaoUsuario = new Sessao($session);
// 	$ctrl = new ControladoraSessao($geradoraResposta, $params, $sessaoUsuario);
// 	$ctrl->estaAtiva();
// });
// // Fim das rotas para sessão

// //Inicio Rotas para Favoritos
// $app->get('/favorito', function() use ($app)
// {
// 	$params = $app->request->get();
// 	$geradoraResposta = new GeradoraRespostaComSlim($app);
// 	$session = new Session();
// 	$sessaoUsuario = new Sessao($session);
// 	$ctrl = new ControladoraFavorito($geradoraResposta, $params, $sessaoUsuario);
// 	$ctrl->todos();
// });

// $app->post('/favorito', function() use ($app)
// {
// 	$params = $app->request->post();
// 	$geradoraResposta = new GeradoraRespostaComSlim($app );
// 	$session = new Session();
// 	$sessaoUsuario = new Sessao($session);
// 	$ctrl = new ControladoraFavorito($geradoraResposta, $params, $sessaoUsuario);
// 	$ctrl->adicionar();
// });

// $app->post('/favorito/esta-nos-favoritos', function() use ($app)
// {
// 	$params = $app->request->post();
// 	$geradoraResposta = new GeradoraRespostaComSlim($app );
// 	$session = new Session();
// 	$sessaoUsuario = new Sessao($session);
// 	$ctrl = new ControladoraFavorito($geradoraResposta, $params, $sessaoUsuario);
// 	$ctrl->estaNosFavoritos();
// });

// $app->put('/favorito', function() use ($app)
// {
// 	$params = $app->request->put();
// 	$geradoraResposta = new GeradoraRespostaComSlim($app);
// 	$session = new Session();
// 	$sessaoUsuario = new Sessao($session);
// 	$ctrl = new ControladoraFavorito($geradoraResposta, $params, $sessaoUsuario);
// 	$ctrl->atualizar();
// });

// $app->delete('/favorito/:id', function($id) use ($app)
// {
// 	$params = array('id' => $id);
// 	$geradoraResposta = new GeradoraRespostaComSlim($app);
// 	$session = new Session();
// 	$sessaoUsuario = new Sessao($session);
// 	$ctrl = new ControladoraFavorito($geradoraResposta, $params, $sessaoUsuario);
// 	$ctrl->remover();
// });

// //Inicio Rotas Endereço

// $app->get('/endereco/estados', function() use ($app)
// {
// 	$params = $app->request->get();
// 	$geradoraResposta = new GeradoraRespostaComSlim($app);
// 	$session = new Session();
// 	$sessaoUsuario = new Sessao($session);
// 	$ctrl = new ControladoraEndereco($geradoraResposta, $params, $sessaoUsuario);
// 	$ctrl->todosEstados();
// });

// $app->post('/endereco-cep', function() use ($app)
// {
// 	$params = $app->request->get();
// 	$geradoraResposta = new GeradoraRespostaComSlim($app);
// 	$session = new Session();
// 	$sessaoUsuario = new Sessao($session);
// 	$ctrl = new ControladoraEndereco($geradoraResposta, $params, $sessaoUsuario);
// 	$ctrl->comCep();
// });

// $app->post('/endereco/endereco-uf', function() use ($app)
// {
// 	$params = $app->request->post();
// 	$geradoraResposta = new GeradoraRespostaComSlim($app);
// 	$session = new Session();
// 	$sessaoUsuario = new Sessao($session);
// 	$ctrl = new ControladoraEndereco($geradoraResposta, $params, $sessaoUsuario);
// 	$ctrl->comUf();
// });

// $app->post('/endereco/endereco-geolocalizacao', function() use ($app)
// {
// 	$params = $app->request->post();
// 	$geradoraResposta = new GeradoraRespostaComSlim($app);
// 	$session = new Session();
// 	$sessaoUsuario = new Sessao($session);
// 	$ctrl = new ControladoraEndereco($geradoraResposta, $params, $sessaoUsuario);
// 	$ctrl->comGeolocalizacao();
// });
//Fim Rotas Endereço