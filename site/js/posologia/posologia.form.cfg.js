/**
 *  posologia.form.cfg.js
 *
 *  @author	Rafael
 */
(function(app, $, document, window)
{
	'use strict';
	$(document).ready(function()
	{
		var servicoPosologia = new app.ServicoPosologia();
		var servicoMedicamentoPessoal = new app.ServicoMedicamentoPessoal();

		var controladoraFormPosologia = new app.ControladoraFormPosologia(servicoPosologia, servicoMedicamentoPessoal);

 		controladoraFormPosologia.configurar();
	}); // ready
})(app, jQuery, document, window);