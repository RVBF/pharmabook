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
		var _modal = $('.modal');

		var _formulario = $('#conteudo');

		// Obtém o conteúdo atual do form como um objeto
		_this.conteudo = function conteudo()
		{
			return servico.criar($('#login').val(), $('#senha').val());
		};
		// Redireciona para o index
		var irParaOInicio = function irParaOInicio()
		{
		   window.location.href = atualizarCaminho('site/#/');
		};

		// Redireciona para o index
		_this.redirecionarParaCadastroDeUsuario = function redirecionarParaCadastroDeUsuario()
		{
			window.location.href = atualizarCaminho('site/#/usuario/cadastrar');
		};

		/*Envia os dados para o servidor e o coloca na sessão.*/
		_this.logar = function logar(event)
		{
			// Ao validar e tudo estiver correto, é disparado o método submitHandler(),
			// que é definido nas opções de validação.
			$("#form_login").validate(criarOpcoesValidacao());
		};

		// Cria as opções de validação do formulário
		var criarOpcoesValidacao = function criarOpcoesValidacao(){
			var opcoes = {
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
					desabilitarFormulario(!b);
				};

				var sucesso = function sucesso(data, textStatus, jqXHR)
				{
					window.sessionStorage.setItem('usuario', JSON.stringify(data));
					toastr.success('Login efetuado.');
					irParaOInicio();
				};

				var erro = function erro(jqXHR, textStatus, errorThrown) {
					var mensagem = jqXHR.responseText;
					$('#msg').empty().append('<div class="error" >' + mensagem + '</div>');
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
			$('#cadastrar_usuario').click(_this.redirecionarParaCadastroDeUsuario);
		};
	}; // ControladoraFormLogin

	// Registrando
	app.ControladoraFormLogin = ControladoraFormLogin;

})(window, app, jQuery, toastr);


