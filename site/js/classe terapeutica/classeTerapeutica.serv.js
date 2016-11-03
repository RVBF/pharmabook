/**
 *  classeTerapeutica.serv.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
 (function(app, $)
 {
	'use strict';

	function ClaseTerapeutica(
		id,
		nome
	) 
	{
		this.id = id || 0; 
		this.nome = nome || ''; 
	};
	
	function ServicoClaseTerapeutica()
	{ // Model
		var _this = this;
		
		// Cria um objeto de ClaseTerapeutica
		this.criar = function criar(
			id,
			nome
		) 
		{
 			return {
				id : id || 0,
				nome : nome ||
			};
		};
	}; // ServicoClaseTerapeutica
	
	// Registrando
	app.ClaseTerapeutica = ClaseTerapeutica;
	app.ServicoClaseTerapeutica = ServicoClaseTerapeutica;

})(app, $);