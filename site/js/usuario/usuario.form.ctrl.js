/**
 *  usuario.form.ctrl.js
 *  
 *  @author  Rafael Vinicius Barros Ferreira
 */
(function(window, app, $, toastr) 
{
	'use strict'; 
	 
	function ControladoraFormUsuario(servico) 
	{ // Model

		var _this = this;
	   var _modoAlteracao = true;

		_this.modoAlteracao = function modoAlteracao( b ) { // getter/setter
			if (b !== undefined)
			{
				_modoAlteracao = b;
			}

			return _modoAlteracao;
		};

		// Obtém o conteúdo atual do form como um objeto
		_this.conteudo = function conteudo()
		{
			return servico.criar(
				$('#id').val(),
				$('#nome').val(),
				$('#email').val(),
				$('#login').val(),
				$('#senha').val(),
				$('#confirmacao_senha').val()
			);
		};

		// Desenha o objeto no formulário
		_this.desenhar = function desenhar(obj)
		{
			$('#id').val(obj.id || 0);
			$('#nome').val(obj.nome || '');
			$('#email').val(obj.email || '');
			$('#login').val(obj.login || '');
			$('#senha').val(obj.senha);
			$('#confirmacao_senha').val(obj.confirmacaoSenha)
		};  

		_this.salvar = function salvar(event)
		{
			// Ao validar e tudo estiver correto, é disparado o método submitHandler(),
			// que é definido nas opções de validação.
			$("#usuario").validate(criarOpcoesValidacao());
		};

		_this.cancelar = function cancelar(event)
		{
			event.preventDefault();
			$('#usuario_form').modal('hide')
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
					"nome": {
						required	: true,
						rangelength : [ 6, 50 ],
					}

					// "email": {
					// 	required	: true,
					// 	email	: true,
					// 	rangelength : [ 6, 50 ],
					// },

					// "login": {
					// 	required	: true,
					// 	rangelength : [ 6, 50 ]
					// }, 

					// "senha": {
					// 	required	: true,
					// 	rangelength : [ 6, 50 ]
					// },				

					// "confirmacao_senha": {
					// 	equalTo : "#senha",
					// 	required	: true,
					// 	rangelength : [ 6, 50 ]
					// } 
				},

				messages: {
					"nome": {
						required	: "O campo login/email é obrigatório.",
						rangelength	: $.validator.format("A login/email deve ter entre {0} e {1} caracteres."),
					}

					// "email": {
					// 	required	: "O campo login/email é obrigatório.",
					// 	email : "Insira um e-mail valido.",
					// 	rangelength	: $.validator.format("O email deve ter entre {0} e {1} caracteres.")
					// },

					// "login": {
					// 	required	: "O campo login/email é obrigatório.",
					// 	rangelength	: $.validator.format("O login deve ter entre {0} e {1} caracteres."),
					// },					

					// "senha": {
					// 	required	: "O campo senha é obrigatório.",
					// 	rangelength	: $.validator.format("A senha deve ter entre {0} e {1} caracteres."),
					// },

					// "confirmacao_senha": {
					// 	required	: "O campo confirmação de senha é obrigatório.",
					// 	equalTo	: "O campo senha e confirmação de senha devem ser iguais.",
					// 	rangelength	: $.validator.format("A confirmação de senha deve ter entre {0} e {1} caracteres."),
					// }				
				}
			};
			console.log(opcoes);
			// Irá disparar quando a validação passar, após chamar o método validate().
			opcoes.submitHandler = function submitHandler(form) {
				console.log(form);
				// Habilita/desabilita os controles
				// var controlesHabilitados = function controlesHabilitados(b) {
				// 	$('#nome input').prop("disabled", !b);
				// 	$('#email input').prop("disabled", !b);
				// 	$('#login input').prop("disabled", !b);
				// 	$('#senha input').prop("disabled", !b);
				// 	$('#confirmacao_senha input').prop("disabled", !b);
				// 	$('#entrar button').prop("disabled", !b);
				// };
				// console.log(opcoes);

				// controlesHabilitados(false);
				
				// var sucesso = function sucesso(data, textStatus, jqXHR) {
				// 	toastr.success('Usuário Cadastrado com sucesso.');
				// };
				
				// var erro = function erro(jqXHR, textStatus, errorThrown) {
				// 	var mensagem = jqXHR.responseText;
				// 	$('#msg').append('<div class="error" >' + mensagem + '</div>');
				// };
				
				// var terminado = function() {
				// 	controlesHabilitados(true);
				// };

				// console.log('aqui');
				// var obj = _this.conteudo();
				// var jqXHR = servico.adicionar(obj);

				// jqXHR
				// 	.done(sucesso)
				// 	.fail(erro)
				// 	.always(terminado)
				// 	;	
			}; // submitHandler

			return opcoes;
		}; // criarOpcoesValidacao

		// Configura os eventos do formulário
		_this.configurar = function configurar(){
			$('#nome').focus(); // Coloca o foco no 1° input = nome;
			$("#usuario").submit(false);
			$('#salvar').click(_this.salvar);
			$('#cancelar').click(_this.cancelar);           
		};
	}; // ControladoraFormUsuario
	 
	// Registrando
	app.ControladoraFormUsuario = ControladoraFormUsuario;

})(window, app, jQuery, toastr);


