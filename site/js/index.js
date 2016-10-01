/**
 *  index.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(window, app, $, toastr)
	{
	'use strict';	

	function ControladoraIndex()
	{
		var _this = this;

		// Redireciona para o login
		var irProLogin = function irProLogin()
		{
			window.location.href = 'login.html';
		};
		
		var permissao = window.localStorage.getItem('logado' );
		
		_this.verificar = function()
		{
			if(! permissao)
			{
				irProLogin();
			}	
		}
	}; 
	
	
	$(document ).ready(function()
	{
		var redirecionar = new ControladoraIndex();
		redirecionar.verificar();
	} ); 
	
})(window, app, jQuery, toastr );