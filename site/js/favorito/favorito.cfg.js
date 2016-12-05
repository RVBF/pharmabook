/**
 *  favorito.cfg.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(app)
	{
	'use strict';

	$(document ).ready(function()
	{
		var data = new app.Data();
		var servicoFavorito = new app.ServicoFavorito(data);
		var servicoMedicamentoPrecificado = new app.ServicoMedicamentoPrecificado(data);
		
		var controladoraEdicao = new app.ControladoraEdicao();

		var controladoraListagem = new app.ControladoraListagemFavorito(
			servicoFavorito,
			servicoMedicamentoPrecificado,
			controladoraEdicao
		);

		//configura os eventos do modo de listagem
		controladoraListagem.configurar();

		// Inicia em modo de listagem
		controladoraEdicao.modoListagem(true);

	}); // ready
})(app);