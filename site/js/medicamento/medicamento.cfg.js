/**
 *  medicamento.cfg.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(app)
	{
	'use strict';

	$(document ).ready(function()
	{
		var servico = new app.ServicoMedicamento();
		var controladoraEdicao = new app.ControladoraEdicao();
		
		var controladoraListagem = new app.ControladoraListagemMedicamento(servico, controladoraEdicao);
		controladoraListagem.configurar();
		
		// Inicia em modo de listagem
		controladoraEdicao.modoListagem(true);
	}); // ready
})(app);