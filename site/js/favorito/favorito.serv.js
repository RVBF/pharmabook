/**
 *  favorito.serv.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
 (function(app, $)
 {
	'use strict';

	function Favorito(
		id,
		medicamentoPrecificado
	) 
	{
		this.id = id;
		this.medicamentoPrecificado = medicamentoPrecificado;
	};
	
	function ServicoFavorito()
	{ // Model
		var _this = this;
		// Rota no servidor
		_this.rota = function rota()
		{
			return app.API + '/favorito';
		};

		// Cria um objeto de favorito
		this.criar = function criar(
			id,
			medicamentoPrecificado
		)
		{
 			return {
				id : id || 0,
				medicamentoPrecificado : medicamentoPrecificado || ''
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
	}; // ServicoFavorito
	
	// Registrando
	app.Favorito = Favorito;
	app.ServicoFavorito = ServicoFavorito;

})(app, $);