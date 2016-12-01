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
		descricao,
		administracao,
		periodicidade,
		tipoUnidadeDose,
		tipoPeriodicidade
	) 
	{
		this.id = id;
		this.dose = dose;
		this.descricao = descricao;
		this.administracao = administracao;
		this.periodicidade = periodicidade;
		this.tipoUnidadeDose = tipoUnidadeDose;
		this.tipoPeriodicidade = tipoPeriodicidade;
		this.medicamentoPessoal = medicamentoPessoal;
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
			descricao,
			administracao,
			periodicidade,
			tipoUnidadeDose,
			tipoPeriodicidade,
			medicamentoPessoal
		) 
		{
 			return {
				id : id || 0,
				dose : dose || 0,
				descricao : descricao || '',
				administracao : administracao || '',
				periodicidade : periodicidade || '',
				tipoUnidadeDose : tipoUnidadeDose || '',
				tipoPeriodicidade : tipoPeriodicidade || '',
				medicamentoPessoal : medicamentoPessoal || ''
			};
		};

		_this.getTiposDePeriodicidade = function getTiposDePeriodicidade() {
			return $.ajax({
				type: "GET",
				url: _this.rota() + "/tipos-periodicidades",
				dataType: "json",
			});
		};		

		_this.getTiposDeUnidade = function getTiposDeUnidade() {
			return $.ajax({
				type: "GET",
				url: _this.rota() + "/tipos-unidades",
				dataType: "json",
			});
		};		

		_this.getTiposDeAdministracao = function getTiposDeAdministracao() {
			return $.ajax({
				type: "GET",
				url: _this.rota() + "/tipos-administracoes",
				dataType: "json",
			});
		};

		_this.adicionar = function adicionar(obj)
		{
			return $.ajax({
				type: "POST",
				url: _this.rota(),
				data: obj
			});
		};

		_this.comId = function comId(id)
		{
			return $.ajax({
				type: "GET",
				url: _this.rota() + '/' + id,
				async: false
			});
		};
	}; // ServicoPosologia
	
	// Registrando
	app.Posologia = Posologia;
	app.ServicoPosologia = ServicoPosologia;

})(app, $);