/**
 *  farmacia.cfg.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(app)
	{
	'use strict';

	$(document ).ready(function()
	{
		var servico = new app.ServicoFarmacia();
		var controladoraEdicao = new app.ControladoraEdicao();

		var controladoraForm = new app.ControladoraFormFarmacia(servico, controladoraEdicao);
		controladoraForm.configurar();
		
		var controladoraListagem = new app.ControladoraListagemFarmacia(servico, controladoraEdicao, controladoraForm);
		controladoraListagem.configurar();
		
		// Inicia em modo de listagem
		controladoraEdicao.modoListagem(true);
	}); // ready
})(app);