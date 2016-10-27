/**
 *  farmacia.serv.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
 (function(app, $)
 {
	'use strict';

	function Endereco(
		id,
		logradouro,
		bairro,
		cidade,
		estado,
		numero,
		complemento,
		referencia,
		dataCriacao,
		dataAtualizacao
	) 
	{
		this.id = id || 0;
		this.logradouro = logradouro || '';
		this.bairro = bairro || '';
		this.cidade = cidade || '';
		this.estado = estado || '';
		this.numero = numero || '';
		this.complemento = complemento || '';
		this.referencia = referencia || '';
		this.dataCriacao = dataCriacao || '';
		this.dataAtualizacao = dataAtualizacao || '';	
	};
	
	function ServicoEndereco()
	{ // Model
		var _this = this;
		// Rota no servidor
		_this.rota = function rota()
		{
			return app.API + '/enderecos';
		};

		// Cria um objeto de Endereco
		this.criar = function criar(
			id,
			logradouro,
			bairro,
			cidade,
			estado,
			numero,
			complemento,
			referencia,
			dataCriacao,
			dataAtualizacao
		)
		{
 			return {
				id : id || 0,
				logradouro : logradouro || '',
				bairro : bairro || '',
				cidade : cidade || '',
				estado : estado || '',
				numero : numero || '',
				complemento : complemento || '',
				referencia : referencia || '',
				dataCriacao : dataCriacao || '',
				dataAtualizacao : dataAtualizacao || ''
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
	}; // ServicoEndereco
	
	// Registrando
	app.Endereco = Endereco;
	app.ServicoEndereco = ServicoEndereco;

})(app, $);