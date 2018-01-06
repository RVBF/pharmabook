/**
 *  rotas.js
 *
 *  @author	Rafael vinicius barros ferreira
 */

(function(window ,app, document, $, Grapnel)
{
	'use strict';
	var router = new Grapnel();
	var conteudo = $('#conteudo');

	var mudarConteudo = function mudarConteudo(valor)
	{
		conteudo.empty().html(valor);
	};

	var carregarPagina = function carregarPagina(pagina)
	{
		conteudo.empty().load(pagina);
	};

	var verficarLogin = function (req, event, next)
	{
		var servicoSessao = new app.ServicoSessao();

		var erro = function erro(jqXHR, textStatus, errorThrown)
		{
			var mensagem = jqXHR.responseText || 'Erro ao acessar página.';
			toastr.error(mensagem);

			if(servicoSessao.getSessao() == null || servicoSessao.getSessao() == '')
			{
				servicoSessao.limparSessionStorage();
			}

			servicoSessao.redirecionarParalogin();

			return;
		};

		var jqXHR = servicoSessao.verificarSessao();
		jqXHR.fail(erro);

		if( typeof next == 'function')
		{
			next();
		}
	};

	var naoEstaLogado = function (req, event, next)
	{
		var servicoSessao = new app.ServicoSessao();

		var erro = function erro(jqXHR, textStatus, errorThrown)
		{
			if( typeof next == 'function')
			{
				next();
			}
		};

		var sucesso = function sucesso(data, textStatus, jqXHR)
		{
			return;
		};

		var jqXHR = servicoSessao.verificarSessao();
		jqXHR.fail(erro);
	};

	var criarRotaPara = function criarRotaPara(pagina)
	{
		return function()
		{
			carregarPagina(pagina);
		};
	};
	router.get('/logout', criarRotaPara('login.html'));

	router.get('/farmacias', verficarLogin ,criarRotaPara('farmacia.html'));
	router.get('/farmacias/cadastrar', verficarLogin ,criarRotaPara('formularioFarmacia.html'));
	router.get('/farmacias/visualizar/:id', verficarLogin ,criarRotaPara('formularioFarmacia.html'));
	router.get('/farmacias/editar/:id', verficarLogin ,criarRotaPara('formularioFarmacia.html'));

	router.get('/medicamentos-pessoais', verficarLogin ,criarRotaPara('medicamentoPessoal.html'));
	router.get('/medicamentos-pessoais/cadastrar', verficarLogin ,criarRotaPara('formularioMedicamentoPessoal.html'));
	router.get('/medicamentos-pessoais/visualizar/:id', verficarLogin ,criarRotaPara('formularioMedicamentoPessoal.html'));
	router.get('/medicamentos-pessoais/editar/:id', verficarLogin ,criarRotaPara('formularioMedicamentoPessoal.html'));

	router.get('/posologias', verficarLogin ,criarRotaPara('posologia.html'));
	router.get('/posologias/cadastrar/:idMedicamentoPessoal', verficarLogin ,criarRotaPara('formularioPosologia.html'));
	router.get('/posologias/visualizar/:id', verficarLogin ,criarRotaPara('formularioPosologia.html'));
	router.get('/posologias/editar/:id', verficarLogin ,criarRotaPara('formularioPosologia.html'));

	router.get('/medicamentos-precificados', verficarLogin ,criarRotaPara('medicamentoPrecificado.html'));
	router.get('/medicamentos-precificados/cadastrar', verficarLogin ,criarRotaPara('formularioMedicamentoPrecificado.html'));
	router.get('/medicamentos-precificados/visualizar/:id', verficarLogin ,criarRotaPara('formularioMedicamentoPrecificado.html'));
	router.get('/medicamentos-precificados/editar/:id', verficarLogin ,criarRotaPara('formularioMedicamentoPrecificado.html'));

	router.get('/usuario/visualizar/:id/', verficarLogin ,criarRotaPara('formularioUsuario.html'));
	router.get('/usuario/editar/:id/', verficarLogin ,criarRotaPara('formularioUsuario.html'));
	router.get('/usuario/alterar-senha/:id/', verficarLogin ,criarRotaPara('formularioAlterarSenhaUsuario.html'));
	router.get('/usuario/cadastrar', naoEstaLogado ,criarRotaPara('formularioCadastrarUsuario.html'));

	router.get('/favoritos', verficarLogin , criarRotaPara('favoritos.html'));
	router.get('/home', verficarLogin , criarRotaPara('home.html'));
	router.get('/', verficarLogin , criarRotaPara('home.html'));
	router.get('', verficarLogin , criarRotaPara('home.html'));

	// 404
	router.get('/*', function(req, e)
	{
		if(! e.parent())
		{
			carregarPagina('404.html');
		}
	});

	// Registra como global
	window.router = router;

})(window ,app, document, jQuery, Grapnel);