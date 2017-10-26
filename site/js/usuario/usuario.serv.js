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
		endereco
	)
	{
		this.id = id || 0;
		this.nome = nome || '';
		this.sobrenome = sobrenome || '';
		this.email = email || '';
		this.login = login || '';
		this.senha = senha || '';
		this.endereco= endereco  || undefined;
	};

	function ServicoUsuario()
	{ // Model
		var _this = this;
		// Rota no servidor
		_this.rota = function rota()
		{
			return app.API + '/usuarios';
		};

		// Cria um objeto de usuario
		this.criar = function criar(id, nome, sobrenome, email, login, senha, endereco)
		{
 			return {
				id : id || 0,
				nome : nome || '',
				sobrenome : sobrenome || '',
				email : email || '',
				login : login || '',
				senha : senha || '',
				endereco : endereco || ''
			};
		};

		this.alterarSenha = function alterarSenha(id, senhaAtual, novaSenha, confirmacaoSenha)
		{
 			return {
				id : id || 0,
				senhaAtual : senhaAtual || '',
				novaSenha : novaSenha || '',
				confirmacaoSenha : confirmacaoSenha || ''
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

		_this.novaSenha = function novaSenha(obj)
		{
			return  $.ajax({
				type: "POST",
				url: _this.rota() + '/nova-senha',
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
	app.ServicoUsuario = ServicoUsuario;

})(app, $);