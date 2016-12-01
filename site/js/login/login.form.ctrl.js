/**
 *  login.form.ctrl.js
 *  
 *  @author  Rafael Vinicius Barros Ferreira
 */
(function(window, app, $, toastr) 
{
	'use strict'; 
	 
	function ControladoraFormLogin(servico) 
	{ // Model

		var _this = this;
		var _modoAlteracao = true;

		var _formulario = $('#conteudo');

		// Redireciona para o index
		var irProIndex = function irProIndex() {
			window.location.href = 'index.html';
		};

		// Obtém o conteúdo atual do form como um objeto
		_this.conteudo = function conteudo()
		{
			return servico.criar($('#login').val(), $('#senha').val());
		};

		/*Envia os dados para o servidor e o coloca na sessão.*/
		_this.logar = function logar(event)
		{
			// Ao validar e tudo estiver correto, é disparado o método submitHandler(),
			// que é definido nas opções de validação.
			$("#form_login").validate(criarOpcoesValidacao());
		};

		_this.exibirSenha = function exibirSenha()
		{
			var senha_attr = $('#senha').attr('type');

			if(senha_attr != 'text')
			{

				$('.checkbox').addClass('show');
				$('#senha').attr('type', 'text');
			} 
			else
			{

				$('.checkbox').removeClass('show');
				$('#senha').attr('type', 'password');
			}
		};

		_this.carregarCadastroDeUsuario = function carregarCadastroDeUsuario()
		{
			_formulario.empty().load('usuario.html', '',_this.configurarModal);
		};

		_this.configurarModal = function configurarModal()
		{
			$('#usuario_modal').modal('show');
		};

		// Cria as opções de validação do formulário
		var criarOpcoesValidacao = function criarOpcoesValidacao(){	
			var opcoes = {
				focusInvalid: false,
				onkeyup: false,
				onfocusout: true,
				errorElement: "div",
				errorPlacement: function(error, element) {
					error.appendTo("div#msg");
				}, 
				rules: {
					"login": {
						required	: true,
						rangelength : [ 7, 50 ],
					},
					"senha": {
						required	: true,
						rangelength : [ 6, 50 ]
					} 
				},
				messages: {
					"login": {
						required	: "O campo login/email é obrigatório.",
						rangelength	: $.validator.format("A identificação deve ter entre {0} e {1} caracteres."),
					},
					"senha": {
						required	: "O campo senha é obrigatório.",
						rangelength	: $.validator.format("A Senha deve ter entre {0} e {1} caracteres.")
					} 
				}
			};

			// Irá disparar quando a validação passar, após chamar o método validate().
			opcoes.submitHandler = function submitHandler(form) {
				// Habilita/desabilita os controles
				var controlesHabilitados = function controlesHabilitados(b) {
					$('#login input').prop("disabled", !b);
					$('#senha input').prop("disabled", !b);
					$('#entrar').prop("disabled", !b);
				};
								
				var sucesso = function sucesso(data, textStatus, jqXHR) {
					window.sessionStorage.setItem('usuario', JSON.stringify(data));
					irProIndex();
					toastr.success('Login efetuado.');
				};
				
				var erro = function erro(jqXHR, textStatus, errorThrown) {
					var mensagem = jqXHR.responseText;
					$('#msg').append('<div class="error" >' + mensagem + '</div>');
				};
				
				var terminado = function() {
					controlesHabilitados(true);
				};
				
				var obj = _this.conteudo();
				var jqXHR = servico.logar(obj);

				jqXHR
					.done(sucesso)
					.fail(erro)
					.always(terminado)
					;	
			}; // submitHandler
			
			return opcoes;
		}; // criarOpcoesValidacao

		// Configura os eventos do formulário
		_this.configurar = function configurar() 
		{
			$('#login').focus(); // Coloca o foco no 1° input = nome;
			$("#form_login").submit(false);
			$('#entrar').click(_this.logar);			
			$('#cadastrar_usuario').click(_this.carregarCadastroDeUsuario);			
			$('.character-checkbox').on('click', _this.exibirSenha); 
		};
	}; // ControladoraFormLogin
	 
	// Registrando
	app.ControladoraFormLogin = ControladoraFormLogin;

})(window, app, jQuery, toastr);


