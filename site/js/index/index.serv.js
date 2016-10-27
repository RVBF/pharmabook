/**
 *  index.serv.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
 (function(app, $)
 {
	'use strict';
	
	function ServicoIndex()
	{ 
	
		var _this = this;
	
		// Rota no servidor
		_this.rota = function rota(){
			return app.API + '/buscar-sessao';
		};

		_this.retornarSessao = function retornarSessao(){
			return $.ajax({
				type: "POST",
				url: _this.rota()
			} );
		};
	}; // ServicoIndex
	
	// Registrando
	app.ServicoIndex = ServicoIndex;

})(app, $);