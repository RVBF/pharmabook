/**
 *  index.cfg.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(app){
	'use strict';

	$(document).ready(function()
	{
		var servico = new app.ServicoIndex();
		
		var index = new app.ControladoraIndex(servico);

		index.verificar();
	} );
})(app);