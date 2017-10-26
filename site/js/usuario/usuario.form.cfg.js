/**
 *  usuario.form.cfg.js
 *
 *  @author	Rafael
 */
(function(app, $, document, window)
{
	'use strict';
	$(document).ready(function()
	{
		var servicoUsuario = new app.ServicoUsuario();
		var servicoLogout = new app.ServicoLogout();
		console.log('servicoLogout');
		var servicoEndereco = new app.ServicoEndereco();

		var controladoraFormUsuario = new app.ControladoraFormUsuario(servicoUsuario, servicoLogout, servicoEndereco);

 		controladoraFormUsuario.configurar();
	}); // ready
})(app, jQuery, document, window);