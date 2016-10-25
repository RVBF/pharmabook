/**
 *  index.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(window, app, $, toastr){
	'use strict';	
	function ControladoraIndex(){
		var _this = this;

		// Redireciona para o login
		var irProLogin = function irProLogin()
		{
			window.location.href = 'login.html';
		};
		
		var usuario = window.localStorage.getItem('usuario');
		_this.verificarExistenciaSessao = function()
		{
			
		}
		_this.verificar = function()
		{
			if(usuario == null)
			{
				irProLogin();
			}	
		}
	}; 

	app.ControladoraIndex = ControladoraIndex;


	console.log(app);


})(window, app, jQuery, toastr );