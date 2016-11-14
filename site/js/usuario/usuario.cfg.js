/**
 *  usuario.cfg.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(app) {
	'use strict';
	$(document ).ready(function()
	{
		var data = new app.Data();
		var servico = new app.ServicoUsuario(data);

		var controladoraForm = new app.ControladoraFormUsuario(servico);

		controladoraForm.configurar();
	}); // ready
})(app);