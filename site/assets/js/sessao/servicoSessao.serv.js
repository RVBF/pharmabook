/**
 *  servicoSessao.serv.js
 *
 *  @author	Rafael Vinicius Barros Ferreira
 */

(function(window, app, $)
 {
	'use strict';

	function ServicoSessao()
	{

		var _this = this;

		var irParaLogin = function irParaLogin()
		{
			window.location.href = 'login.html';
		};

		_this.redirecionarParalogin = function redirecionarParalogin()
		{
			irParaLogin();
		};

		_this.adicionarUsuarioSessao = function adicionarUsuarioSessao(usuario)
		{
			window.sessionStorage.setItem('usuario', usuario);
		}

		_this.getSessao = function getSessao()
		{
			return window.sessionStorage.getItem('usuario');
		}

		_this.limparSessionStorage = function limparSessionStorage()
		{
			window.sessionStorage.clear();
		};

		// Rota no servidor
		_this.rota = function rota(){
			return app.API + '/sessao';
		};

		_this.verificarSessao = function verificarSessao()
		{
			return $.ajax({
				type: "POST",
				url: app.API + '/verificar-sessao'
			} );
		};
	}; // ServicoLogin

	// Registrando
	app.ServicoSessao = ServicoSessao;

})(window, app, jQuery);