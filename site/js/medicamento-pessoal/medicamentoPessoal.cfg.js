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
		var data = new app.Data();
		var servicoMedicamentoPrecificado = new app.ServicoMedicamentoPrecificado(data);
		var servicoUsuario = new app.ServicoUsuario();
		var servicoPosologia = new app.ServicoPosologia(data);
		var servicoMedicamentoPessoal = new app.ServicoMedicamentoPessoal(data);
		
		var controladoraEdicao = new app.ControladoraEdicao();

		var controladoraForm = new app.ControladoraFormMedicamentoPrecificado(
			servicoMedicamentoPrecificado,
			servicoUsuario,
			servicoMedicamentoPessoal,
			servicoPosologia,
			controladoraEdicao
		);
		
		//Configura os eventos do formulário
		controladoraForm.configurar();

		var controladoraListagem = new app.ControladoraListagemMedicamentoPessoal(
			servicoMedicamentoPrecificado,
			servicoUsuario,
			servicoMedicamentoPessoal,
			servicoPosologia,
			controladoraForm,
			controladoraEdicao
		);

		//configura os eventos do modo de listagem
		controladoraListagem.configurar();

		// Inicia em modo de listagem
		controladoraEdicao.modoListagem(true);

	}); // ready
})(app);