/**
 *  farmacia.serv.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
 (function(app, $)
 {
	'use strict';

	function Farmacia(
		id,
		nome,
		telefone,
		endereco,
		dataCriacao,
		dataAtualizacao
	) 
	{
		this.id = id || 0;
		this.nome = nome || '';
		this.telefone = telefone || '';
		this.endereco = endereco || '';
		this.dataCriacao = dataCriacao || '';
		this.dataAtualizacao = dataCriacao || '';
	};
	
	function ServicoFarmacia(data)
	{ // Model
		var _this = this;
		// Rota no servidor
		_this.rota = function rota()
		{
			return app.API + '/farmacias';
		};

		// Cria um objeto de farmacia
		this.criar = function criar(
			id,
			nome,
			telefone,
			endereco
		)
		{
 			return {
				id : id || 0,
				nome : nome || '',
				telefone : telefone || '',
				endereco : endereco || null,
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

		_this.pesquisarFarmacia = function pesquisarFarmacia(farmacia, medicamentoPrecificado)
		{
			return $.ajax({
				type: "POST",
				url: _this.rota()+"/pesquisar-farmacias",
				dataType: "json",
				data: {
					farmacia: farmacia || '',
					medicamentoPrecificado: medicamentoPrecificado || ''
				}
			});
		};
	}; // ServicoFarmacia
	
	// Registrando
	app.Farmacia = Farmacia;
	app.ServicoFarmacia = ServicoFarmacia;

})(app, $);