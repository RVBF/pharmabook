/**
 *  favorito.list.cfg.js
 *
 *  @author	Rafael
 */
(function(app, $, document, window)
{
	'use strict';
	$(document).ready(function()
	{
		var servicoFavorito = new app.ServicoFavorito();
		var servicoEndereco = new app.ServicoEndereco();

		var controladoraFavorito = new app.ControladoraListagemFavorito(servicoFavorito);
		controladoraFavorito.configurar();
	}); // ready
})(app, jQuery, document, window);