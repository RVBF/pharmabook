/**
 *  index.cfg.js
 *
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(app, $, window){
	'use strict';

	$(document).ready(function()
	{
		var servico = new app.ServicoIndex();
		var servicoSessao = new app.ServicoSessao();

		var index = new app.ControladoraIndex(servico, servicoSessao);

		// index.verificar();

		setTimeout(function()
		{
			index.verficarLogin();
		}, 600000);

		index.configurar();
	} );
})(app, jQuery, window);