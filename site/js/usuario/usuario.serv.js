/**
 *  usuario.serv.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
 (function(app, $)
 {
	'use strict';

	function Usuario(
		id,
		nome,
		sobrenome,
		email,
		login,
		senha,
		dataCriacao,
		dataAtualizacao
	) 
	{
		this.id = id || 0;
		this.nome = nome || '';
		this.sobrenome = sobrenome || '';
		this.email = email || '';
		this.login = login || '';
		this.senha = senha || '';
		this.dataCriacao = dataCriacao || '';
		this.dataAtualizacao = dataAtualizacao || '';	
	};

	function NovaSenha(
		senhaAtual,
		novaSenha,
		confirmacaoSenha,
		dataAtualizacao
	)
	{
		this.senhaAtual = senhaAtual;
		this.novaSenha = novaSenha;
		this.confirmacaoSenha = confirmacaoSenha;
		this.dataAtualizacao = dataAtualizacao;
	};
	
	function ServicoUsuario(data)
	{ // Model
		var _this = this;
		// Rota no servidor
		_this.rota = function rota()
		{
			return app.API + '/usuarios';
		};

		// Cria um objeto de usuario
		this.criar = function criar(id, nome, sobrenome, email, login, senha) 
		{
 			return {
				id : id || 0,
				nome : nome || '',
				sobrenome : sobrenome || '',
				email : email || '',
				login : login || '',
				senha : senha || '',
				dataCriacao : (id == 0) ? data.getDataAtual() : '' || '',
				dataAtualizacao : (id == 0) ? data.getDataAtual() : '' || ''
			};
		};
		
		_this.adicionar = function adicionar(obj)
		{
			return  $.ajax({
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

		_this.atualizarSenha = function atualizarSenha(obj)
		{
			return $.ajax({
				type: "POST",
				url: _this.rota()+'/nova-senha',
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

		_this.getUsuarioSessao = function getUsuarioSessao()
		{
			return $.ajax({
				type: "GET",
				url: _this.rota() + '/get-usuario-sessao'
			});
		};
		
		_this.comId = function comId(id)
		{
			return $.ajax({
				type: "GET",
				url: _this.rota() + '/' + id
			});
		};	
	}; // ServicoUsuario
	
	// Registrando
	app.Usuario = Usuario;
	app.NovaSenha = NovaSenha;
	app.ServicoUsuario = ServicoUsuario;

})(app, $);