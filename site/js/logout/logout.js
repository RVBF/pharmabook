/**
 *  logout.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
( function( window, app, $, toastr ) {
	'use strict';	
	
	function ServicoLogout() { // Model
	
		var _this = this;
	
		// Rota no servidor
		_this.rota = function rota() {
			return app.API + '/logout';
		};
		
		_this.sair = function sair() {
			return $.ajax( {
				type: "DELETE", 
				url:_this.rota()
			} );
		};
				
	}; 
	function ControladoraLogout( servico ) {
		
		var _this = this;

		// Redireciona para o login
		var irProLogin = function irProLogin() {
			window.location.href = 'login.html';
		};
		
		_this.sair = function sair( event ) {
			
			var sucesso = function sucesso( data, textStatus, jqXHR ) {
				toastr.success( 'Logout efetuado!' );
				window.sessionStorage.clear();
				irProLogin();
			};
			
			var erro = function erro( jqXHR, textStatus, errorThrown ) {
				var mensagem = jqXHR.responseText || 'Ocorreu um erro ao tentar sair.';
				toastr.error( mensagem );
			};

			var jqXHR = servico.sair();

				jqXHR
					.done( sucesso )
					.fail( erro )
					;
		}
			
		_this.configurar = function configurar() {
			$( '#sair' ).click( _this.sair );
		};
		
	}; // ControladoraLogout
	
	$( document ).ready( function() {

		var servico = new ServicoLogout();
		
		var controladoraLogout = new ControladoraLogout( servico );
		controladoraLogout.configurar();	
	} );	
} )( window, app, jQuery, toastr );