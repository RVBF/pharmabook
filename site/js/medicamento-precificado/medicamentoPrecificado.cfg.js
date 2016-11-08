/**
 *  medicamentoPrecificado.cfg.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(app)
	{
	'use strict';

	$(document ).ready(function()
	{
		var data = new app.Data();
		var servicoMedicamentoPrecificado = new app.ServicoMedicamentoPrecificado(data);
		var servicoUsuario = new app.ServicoUsuario();
		var servicoFarmacia = new app.ServicoFarmacia();
		var servicoMedicamento = new app.ServicoMedicamento();
		var controladoraEdicao = new app.ControladoraEdicao();

		var controladoraForm = new app.ControladoraFormMedicamentoPrecificado(
			servicoMedicamentoPrecificado,
			servicoUsuario,
			servicoMedicamento,
			servicoFarmacia,
			controladoraEdicao
		);
		
		//Configura os eventos do formulário
		controladoraForm.configurar();

		var controladoraListagem = new app.ControladoraListagemMedicamentoPrecificado(
			servicoMedicamentoPrecificado,
			servicoUsuario,
			servicoMedicamento,
			servicoFarmacia,
			controladoraForm,
			controladoraEdicao
		);

		//configura os eventos do modo de listagem
		controladoraListagem.configurar();

		// Inicia em modo de listagem
		controladoraEdicao.modoListagem(true);

	}); // ready
})(app);