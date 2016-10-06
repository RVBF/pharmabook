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
		
		var criarRotaPara = function criarRotaPara(pagina)
		{
			return function()
			{
				carregarPagina(pagina); 
			};
		};
		
		// Rotas: adicione sua rota ACIMA das existentes, a seguir. -Thiago
		crossroads.addRoute('/usuario', criarRotaPara('usuario.html'));
		crossroads.addRoute('/login', criarRotaPara('login.html' ));
		crossroads.addRoute('/logout', criarRotaPara('login.html' ));
		crossroads.addRoute('/medicamento', criarRotaPara('medicamento.html'));
		crossroads.addRoute('/', rotaHome);
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