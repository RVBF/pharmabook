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

		var controladoraFormUsuario = new app.ControladoraFormUsuario(servicoUsuario, servicoLogout);

 		controladoraFormUsuario.configurar();
	}); // ready
})(app, jQuery, document, window);