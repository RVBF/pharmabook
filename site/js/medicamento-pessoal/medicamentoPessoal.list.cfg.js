/**
 *  medicamentoPessial.list.cfg.js
 *
 *  @author	Rafael
 */
(function(app, $, document, window)
{
	'use strict';
	$(document).ready(function()
	{
		var servicoMedicamentoPessoal = new app.ServicoMedicamentoPessoal();
		var controladoraListagemMedicamentoPessoal = new app.ControladoraListagemMedicamentoPessoal(servicoMedicamentoPessoal);
		controladoraListagemMedicamentoPessoal.configurar();
	}); // ready
})(app, jQuery, document, window);