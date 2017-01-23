/**
 *  medicamento pessoal.serv.js
 *
 *  @author	Rafael Vinicius Barros Ferreira
 */
 (function(app, $)
 {
	'use strict';

	function MedicamentoPessoal(
		id,
		validade,
		quantidade,
		medicamento,
		tipoMedicamento
	)
	{
		this.id = id;
		this.validade = validade;
		this.quantidade = quantidade;
		this.medicamento = medicamento;
		this.tipoMedicamento = tipoMedicamento;
	};

	function ServicoMedicamentoPessoal(data)
	{ // Model
		var _this = this;
		// Rota no servidor
		_this.rota = function rota()
		{
			return app.API + '/medicamentos-pessoais';
		};

		// Cria um objeto de medicamento pessoal
		this.criar = function criar(
			id,
			validade,
			quantidade,
			medicamento,
			tipoMedicamento
		)
		{
 			return {
				id : id || 0,
				validade : validade || '',
				quantidade : quantidade || 0,
				medicamento : medicamento || '',
				tipoMedicamento : tipoMedicamento || ''
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

		_this.todos = function todos() {
			return $.ajax({
				type : "GET",
				url: _this.rota()
			});
		};

		_this.getAdministracaoesMedicamentos = function getAdministracaoesMedicamentos() {
			return $.ajax({
				type : "GET",
				url: _this.rota() +'/administracoes'
			});
		};

		_this.getMedicamentosFormas = function getMedicamentosFormas() {
			return $.ajax({
				type : "GET",
				url: _this.rota() +'/medicamentos-formas'
			});
		};

		_this.atualizar = function atualizar(obj)
		{
			return $.ajax({
				type: "PUT",
				url: _this.rota(),
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
				type: "GET",
				url: _this.rota() + '/' + id
			});
		};
	}; // ServicoMedicamentoPessoal

	// Registrando
	app.MedicamentoPessoal = MedicamentoPessoal;
	app.ServicoMedicamentoPessoal = ServicoMedicamentoPessoal;

})(app, $);