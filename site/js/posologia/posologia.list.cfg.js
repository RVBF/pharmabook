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
		var servicoMedicamentoPessoal = new app.ServicoMedicamentoPessoal()

		var controladoraPosologia = new app.ControladoraListagemPosologia(
			servicoPosologia,
			servicoMedicamentoPessoal
		);

		controladoraPosologia.configurar();
	}); // ready
})(app, jQuery, document, window);