/**
 *  rotas.js
 *  
 *  @author	Thiago Delgado Pinto
 */
 
(function(window, app, $, crossroads)
{
	var conteudo = $('#conteudo');
	
	var mudarConteudo = function mudarConteudo(valor)
	{
		conteudo.empty().html(valor);
	};
	
	var carregarPagina = function carregarPagina(pagina)
	{
		conteudo.empty().load(pagina);
	};

	var adicionar = function adicionar()
	{
	
		var rotaHome = function rotaHome()
		{
			carregarPagina('home.html');
		};

		var rotaLogin = function rotaLogin()
		{
			carregarPagina('login.html');
		};
		
		var verificar = function verificar()
		{
			var servicoSessao = new app.ServicoSessao();

			var erro = function erro( jqXHR, textStatus, errorThrown ) {
				var mensagem = jqXHR.responseText || 'Erro ao acessar página.';
				toastr.error( mensagem );
				servicoSessao.redirecionarParalogin();
				
				if(servicoSessao.getSessao() == null || servicoSessao.getSessao() == '')
				{
					servicoSessao.limparSessionStorage();
				}

				return false;
			};

			var sucesso = function sucesso( jqXHR, textStatus, errorThrown ) {
				return true
			};

			var jqXHR = servicoSessao.verificarSessao();
			return jqXHR.fail(erro).done(sucesso);
		};

		var criarRotaPara = function criarRotaPara(pagina)
		{
			return function()
			{
				carregarPagina(pagina); 
			};
		};
		
		// Rotas: adicione sua rota ACIMA das existentes, a seguir. -Thiago
		crossroads.addRoute('/login', (verificar()) ? criarRotaPara('login.html' ) : rotaLogin);
		crossroads.addRoute('/logout', criarRotaPara('index.html' ));
		crossroads.addRoute('/medicamentos-precificados', (verificar()) ? criarRotaPara('medicamentoPrecificados.html'): rotaLogin);
		crossroads.addRoute('/farmacias',(verificar()) ? criarRotaPara('farmacias.html'): rotaLogin);
		crossroads.addRoute('/estoque', (verificar()) ? criarRotaPara('estoque.html'): rotaLogin);
		crossroads.addRoute('/favoritos',  (verificar()) ? criarRotaPara('favoritos.html'): rotaLogin);
		crossroads.addRoute('/posologias',  (verificar()) ? criarRotaPara('posologia.html'): rotaLogin);
		crossroads.addRoute('/', (verificar()) ? rotaHome : rotaLogin);
	};

	
	var configurar = function configurar()
	{
		
		adicionar();
			
		crossroads.bypassed.add(function(request)
		{
			console.error(request + ' parece não estar configurado...');
		});

		window.addEventListener("hashchange", function()
		{
			var route = '/';
			var hash = window.location.hash;
			if (hash.length > 0)
			{
				route = hash.split('#').pop();
			}
			crossroads.parse(route);
		});
		 
		window.dispatchEvent(new CustomEvent("hashchange"));	
	};
	
	// Módulo
	app.rotas = {};
	app.rotas.configurar = configurar;

})(window, app, jQuery, crossroads);