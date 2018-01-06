<?php
// ----------------------------------------------------------------------------
// Variáveis do index.php são acessíveis neste arquivo.
// ----------------------------------------------------------------------------
$app->get('/hello/{name}', function ($request, $response, $args) {
    return $response->write("Hello " . $args['name']);
});
// Início das rotas para Medicamentos Precificados
$app->get('medicamentos-precificados', 'ControladoraMedicamentoPrecificado:todos');
$app->post('medicamentos-precificados', 'ControladoraMedicamentoPrecificado:adicionar');
$app->put('medicamentos-precificados', 'ControladoraMedicamentoPrecificado:atualizar');
$app->get('medicamentos-precificados/{id}', 'ControladoraMedicamentoPrecificado:comId');
$app->delete('medicamentos-precificados/{id}', 'ControladoraMedicamentoPrecificado:remover');
$app->post('medicamentos-precificados/pesquisar-medicamentoPrecificado', 'ControladoraMedicamentoPrecificado:autoCompleteMedicamentoPrecificado');
$app->post('medicamentos-precificados/buscar-medicamentoPrecificado', 'ControladoraMedicamentoPrecificado:getMedicamentosPrecificados');
// Fim das rotas para Medicamentos Precificados

// Início das rotas para Medicamentos Pessoal
$app->get('medicamentos-pessoais', 'ControladoraPosologia:todos');
$app->get('medicamentos-pessoais/administracoes', 'ControladoraMedicamentoPessoal:getAdministracoes');
$app->get('medicamentos-pessoais/medicamentos-formas', 'ControladoraMedicamentoPessoal:getMedicamentosFormas');
$app->get('medicamentos-pessoais/unidades-solidas', 'ControladoraMedicamentoPessoal:getUnidadesSolidas');
$app->get('medicamentos-pessoais/unidades-inteiras', 'ControladoraMedicamentoPessoal:getUnidadesInteiras');
$app->post('medicamentos-pessoais', 'ControladoraMedicamentoPessoal:adicionar');
$app->put('medicamentos-pessoais', 'ControladoraMedicamentoPessoal:atualizar');
$app->get('medicamentos-pessoais/{id}', 'ControladoraMedicamentoPessoal:comId');
$app->delete('medicamentos-pessoais/{id}', 'ControladoraMedicamentoPessoal:remover');
// Fim das rotas para Medicamentos Pessoal

// Início das rotas para posologias
$app->get('posologias', 'ControladoraPosologia:todos');
$app->post('posologias', 'ControladoraPosologia:adicionar');
$app->put('posologias/{id}', 'ControladoraPosologia:atualizar');
$app->get('posologias/{id}', 'ControladoraPosologia:comId');
$app->delete('posologias/{id}', 'ControladoraPosologia:remover');
// Fim das rotas para posologias

// Início das rotas para Medicamentos
$app->get('medicamentos/{id}', 'ControladoraMedicamento:comId');
$app->post('medicamentos/pesquisar-medicamento', 'ControladoraMedicamento:pesquisarMedicamentoParaAutoComplete');
$app->post('medicamentos/buscar-medicamento', 'ControladoraMedicamento:getMedicamentoDoSistema');
// Fim das rotas para Medicamentos

// Início das rotas para farmacias
$app->get('farmacias','ControladoraFarmacia:todos');
$app->post('farmacias','ControladoraFarmacia:adicionar');
$app->put('farmacias/{id}', 'ControladoraFarmacia:atualizar');
$app->get('farmacias/{id}', 'ControladoraFarmacia:comId');
$app->delete('farmacias/{id}', 'ControladoraFarmacia:remover');
$app->post('farmacias/pesquisar-farmacias', 'ControladoraFarmacia:autoCompleteFarmacia');
// Fim das rotas para farmacias

// Início das rotas para usuários
$app->post('usuarios', 'ControladoraUsuario:adicionar');
$app->get('usuarios/get-usuario-sessao', 'ControladoraUsuario:getUsuarioSessao');
$app->put('usuarios/{id}', 'ControladoraUsuario:atualizar');
$app->get('usuarios/{id}', 'ControladoraUsuario:comId');
$app->post('usuarios/nova-senha', 'ControladoraUsuario:novaSenha');
$app->delete('usuarios/{id}', 'ControladoraUsuario:remover');
// Fim das rotas para usuários

// Início das rotas para Laborátorios
$app->get('laboratorios/{id}', 'ControladoraLaboratorio:comId');
$app->post('laboratorios/laboratorios-do-medicamento', 'ControladoraLaboratorio:getLaboratoriosDoMedicamento');
// Fim das rotas para usuários

// Início das rotas para login
$app->get('login', 'controladoralogin:logar');
$app->get('logout', 'controladoralogin:sair');
// Fim das rotas para login

// Início das rotas para sessão
$app->post('/verificar-sessao', 'ControladoraSessao:estaAtiva');
// Fim das rotas para sessão

// Inicio Rotas para Favoritos
$app->get('favorito', 'ControladoraFavorito:todos');
$app->post('favorito', 'ControladoraFavorito:adicionar');
$app->post('favorito/esta-nos-favoritos', 'ControladoraFavorito:estaNosFavoritos');
$app->put('favorito/{id}', 'ControladoraFavorito:atualizar');
$app->delete('favorito/{id}', 'ControladoraFavorito:remover');
// Fim Rotas para Favoritos

// Inicio Rotas Endereço
$app->get('endereco/estados', 'ControladoraEndereco:todosEstados');
$app->post('endereco-cep', 'ControladoraEndereco:comCep');
$app->post('endereco/endereco-uf',  'ControladoraEndereco:comUf');
$app->post('endereco/endereco-geolocalizacao',  'ControladoraEndereco:comGeolocalizacao');
//Fim Rotas Endereço