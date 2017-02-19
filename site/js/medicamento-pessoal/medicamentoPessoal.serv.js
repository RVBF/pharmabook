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
		quantidadeRecipiente,
		quantidadeEstoque,
		administracao,
		tipoUnidade,
		medicamentoForma,
		medicamento
	)
	{
		this.id = id;
		this.validade = validade;
		this.quantidadeRecipiente = quantidadeRecipiente;
		this.quantidadeEstoque = quantidadeEstoque;
		this.administracao = administracao;
		this.tipoUnidade = tipoUnidade;
		this.medicamentoForma = medicamentoForma;
		this.medicamento = medicamento;
	};

	function ServicoMedicamentoPessoal()
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
			quantidadeRecipiente,
			quantidadeEstoque,
			administracao,
			tipoUnidade,
			medicamentoForma,
			medicamento
		)
		{
 			return {
				id : id || 0,
				validade : validade || '',
				quantidadeRecipiente : quantidadeRecipiente || 0,
				quantidadeEstoque : quantidadeEstoque || 0,
				administracao : administracao || '',
				tipoUnidade : tipoUnidade || '',
				medicamentoForma : medicamentoForma || '',
				medicamento : medicamento || ''
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

		_this.getMedicamentosFormas = function getMedicamentosFormas()
		{
			return $.ajax({
				type : "GET",
				url: _this.rota() +'/medicamentos-formas'
			});
		};

		_this.unidadesSolidas = function unidadesSolidas()
		{
			return $.ajax({
				type : "GET",
				url : _this.rota() + '/unidades-solidas'
			});
		}

		_this.unidadesLiquidas = function unidadesLiquidas()
		{
			return $.ajax({
				type : "GET",
				url : _this.rota() + '/unidades-liquidas'
			});
		}

		_this.unidadesInteiras = function unidadesInteiras()
		{
			return $.ajax({
				type : "GET",
				url : _this.rota() + '/unidades-inteiras'
			});
		}

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