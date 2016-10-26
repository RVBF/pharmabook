/**
 *  index.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(window, app, $, toastr){
	'use strict';	
	function ControladoraIndex(servico){
		var _this = this;

		// Redireciona para o login
		var irProLogin = function irProLogin()
		{
			window.location.href = 'login.html';
		};

		var irParaInicio = function irParaInicio()
		{
			window.location.href = 'index.html';
		};
		
		_this.verificar = function(sessao)
		{
			var usuario = window.localStorage.getItem('usuario');

			if(usuario == null)
			{
				window.localStorage.removeItem('usuario')

				irProLogin();
			}
			else
			{
				irParaInicio();
			}	
		};

		_this.verificarSessaoServidor = function verificarSessaoServidor()
		{
			var jqXHR = servico.retornarSessao();

			console.log(jqXHR);
			// jqXHR
			// 	.done(_this.verificar)
			// 	// .fail(erro)
			// 	// .always(terminado)
			// 	;	
		};

		_this.openNav = function openNav()
		{
			$("#menu_lateral").css({"width":"100%"});
		};

		_this.closeNav = function closeNav()
		{
			$("#menu_lateral").css({"width":"0"});
		};

		_this.openNavPush = function openNavPush()
		{
		 	$("#menu_lateral").css({"width":"250px"});
		 	$("#main").css({"width":"250px"});
		};

		_this.closeNavPush = function closeNavPush()
		{
		 	$("#menu_lateral").css({"width":"0"});
		 	$("#main").css({"width":"0"});
		};

		// Configura os eventos da página
		_this.configurar = function () 
		{ 
			if($(window).width() <= 768 )
			{
				$('.close').on('click', _this.closeNav);

				$('.navbar-toggle').on('click', _this.openNav);
			}
			else
			{
				$('.close').on('click', _this.closeNavPush);

				$('.navbar-toggle').on('click', _this.openNavPush);
			}
		};
	}; 

	app.ControladoraIndex = ControladoraIndex;
})(window, app, jQuery, toastr );