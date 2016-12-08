/**
 *  visualizarPerfil.cfg.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(app) {
	console.log(app);
	'use strict';
	$(document ).ready(function()
	{
		var data = new app.Data();
		var servicoUsuario = new app.ServicoUsuario(data);

		var controladoraForm = new app.ControladoraVisualizacaoFormUsuario(servicoUsuario);
		
		var sucesso = function sucesso(data, textStatus, jqXHR)
		{
			controladoraForm.desenhar(data);
			controladoraForm.configurar();
		};

		var erro = function erro()
		{
			var mensagem = jqXHR.responseText || 'Erro ao popular select de farmácias.';
			toastr.error( mensagem );
		};

		var jqXHR = servicoUsuario.getUsuarioSessao(); 
		
		jqXHR.fail(erro).done(sucesso);
		
	}); // ready
})(app);