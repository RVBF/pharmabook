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
		var servicoUsuario = new app.ServicoUsuario();

		var controladoraFormMedicamentoPessoal = new app.ControladoraFormMedicamentoPessoal(
			servicoLaboratorio,
			servicoMedicamento,
			servicoMedicamentoPessoal,
			servicoUsuario
 		);

 		controladoraFormMedicamentoPessoal.configurar();
	}); // ready
})(app, jQuery, document, window);