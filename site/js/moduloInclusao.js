/**
 * includes.js
 *
 * @author	Rafael  Vinicius Barros Ferreira
 */

(function(window)
{
	'use strict';

	function Loader() {

		var _this = this;

		var createElement = function createElement(name)
		{
			return window.document.createElement(name );
		};

		var addToHead = function addToHead(element)
		{
			window.document.head.appendChild(element );
		};

		var addToBody = function addToBody(element)
		{
			window.document.body.appendChild(element );
		};

		/**
		 * Carrega um script no documento atual.
		 *
		 * @param string	src			Arquivo de script.
		 * @param boolean	isAsync		Se o carregamento é assíncrono.
		 * @param callable	onLoad		Método a ser executado quando carregar.
		 *								Opcional.
		 * @return element	O elemento de script criado.
		 */
		_this.script = function script(src, isAsync, onLoad, type)
		{
			var e = createElement('script' );

			e.src = src;
			e.async = isAsync === true;
			if (onLoad !== undefined)
				{ e.onload = onLoad; }
			e.type = type !== undefined ? type : 'text/javascript';

			addToBody(e );
		};

		_this.link = function link(href, rel, type)
		{
			var e = createElement('link' );
			e.href = href;
			if (rel !== undefined)
			{
				e.rel = rel;
			}
			if (type !== undefined)
			{
				e.type = type;
			}

			addToHead(e );
		};

		_this.css = function css(href)
		{
			return _this.link(href, 'stylesheet', 'text/css' );
		};

		_this.font = function font(href)
		{
			return _this.link(href, undefined, 'application/octet-stream' );
		};
	} // class

	// Registrando no window
	window.Loader = Loader;
})(window);


