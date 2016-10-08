/**
 *  usuario.cfg.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(app)
	{
	'use strict';

	$(document ).ready(function()
	{
		var servico = new app.ServicoUsuario();
		var controladoraEdicao = new app.ControladoraEdicao();

		var controladoraForm = new app.ControladoraFormUsuario(servico, controladoraEdicao);
		controladoraForm.configurar();
				
		// Inicia em modo de listagem
		controladoraEdicao.modoListagem(true);
	}); // ready
})(app);