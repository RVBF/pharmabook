/**
 *  login.cfg.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(app){
	'use strict';

	$(document ).ready(function()
	{
		var servico = new app.ServicoLogin();

		var controladoraForm = new app.ControladoraFormLogin(servico);
		
		controladoraForm.configurar();
	}); // ready
})(app);