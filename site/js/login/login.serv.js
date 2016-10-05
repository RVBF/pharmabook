/**
 *  login.serv.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */

 var app = { API: 'api' };

 (function(app, $)
 {
	'use strict';

	function Login(
		login,
		senha
	) 
	{
		this.login = $login || ''; 
		this.senha = $senha || ''; 
	};
	
	function ServicoLogin()
	{ 
	
		var _this = this;
	
		// Rota no servidor
		_this.rota = function rota(){
			return app.API + '/login';
		};

		// Cria um objeto de login
		_this.criar = function criar(login, senha ) {
			return {
				login	: login || '',
				senha			: senha 		|| ''
			};
		};
		
		_this.logar = function logar(obj){
			return $.ajax({
				type: "POST",
				url: _this.rota(),
				data: obj
			} );
		};
	}; // ServicoLogin
	
	// Registrando
	app.Login = Login;
	app.ServicoLogin = ServicoLogin;

})(app, $);