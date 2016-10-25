/**
 *  index.cfg.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(app){
	'use strict';

	$(document).ready(function()
	{
		var redirecionar = new ControladoraIndex();
		redirecionar.verificar();
	} );
	
})(app);