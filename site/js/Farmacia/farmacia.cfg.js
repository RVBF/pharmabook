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
		console.log(app);
		var servicoFarmacia = new app.ServicoFarmacia();
		var servicoEndereco = new app.ServicoEndereco();
		var controladoraEdicao = new app.ControladoraEdicao();

		var controladoraForm = new app.ControladoraFormFarmacia(servicoFarmacia, servicoEndereco, controladoraEdicao);
		controladoraForm.configurar();
		
		var controladoraListagem = new app.ControladoraListagemFarmacia(servicoFarmacia, servicoEndereco, controladoraEdicao, controladoraForm);
		controladoraListagem.configurar();
		
		// Inicia em modo de listagem
		controladoraEdicao.modoListagem(true);
	}); // ready
})(app);