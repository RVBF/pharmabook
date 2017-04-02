/**
 *  rotas.js
 *
 *  @author	Thiago Delgado Pinto
 */

(function(window ,app, document, $, Grapnel)
{
	'use strict';
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

	var criarRotaPara = function criarRotaPara(pagina)
	{
		return function() {
			carregarPagina( pagina );
		};
	};

	var router = new Grapnel();

	// Rotas: adicione sua rota ACIMA das existentes, a seguir. -Thiago
	router.get('/logout', criarRotaPara('login.html'));
	router.get('/farmacias', verficarLogin ,criarRotaPara('farmacias.html'));

	router.get('/medicamentos-pessoais', verficarLogin ,criarRotaPara('medicamentoPessoal.html'));
	router.get('/medicamentos-pessoais/cadastrar', verficarLogin ,criarRotaPara('formularioMedicamentoPessoal.html'));
	router.get('/medicamentos-pessoais/visualizar/:id', verficarLogin ,criarRotaPara('formularioMedicamentoPessoal.html'));
	router.get('/medicamentos-pessoais/editar/:id', verficarLogin ,criarRotaPara('formularioMedicamentoPessoal.html'));

	router.get('/medicamentos-precificados', verficarLogin ,criarRotaPara('medicamentoPrecificado.html'));
	router.get('/medicamentos-precificados/cadastrar', verficarLogin ,criarRotaPara('formularioMedicamentoPrecificado.html'));
	router.get('/medicamentos-precificados/visualizar/:id', verficarLogin ,criarRotaPara('formularioMedicamentoPrecificado.html'));
	router.get('/medicamentos-precificados/editar/:id', verficarLogin ,criarRotaPara('formularioMedicamentoPrecificado.html'));

	router.get('/favoritos', verficarLogin , criarRotaPara('favoritos.html'));
	router.get('/posologias', verficarLogin , criarRotaPara('posologia.html'));
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