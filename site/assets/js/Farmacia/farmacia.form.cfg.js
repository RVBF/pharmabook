/**
 *  farmacia.form.cfg.js
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

		var controladoraFormFarmacia = new app.ControladoraFormFarmacia(servicoFarmacia, servicoEndereco)

 		controladoraFormFarmacia.configurar();
	}); // ready
})(app, jQuery, document, window);