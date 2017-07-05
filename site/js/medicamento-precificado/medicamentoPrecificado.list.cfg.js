/**
 *  medicamentoPrecificado.list.cfg.js
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
		var servicoFavorito = new app.ServicoFavorito();

		var controladoraListagemMedicamentoPrecificado = new app.ControladoraListagemMedicamentoPrecificado(
			servicoMedicamentoPrecificado,
			servicoUsuario,
			servicoMedicamento,
			servicoLaboratorio,
			servicoFarmacia,
			servicoFavorito
		);

		// controladoraListagemMedicamentoPrecificado.configurar();
	}); // ready
})(app, jQuery, document, window);