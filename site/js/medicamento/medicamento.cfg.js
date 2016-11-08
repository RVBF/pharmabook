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
		var servicoMedicamento = new app.ServicoMedicamento();
		var servicoPrincipioAtivo = new app.ServicoPrincipioAtivo();
		var servicoClasseTerapeutica = new app.ServicoClasseTerapeutica();
		var servicoLaboratorio = new app.ServicoLaboratorio();

		var controladoraEdicao = new app.ControladoraEdicao();
		
		var controladoraListagem = new app.ControladoraListagemMedicamento(servicoMedicamento, servicoPrincipioAtivo, servicoClasseTerapeutica, servicoLaboratorio, controladoraEdicao);
		controladoraListagem.configurar();
		
		// Inicia em modo de listagem
		controladoraEdicao.modoListagem(true);
	}); // ready
})(app);