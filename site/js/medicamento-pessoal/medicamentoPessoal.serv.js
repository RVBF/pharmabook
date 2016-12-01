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
		medicamentoPrecificado,
		dataCriacao,
		dataAtualizacao,
		dataNovaCompra
	) 
	{
		this.id = id;
		this.validade = validade;
		this.quantidade = quantidade;
		this.medicamentoPrecificado = medicamentoPrecificado;
		this.dataCriacao = dataCriacao;
		this.dataAtualizacao = dataAtualizacao;
		this.dataNovaCompra = dataNovaCompra;
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
			medicamentoPrecificado,
			dataNovaCompra
		)
		{
 			return {
				id : id || 0,
				validade : validade || '',
				quantidade : quantidade || 0,
				medicamentoPrecificado : medicamentoPrecificado || '',
				dataAtualizacao : data.getDataAtual() || '',
				dataCriacao : (id == 0) ? data.getDataAtual() : '' || '',	
				dataNovaCompra : dataNovaCompra || ''
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
	app.ServicoMedicamentoPessoal = ServicoMedicamentoPessoal;
	app.ServicoMedicamentoPessoal = ServicoMedicamentoPessoal;

})(app, $);