(function(window)
{
	'use strict';

	// CAMINHOS ---------------------------------------------------------------

	var sistemaDePastas = {};

	sistemaDePastas.js = 'js/';
	sistemaDePastas.usuario = sistemaDePastas.js + 'usuario/';
	sistemaDePastas.endereco = sistemaDePastas.js + 'endereco/';
	sistemaDePastas.favorito = sistemaDePastas.js + 'favorito/';
	sistemaDePastas.index = sistemaDePastas.js + 'index/';
	sistemaDePastas.laboratorio = sistemaDePastas.js + 'laboratorio/';
	sistemaDePastas.login = sistemaDePastas.js + 'login/';
	sistemaDePastas.medicamento = sistemaDePastas.js + 'medicamento/';
	sistemaDePastas.medicamentoPessoal = sistemaDePastas.js + 'medicamento-pessoal/';
	sistemaDePastas.logout = sistemaDePastas.js + 'logout/';
	sistemaDePastas.medicamentoPrecificado = sistemaDePastas.js + 'medicamento-precificado/';
	sistemaDePastas.principioAtivo = sistemaDePastas.js + 'principio-ativo/';
	sistemaDePastas.sessao = sistemaDePastas.js + 'sessao/';
	sistemaDePastas.usuario = sistemaDePastas.js 	   + 'usuario/';
	sistemaDePastas.posologia = sistemaDePastas.js 	   + 'posologia/';
	sistemaDePastas.posologia = sistemaDePastas.js 	   + 'posologia/';
	sistemaDePastas.farmacia = sistemaDePastas.js 	   + 'farmacia/';
	sistemaDePastas.classeTerapeutica = sistemaDePastas.js 	   + 'classe-terapeutica/';

	// DEPENDÊNCIAS EXTERNAS --------------------------------------------------
	sistemaDePastas.vendor = 'vendor/';
	sistemaDePastas.dist = 'dist/';
	sistemaDePastas.css = 'css/';
	sistemaDePastas.jquery = sistemaDePastas.vendor + 'jquery/';
	sistemaDePastas.datatables = sistemaDePastas.vendor + 'datatables/';
	sistemaDePastas.datatablesResponsive = sistemaDePastas.vendor + 'datatables-responsive/';
	sistemaDePastas.datatablesNetSelect = sistemaDePastas.vendor + 'datatables.net-select/';
	sistemaDePastas.jqueryMaskedInput = sistemaDePastas.vendor + 'jquery.maskedinput/';
	sistemaDePastas.maskMoney = sistemaDePastas.vendor + 'jquery-maskmoney/';
	sistemaDePastas.jqueryUi = sistemaDePastas.vendor + 'jquery-ui/';
	sistemaDePastas.jqueryUiBoostrap = sistemaDePastas.vendor + 'jquery-ui-bootstrap/';
	sistemaDePastas.bootstrap = sistemaDePastas.vendor + 'bootstrap/';
	sistemaDePastas.jsSignals = sistemaDePastas.vendor + 'js-signals/';
	sistemaDePastas.crossroads = sistemaDePastas.vendor + 'crossroads/';
	sistemaDePastas.jqueryValidation = sistemaDePastas.vendor + 'jquery-validation/';
	sistemaDePastas.toastr = sistemaDePastas.vendor + 'toastr/';
	sistemaDePastas.bootstrap3Dialog = sistemaDePastas.vendor + 'bootstrap3-dialog/';
	sistemaDePastas.bootstrapDatepicker = sistemaDePastas.vendor + 'bootstrap-datepicker/';
	sistemaDePastas.select2 = sistemaDePastas.vendor + 'select2/';
	sistemaDePastas.jqueryInputmask = sistemaDePastas.vendor + 'jquery.inputmask/';
	sistemaDePastas.select2BootstrapTheme = sistemaDePastas.vendor + 'select2-bootstrap-theme/';
	sistemaDePastas.grapnel = sistemaDePastas.vendor + 'grapnel/' + sistemaDePastas.dist;

	var dependenciasJavaScript = [];

	dependenciasJavaScript.push({ url : sistemaDePastas.jquery + 'dist/jquery.min.js' });
	dependenciasJavaScript.push({ url : sistemaDePastas.datatables + 'media/js/jquery.dataTables.min.js' });
	dependenciasJavaScript.push({ url : sistemaDePastas.datatablesResponsive + 'js/dataTables.responsive.js' });
	dependenciasJavaScript.push({ url : sistemaDePastas.datatablesResponsive + 'js/responsive.bootstrap.js' });
	dependenciasJavaScript.push({ url : sistemaDePastas.datatables + 'media/js/dataTables.bootstrap.min.js' });
	dependenciasJavaScript.push({ url : sistemaDePastas.datatablesNetSelect + 'js/dataTables.select.js' });
	dependenciasJavaScript.push({ url : sistemaDePastas.jqueryMaskedInput + 'dist/jquery.maskedinput.min.js' });
	dependenciasJavaScript.push({ url : sistemaDePastas.maskMoney + 'dist/jquery.maskMoney.min.js' });
	dependenciasJavaScript.push({ url : sistemaDePastas.jqueryUi + 'jquery-ui.min.js' });
	dependenciasJavaScript.push({ url : sistemaDePastas.bootstrap + 'dist/js/bootstrap.min.js' });
	dependenciasJavaScript.push({ url : sistemaDePastas.jsSignals + 'dist/signals.min.js' });
	dependenciasJavaScript.push({ url : sistemaDePastas.crossroads + 'dist/crossroads.min.js' });
	dependenciasJavaScript.push({ url : sistemaDePastas.jqueryValidation + 'dist/jquery.validate.min.js' });
	dependenciasJavaScript.push({ url : sistemaDePastas.jqueryValidation + 'dist/additional-methods.min.js' });
	dependenciasJavaScript.push({ url : sistemaDePastas.toastr + 'toastr.min.js' });
	dependenciasJavaScript.push({ url : sistemaDePastas.bootstrap3Dialog + 'dist/js/bootstrap-dialog.min.js' });
	dependenciasJavaScript.push({ url : sistemaDePastas.bootstrapDatepicker + 'dist/js/bootstrap-datepicker.min.js' });
	dependenciasJavaScript.push({ url : sistemaDePastas.bootstrapDatepicker + 'dist/locales/bootstrap-datepicker.pt-BR.min.js' });
	dependenciasJavaScript.push({ url : sistemaDePastas.select2 + 'dist/js/select2.js' });
	dependenciasJavaScript.push({ url : sistemaDePastas.jqueryInputmask + 'dist/inputmask/inputmask.js' });
	dependenciasJavaScript.push({ url : sistemaDePastas.jqueryInputmask + 'dist/inputmask/inputmask.extensions.js' });
	dependenciasJavaScript.push({ url : sistemaDePastas.jqueryInputmask + 'dist/inputmask/inputmask.numeric.extensions.js' });
	dependenciasJavaScript.push({ url :sistemaDePastas.grapnel + 'grapnel.min.js' });


	var dependenciasCSS = [];

	dependenciasCSS.push({ url : sistemaDePastas.css + 'estilo.css' });
	dependenciasCSS.push({ url : sistemaDePastas.bootstrap + 'dist/css/bootstrap.min.css' });
	dependenciasCSS.push({ url : sistemaDePastas.datatablesResponsive + 'css/responsive.bootstrap.scss' });
	dependenciasCSS.push({ url : sistemaDePastas.datatablesResponsive + 'css/responsive.dataTables.scss' });
	dependenciasCSS.push({ url : sistemaDePastas.datatables + 'media/css/dataTables.bootstrap.min.css' });
	dependenciasCSS.push({ url : sistemaDePastas.toastr + 'toastr.min.css' });
	dependenciasCSS.push({ url : sistemaDePastas.bootstrap3Dialog + 'dist/css/bootstrap-dialog.min.css' });
	dependenciasCSS.push({ url : sistemaDePastas.jqueryUi + 'themes/base/jquery-ui.min.css' });
	dependenciasCSS.push({ url : sistemaDePastas.jqueryUiBoostrap + 'jquery.ui.theme.css' });
	dependenciasCSS.push({ url : sistemaDePastas.jqueryUiBoostrap + 'jquery.ui.theme.font-awesome.css' });
	dependenciasCSS.push({ url : sistemaDePastas.bootstrapDatepicker + 'dist/css/bootstrap-datepicker.min.css' });
	dependenciasCSS.push({ url : sistemaDePastas.select2 + 'dist/css/select2.css' });
	dependenciasCSS.push({ url : sistemaDePastas.select2BootstrapTheme + 'dist/select2-bootstrap.css' });
	dependenciasCSS.push({ url : sistemaDePastas.datatables + 'media/css/jquery.dataTables.css' });


	// DEPENDÊNCIAS DE MÓDULOS ------------------------------------------------

	var modulosJS = [];
	// Inicialização
	modulosJS.push({ url : sistemaDePastas.js + 'main.js' });
	modulosJS.push({ url : sistemaDePastas.sessao + 'servicoSessao.serv.js' });
	modulosJS.push({ url : sistemaDePastas.js + 'rotas.js' });
	modulosJS.push({ url : sistemaDePastas.js + 'edicao.ctrl.js' });
	modulosJS.push({ url : sistemaDePastas.js + 'funcoesSistema.js' });
	modulosJS.push({ url : sistemaDePastas.js + 'index/index.serv.js' });
	modulosJS.push({ url : sistemaDePastas.js + 'index/index.cfg.js' });
	modulosJS.push({ url : sistemaDePastas.js + 'index/index.js' });

	modulosJS.push({ url : sistemaDePastas.usuario + 'usuario.serv.js'});
	modulosJS.push({ url : sistemaDePastas.usuario + 'usuario.form.ctrl.js'});
	modulosJS.push({ url : sistemaDePastas.usuario + 'visualizarPerfil.form.ctrl.js' });

	modulosJS.push({ url : sistemaDePastas.principioAtivo + 'principioAtivo.serv.js' });
	modulosJS.push({ url : sistemaDePastas.classeTerapeutica + 'classeTerapeutica.serv.js' });
	modulosJS.push({ url : sistemaDePastas.laboratorio + 'laboratorio.serv.js' });
	modulosJS.push({ url : sistemaDePastas.medicamento + 'medicamento.serv.js' });
	modulosJS.push({ url : sistemaDePastas.medicamento + 'medicamento.list.ctrl.js' });

	modulosJS.push({ url : sistemaDePastas.medicamentoPessoal + 'medicamentoPessoal.serv.js' });
	modulosJS.push({ url : sistemaDePastas.medicamentoPessoal + 'medicamentoPessoal.form.ctrl.js' });
	modulosJS.push({ url : sistemaDePastas.medicamentoPessoal + 'medicamentoPessoal.list.ctrl.js' });

	modulosJS.push({ url : sistemaDePastas.medicamentoPrecificado + 'medicamentoPrecificado.serv.js' });
	modulosJS.push({ url : sistemaDePastas.medicamentoPrecificado + 'medicamentoPrecificado.form.ctrl.js' });
	modulosJS.push({ url : sistemaDePastas.medicamentoPrecificado + 'medicamentoPrecificado.list.ctrl.js' });

	modulosJS.push({ url : sistemaDePastas.posologia + 'posologia.serv.js' });
	modulosJS.push({ url : sistemaDePastas.posologia + 'posologia.form.ctrl.js' });
	modulosJS.push({ url : sistemaDePastas.posologia + 'posologia.list.ctrl.js' });

	modulosJS.push({ url : sistemaDePastas.endereco + 'endereco.serv.js' });
	modulosJS.push({ url : sistemaDePastas.farmacia + 'farmacia.serv.js' });
	modulosJS.push({ url : sistemaDePastas.farmacia + 'farmacia.form.ctrl.js' });
	modulosJS.push({ url : sistemaDePastas.farmacia + 'farmacia.list.ctrl.js' });

	modulosJS.push({ url : sistemaDePastas.favorito + 'favorito.serv.js' });
	modulosJS.push({ url : sistemaDePastas.favorito + 'favorito.list.ctrl.js' });

	modulosJS.push({ url : sistemaDePastas.logout + 'logout.js' });
	modulosJS.push({ url : sistemaDePastas.js + 'instanciarJs.js'});

	//CARREGANDO -------------------------------------------------------------
	var loader = new window.Loader();

	dependenciasCSS.forEach(function(e, index, arr)
	{
		loader.css(e.url );
	} );

	dependenciasJavaScript.forEach(function(e, index, arr)
	{
		loader.script(e.url, e.async );
	} );

	modulosJS.forEach(function(e, index, arr)
	{
		loader.script(e.url, e.async );
	} );
})(window);