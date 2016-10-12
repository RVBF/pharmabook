/**
 *  usuario.cfg.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(app) {
	'use strict';
	$(document ).ready(function()
	{
		var servico = new app.ServicoUsuario();

		var controladoraForm = new app.ControladoraFormUsuario(servico);

		controladoraForm.configurar();
	}); // ready
})(app);