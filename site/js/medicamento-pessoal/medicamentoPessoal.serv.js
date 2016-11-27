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
		preco,
		farmacia,
		medicamento,
		usuario,
		dataCriacao,
		dataAtualizacao
	) 
	{
		this.id = id;
		this.preco = preco;
		this.farmacia = farmacia;
		this.medicamento = medicamento;
		this.usuario = usuario;
		this.dataCriacao = dataCriacao;
		this.dataAtualizacao = dataAtualizacao;	
	};
	
	function ServicoMedicamentoPessoal(data)
	{ // Model
		var _this = this;
		// Rota no servidor
		_this.rota = function rota()
		{
			return app.API + '/medicamentos-pessoal';
		};

		// Cria um objeto de medicamento precificado
		this.criar = function criar(
			id,
			preco,
			farmacia,
			medicamento,
			usuario,
			dataCriacao,
			dataAtualizacao
		)
		{
 			return {
				id : id || 0,
				preco : preco || '',
				farmacia : farmacia || null,
				medicamento : medicamento || null,
				usuario : usuario || null,
				dataAtualizacao : data.getDataAtual() || '',
				dataCriacao : (id == 0) ? data.getDataAtual() : '' || ''
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
				type: "GET",
				url: _this.rota() + '/' + id
			});
		};	
	}; // ServicoMedicamentoPessoal
	
	// Registrando
	app.MedicamentoPessoal = MedicamentoPessoal;
	app.ServicoMedicamentoPessoal = ServicoMedicamentoPessoal;

})(app, $);