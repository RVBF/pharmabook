/**
 *  login.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(window, $, toastr) {
	'use strict';	
	
	var app = { API:'api' };

	function ServicoLogin() { // Model
		
		var _this = this;
	
		// Rota no servidor
		_this.rota = function rota()
		{
			return app.API + '/login';
		};

		// Cria um objeto de login
		_this.criar = function criar(login, senha)
		{
			return {
				login : login || '',
				senha : senha 		|| ''
			};
		};
		
		_this.logar = function logar(obj)
		{
			return $.ajax({
				type: "POST",
				url: _this.rota(),
				data: obj
			});
		};
	}; // ServicoLogin

	function ControladoraLogin(servico)
	{
		var _this = this;
		// Redireciona para o index
		var irProIndex = function irProIndex()
		{
			window.location.href = 'index.html';
		};

		// Obtém o conteúdo atual do form como um objeto
		_this.conteudo = function conteudo()
		{
			return servico.criar($('#login').val(), $('#senha').val());
		};
		
		_this.logar = function logar(event)
		{
			// Ao validar e tudo estiver correto, é disparado o método submitHandler(),
			// que é definido nas opções de validação.
			$("#login").validate(criarOpcoesValidacao());
		};

		// Cria as opções de validação do formulário
		var criarOpcoesValidacao = function criarOpcoesValidacao()
		{
			
			var opcoes = 
			{
				focusInvalid: false,
				onkeyup: false,
				onfocusout: true,
				errorElement: "div",
				
				errorPlacement: function(error, element){
					error.appendTo("div#msg");
				},

				rules:{
					"login": {
						required	: true,
						rangelength : [ 6, 50 ],
						regex		: /^([0-9]{6,7}|[A-Za-z0-9_.-])$/i
					},
					"senha": {
						required	: true,
						rangelength : [ 6, 50 ]
					} 
				},

				messages: {
					"login": {
						required	: "O campo login é obrigatório.",
						rangelength	: $.validator.format("A login deve ter entre {0} e {1} caracteres."),
						regex		: $.validator.addMethod("regex", function(value, element, regexp){
							var regex = new RegExp(regexp);
        					return this.optional(element) || regex.test(value);
    					},
    						"E-mail ou login inválidos."
						)	
					},
					"senha": {
						required	: "O campo senha é obrigatório.",
						rangelength	: $.validator.format("A Senha deve ter entre {0} e {1} caracteres.")
					} 
				}
			};
				
			// Irá disparar quando a validação passar, após chamar o método validate().
			opcoes.submitHandler = function submitHandler(form){
				
				// Habilita/desabilita os controles
				var controlesHabilitados = function controlesHabilitados(b)
				{
					$('#login input').prop("disabled", !b);
					$('#entrar').prop("disabled", !b);
				};
				
				controlesHabilitados(false);
				
				var sucesso = function sucesso(data, textStatus, jqXHR)
				{
					toastr.success('Login efetuado.');
					window.localStorage.setItem('logado', true);
					irProIndex();
				};
				
				var erro = function erro(jqXHR, textStatus, errorThrown){
					var mensagem = jqXHR.responseText;
					console.log(mensagem);

					$('#msg').append('<div class="error" >' + mensagem + '</div>');
				};
				
				var terminado = function()
				{
					controlesHabilitados(true);
				};
				
				var obj = _this.conteudo();

				var jqXHR = servico.logar(obj);

				jqXHR.done(sucesso).fail(erro).always(terminado);
			}; // submitHandler
			
			return opcoes;
		}; // criarOpcoesValidacao
		// Configura os eventos do formulário
		_this.configurar = function configurar()
		{
			console.log('entrei')
			$('#login').submit(false);
			$('#entrar').click(_this.logar);			
		};
	};

	app.ServicoLogin = ServicoLogin;
	app.ControladoraLogin = ControladoraLogin;
})(window, jQuery, toastr);