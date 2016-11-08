/**
 *  laboratorio.serv.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
 (function(app, $)
 {
	'use strict';

	function Laboratorio(id, nome) 
	{
		this.id = id || 0; 
		this.nome = nome || ''; 
	};
	
	function ServicoLaboratorio()
	{ // Model
		var _this = this;
		
		// Cria um objeto de Laboratorio
		this.criar = function criar(id, nome) 
		{
 			return {id : id || 0,nome : nome || ''};
		};
	}; // ServicoLaboratorio
	
	// Registrando
	app.Laboratorio = Laboratorio;
	app.ServicoLaboratorio = ServicoLaboratorio;

})(app, $);