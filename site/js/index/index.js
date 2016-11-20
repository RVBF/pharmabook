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
		
		_this.verificar = function()
		{

			var usuario;
			console.log(document.cookie);
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
	}; 

	app.ControladoraIndex = ControladoraIndex;
})(window, app, jQuery, toastr );