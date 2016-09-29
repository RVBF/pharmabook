/**
 *  medicamento.cfg.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(app) {
	'use strict';

	$( document ).ready( function() {
		var servico = new app.ServicoMedicamento();
		var controladoraEdicao = new app.ControladoraEdicao();

		var controladoraForm = new app.ControladoraFormMedicamento(servico, controladoraEdicao);
		controladoraForm.configurar();
		
		var controladoraListagem = new app.ControladoraListagemMedicamento(servico, controladoraEdicao, controladoraForm);
		controladoraListagem.configurar();
		
		// Inicia em modo de listagem
		controladoraEdicao.modoListagem(true);
	}); // ready
})(app);