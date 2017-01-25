/**
 *  medicamentoPessoal.cfg.js
 *
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(app)
	{
	'use strict';

	$(document ).ready(function()
	{
		var servicoMedicamentoPessoal = new app.ServicoMedicamentoPessoal();
		var servicoMedicamento = new app.ServicoMedicamento();
		var servicoLaboratorio = new app.ServicoLaboratorio();

		var controladoraEdicao 	= new app.ControladoraEdicao();

		var controladoraForm = new app.ControladoraFormMedicamentoPessoal(
			servicoLaboratorio,
			servicoMedicamento,
			servicoMedicamentoPessoal,
			controladoraEdicao
		);

		//Configura os eventos do formulário
		controladoraForm.configurar();

		var controladoraListagem = new app.ControladoraListagemMedicamentoPessoal(
			servicoMedicamentoPessoal,
			controladoraForm,
			controladoraEdicao
		);

		//configura os eventos do modo de listagem
		controladoraListagem.configurar();

		// Inicia em modo de listagem
		controladoraEdicao.modoListagem(true);

	}); // ready
})(app);