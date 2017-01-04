/**
 *  usuario.cfg.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(app) {
	'use strict';
	$(document ).ready(function()
	{
		console.log(app);
		var data = new app.Data();
		var servicoUsuario = new app.ServicoUsuario(data);
		var servicoLogin = new app.ServicoLogin();

		var controladoraForm = new app.ControladoraFormUsuario(servicoUsuario, servicoLogin);

		controladoraForm.configurar();
	}); // ready
})(app);