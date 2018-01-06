/**
 *  farmacia.list.cfg.js
 *
 *  @author	Rafael
 */
(function(app, $, document, window)
{
	'use strict';
	$(document).ready(function()
	{
		var servicoFarmacia = new app.ServicoFarmacia();
		var servicoEndereco = new app.ServicoEndereco();

		var controladoraFarmacia = new app.ControladoraListagemFarmacia(servicoFarmacia, servicoEndereco);
		controladoraFarmacia.configurar();
	}); // ready
})(app, jQuery, document, window);