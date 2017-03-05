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
		periodicidade,
		tipoPeriodicidade,
		medicamentoPessoal
	)
	{
		this.id = id;
		this.dose = dose;
		this.descricao = descricao;
		this.periodicidade = periodicidade;
		this.tipoPeriodicidade = tipoPeriodicidade;
		this.medicamentoPessoal = medicamentoPessoal
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
			periodicidade,
			tipoPeriodicidade,
			medicamentoPessoal
		)
		{
 			return {
				id : id || 0,
				dose : dose || 0,
				descricao : descricao || '',
				periodicidade : periodicidade || 0,
				tipoPeriodicidade : tipoPeriodicidade || '',
				medicamentoPessoal : medicamentoPessoal || ''
			};
		};

		_this.adicionar = function adicionar(obj)
		{
			return $.ajax({
				type: "POST",
				url: _this.rota(),
				data: obj
			});
		};

		_this.atualizar = function atualizar(obj)
		{
			return $.ajax({
				type: "PUT",
				url: _this.rota() + '/' + obj.id,
				data: obj
			});
		};

		_this.remover = function remover(id)
		{
			return $.ajax({
				type: "DELETE",
				url: _this.rota() + '/' + id
			});
		};

		_this.comId = function comId(id)
		{
			return $.ajax({
				type: 'GET',
				url: _this.rota() + '/' + id,
				async: false
			});
		};

		_this.tempoUnidades = function tempoUnidades()
		{
			return $.ajax({
				type: 'GET',
				url: _this.rota() + '/tempo-unidades',
				dataType: 'json',
			});
		};
	}; // ServicoPosologia

	// Registrando
	app.Posologia = Posologia;
	app.ServicoPosologia = ServicoPosologia;

})(app, $);