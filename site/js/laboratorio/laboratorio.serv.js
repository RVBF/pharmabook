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
			// rota para principio ativo
		_this.rota = function rota()
		{
			return app.API + '/laboratorios';
		};

		// Cria um objeto de PrincipioAtivo
		this.criar = function criar(id, nome) 
		{
 			return {id : id || 0, nome : nome || ''};
		};

		_this.pesquisarLaboratorio = function pesquisarLaboratorio(laboratorio, medicamento) {
			return $.ajax({
				type: "POST",
				url: _this.rota()+"/pesquisar-laboratorio",
				dataType: "json",
				data: {
					laboratorio: laboratorio || '',
					medicamento: medicamento || ''
				}
			});
		};
	}; // ServicoLaboratorio
	
	// Registrando
	app.Laboratorio = Laboratorio;
	app.ServicoLaboratorio = ServicoLaboratorio;

})(app, $);