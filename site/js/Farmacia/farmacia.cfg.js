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
		var data = new app.Data();
		var servicoFarmacia = new app.ServicoFarmacia(data);
		var servicoEndereco = new app.ServicoEndereco(data);

		var controladoraForm = new app.ControladoraFormFarmacia(servicoFarmacia, servicoEndereco);
		controladoraForm.configurar();
		
		var controladoraListagem = new app.ControladoraListagemFarmacia(servicoFarmacia, servicoEndereco, controladoraForm);
		controladoraListagem.configurar();
	}); // ready
})(app);