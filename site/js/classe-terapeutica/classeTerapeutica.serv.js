/**
 *  classeTerapeutica.serv.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
 (function(app, $)
 {
	'use strict';

	function ClasseTerapeutica(id, nome) 
	{
		this.id = id || 0; 
		this.nome = nome || ''; 
	};
	
	function ServicoClasseTerapeutica()
	{ // Model
		var _this = this;
		
		// Cria um objeto de ClasseTerapeutica
		this.criar = function criar(id, nome) 
		{
 			return {id : id || 0, nome : nome || ''};
		};
	}; // ServicoClasseTerapeutica
	
	// Registrando
	app.ClasseTerapeutica = ClasseTerapeutica;
	app.ServicoClasseTerapeutica = ServicoClasseTerapeutica;

})(app, $);