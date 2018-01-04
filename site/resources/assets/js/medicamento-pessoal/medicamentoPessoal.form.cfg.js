/**
 *  medicamentoPessoal.form.cfg.js
 *
 *  @author	Rafael
 */
(function(app, $, document, window)
{
	'use strict';
	$(document).ready(function()
	{
		var servicoLaboratorio = new app.ServicoLaboratorio();
		var servicoMedicamento = new app.ServicoMedicamento();
		var servicoMedicamentoPessoal = new app.ServicoMedicamentoPessoal();

		var controladoraFormMedicamentoPessoal = new app.ControladoraFormMedicamentoPessoal(
			servicoLaboratorio,
			servicoMedicamento,
			servicoMedicamentoPessoal
 		);

 		controladoraFormMedicamentoPessoal.configurar();
	}); // ready
})(app, jQuery, document, window);