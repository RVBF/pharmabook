/**
 *  posologia.list.cfg.js
 *
 *  @author	Rafael
 */
(function(app, $, document, window)
{
	'use strict';
	$(document).ready(function()
	{
		var servicoPosologia = new app.ServicoPosologia()

		var controladoraListagemPosologia = new app.ControladoraListagemPosologia(servicoPosologia);

		controladoraListagemPosologia.configurar();
	}); // ready
})(app, jQuery, document, window);