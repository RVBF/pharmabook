/**
 *  medicamentoPrecificado.form.cfg.js
 *
 *  @author	Rafael
 */
(function(app, $, document, window)
{
	'use strict';
	$(document).ready(function()
	{
		var servicoMedicamentoPrecificado = new app.ServicoMedicamentoPrecificado();
		var servicoUsuario = new app.ServicoUsuario();
		var servicoMedicamento = new app.ServicoMedicamento();
		var servicoLaboratorio = new app.ServicoLaboratorio();
		var servicoFarmacia = new app.ServicoFarmacia();

		var controladoraFormMedicamentoPrecificado = new app.ControladoraFormMedicamentoPrecificado(
			servicoMedicamentoPrecificado,
			servicoUsuario,
			servicoMedicamento,
			servicoLaboratorio,
			servicoFarmacia
		);

 		controladoraFormMedicamentoPrecificado.configurar();
	}); // ready
})(app, jQuery, document, window);