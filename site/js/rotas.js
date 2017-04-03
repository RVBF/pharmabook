/**
 *  rotas.js
 *
 *  @author	Thiago Delgado Pinto
 */

(function(window ,app, document, $, Grapnel)
{
	'use strict';
	var router = new Grapnel();
	var conteudo = $('#conteudo');
	var localizacao = $('#localizacao')

	function caminhoParaVisualizacao(str)
	{
		return str.charAt(0).toUpperCase() + str.substr(1).toLowerCase().replace('-', ' ');
	};

	var setarCaminho = function setarCaminho()
	{
		var rota = router.path();
		var rotaArray = rota.split("/");

		localizacao.find('li:not(#inicio)').remove()

		var linkFinal = '/pharmabook/site/#/';

		$.each(rotaArray, function(i, value)
		{
				var caminhoDeVisualizao = caminhoParaVisualizacao(value);
				var liActive = '<li class="breadcrumb-item active"><a class="link" href="/pharmabook/site/#/' + value + '/">' + caminhoDeVisualizao + '</a></li>';
				var li = '<li class="breadcrumb-item"><a class="link" href="/pharmabook/site/#/' + value + '/">' + caminhoDeVisualizao + '</a></li>';

			linkFinal += '/' + value+ '/';
			if(rotaArray.length > 1 && value != "" && typeof caminhoDeVisualizao == 'String')
			{
				console.log(rotaArray.length);
				if(rotaArray.length -1 == i)
				{
					console.log('entrei');
					localizacao.append('<li class="breadcrumb-item active"><a class="link" href="/pharmabook/site/#/' + linkFinal + '/">' + caminhoDeVisualizao + '</a></li>');
				}
				else
				{
					localizacao.append(li);
				}
			}
			else if(value != "")
			{
				localizacao.append(li);
			}
		});
	};

	var mudarConteudo = function mudarConteudo(valor)
	{
		conteudo.empty().html(valor);
		setarCaminho();
	};

	var carregarPagina = function carregarPagina(pagina)
	{
		conteudo.empty().load(pagina);
		setarCaminho();
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
		return function()
		{
			carregarPagina(pagina);
		};
	};

	// Rotas: adicione sua rota ACIMA das existentes, a seguir. -Thiago
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