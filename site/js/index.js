/**
 *  index.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(window, app, $, toastr){
	'use strict';	

	function ControladoraIndex(){
		var _this = this;
		var usuario = window.sessionStorage.getItem('usuario');

		console.log(usuario);
		// Redireciona para o login
		var irProLogin = function irProLogin()
		{
			window.location.href = 'login.html';
		};
		

		_this.verificar = function()
		{
			if(usuario == null)
			{
				// irProLogin();
			}	
		}
	}; 
	
	
	$(document).ready(function()
	{
		var redirecionar = new ControladoraIndex();
		redirecionar.verificar();
	} ); 
	
})(window, app, jQuery, toastr );