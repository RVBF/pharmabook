/**
 *  posologia.cfg.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(app)
	{
	'use strict';

	$(document ).ready(function()
	{
		var data = new app.Data();
		var servicoPosologia = new app.ServicoPosologia(data);
		var servicoMedicamentoPessoal = new app.ServicoMedicamentoPessoal(data);
		var controladoraEdicao = new app.ControladoraEdicao();

		var controladoraForm = new app.ControladoraFormPosologia(servicoPosologia, servicoMedicamentoPessoal, controladoraEdicao);
		controladoraForm.configurar();
		
		var controladoraListagem = new app.ControladoraListagemPosologia(servicoPosologia, servicoMedicamentoPessoal, controladoraForm, controladoraEdicao);
		controladoraListagem.configurar();

		// Inicia em modo de listagem
		controladoraEdicao.modoListagem(true);
	}); // ready
})(app);