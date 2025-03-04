/**
 *  principioAtivo.serv.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
 (function(app, $)
 {
	'use strict';

	function PrincipioAtivo(id, nome) 
	{
		this.id = id || 0; 
		this.nome = nome || ''; 
	};
	
	function ServicoPrincipioAtivo()
	{ // Model
		var _this = this;
				
		// rota para principio ativo
		_this.rota = function rota()
		{
			return app.API + '/principio-ativo';
		};

		// Cria um objeto de PrincipioAtivo
		this.criar = function criar(id, nome) 
		{
 			return {id : id || 0, nome : nome || ''};
		};
	}; // ServicoPrincipioAtivo
	
	// Registrando
	app.PrincipioAtivo = PrincipioAtivo;
	app.ServicoPrincipioAtivo = ServicoPrincipioAtivo;

})(app, $);