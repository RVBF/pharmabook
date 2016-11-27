/**
 *  posologia.serv.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
 (function(app, $)
 {
	'use strict';

	function Posologia(
		id,
		dose,
		unidadeMedida,
		descricao,
		administracao,
		periodicidade,
		tipoPeriodicidade
	) 
	{
		this.id = id;
		this.dose = dose;
		this.unidadeMedida = unidadeMedida;
		this.descricao = descricao;
		this.administracao = administracao;
		this.periodicidade = periodicidade;
		this.tipoPeriodicidade = tipoPeriodicidade;
	};
	
	function ServicoPosologia()
	{ // Model
		var _this = this;
		
		// rota para principio ativo
		_this.rota = function rota()
		{
			return app.API + '/posologias';
		};

		// Cria um objeto de Posologia
		this.criar = function criar(
			id,
			dose,
			unidadeMedida,
			descricao,
			administracao,
			periodicidade,
			tipoPeriodicidade
		)
		{
 			return {
				id : id || 0,
				dose : dose || '',
				unidadeMedida : unidadeMedida || '',
				descricao : descricao || '',
				administracao : administracao || '',
				periodicidade : periodicidade || '',
				tipoPeriodicidade : tipoPeriodicidade || ''	
			};
		};

		_this.getTiposDePeriodicidade = function getTiposDePeriodicidade() {
			return $.ajax({
				type: "GET",
				url: _this.rota() + "/tipos-periodicidade",
				dataType: "json",
			});
		};
	}; // ServicoPosologia
	
	// Registrando
	app.Posologia = Posologia;
	app.ServicoPosologia = ServicoPosologia;

})(app, $);