/**
 *  login.cfg.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(app) {
	'use strict';

	$(document ).ready(function() {
		
		var servico = new app.ServicoLogin();

		var controladoraLogin = new ControladoraLogin(servico);
		controladoraLogin.configurar();	
	}); // ready
})(app);