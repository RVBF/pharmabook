/**
 *  index.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(window, app, $, toastr){
	'use strict';	
	function ControladoraIndex(servico){
		var _this = this;
		var usuarioSessao =  window.sessionStorage.getItem('usuario');;
			console.log(usuarioSessao);

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
			if(usuarioSessao == null)
			{
				window.sessionStorage.removeItem('usuario')

				irProLogin();
			}	
		};
	}; 

	app.ControladoraIndex = ControladoraIndex;
})(window, app, jQuery, toastr );