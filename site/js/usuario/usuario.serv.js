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
		email,
		login,
		senha,
		confirmacaoSenha
	) 
	{
		this.id = $id || 0; 
		this.nome = $nome || ''; 
		this.email = $email || ''; 
		this.login = $login || ''; 
		this.senha = $senha || ''; 
		this.confirmacaoSenha = $confirmacaoSenha || ''; 
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
		this.criar = function criar(
			id,
			nome,
			email,
			login,
			senha,
			confirmacaoSenha
		) 
		{
 			return {
				id : id || 0,
				nome : nome || '',
				email : email || '',
				login : login || '',
				senha : senha || '',
				confirmacaoSenha : confirmacaoSenha || ''
			};
		};
		
		_this.adicionar = function adicionar(obj)
		{
			console.log(obj);
			return $.ajax({
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
	}; // ServicoUsuario
	
	// Registrando
	app.Usuario = Usuario;
	app.ServicoUsuario = ServicoUsuario;

})(app, $);