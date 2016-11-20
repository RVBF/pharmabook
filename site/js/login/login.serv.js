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
		identificador,
		senha
	) 
	{
		this.identificador = identificador || ''; 
		this.senha = senha || ''; 
	};
	
	function ServicoLogin()
	{ 
	
		var _this = this;
	
		// Rota no servidor
		_this.rota = function rota(){
			return app.API + '/login';
		};

		// Cria um objeto de login
		_this.criar = function criar(identificador, senha ) {
			return { identificador: identificador || '',  senha: senha 		|| ''};
		};
		
		_this.logar = function logar(obj){
			return $.ajax({
				type: "POST",
				url: _this.rota(),
				data: obj
			} );
		};

		this.verificarSessaoAtiva = function verificarSessaoAtiva()
		{
			return $.ajax({
				type: "GET",
				url: _this.rota()+"/verificar-sessao"
			} );
		}
	}; // ServicoLogin
	
	// Registrando
	app.Login = Login;
	app.ServicoLogin = ServicoLogin;

})(app, $);