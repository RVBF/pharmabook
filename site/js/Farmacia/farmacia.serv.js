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
		endereco
	) 
	{
		this.id = id || 0;
		this.nome = nome || '';
		this.telefone = telefone || '';
		this.endereco = endereco || '';
	};
	
	function ServicoFarmacia()
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
				endereco : endereco || null
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

		_this.todos = function todos(id) {
			return $.ajax({
				type : "GET",
				url: _this.rota(id)				
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
	}; // ServicoFarmacia
	
	// Registrando
	app.Farmacia = Farmacia;
	app.ServicoFarmacia = ServicoFarmacia;

})(app, $);