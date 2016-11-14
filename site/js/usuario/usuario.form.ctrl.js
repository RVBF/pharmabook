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
	   var _modal = $('.modal');

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
				_modal.find('#id').val(),
				_modal.find('#nome').val(),
				_modal.find('#sobrenome').val(),
				_modal.find('#email').val(),
				_modal.find('#login').val(),
				_modal.find('#senha').val(),
				_modal.find('#confirmacao_senha').val()
			);
		};

		// Desenha o objeto no formulário
		_this.desenhar = function desenhar(obj)
		{
			_modal.find('#id').val(obj.id || 0);
			_modal.find('#nome').val(obj.nome || '');
			_modal.find('#sobrenome').val(obj.nome || '');
			_modal.find('#email').val(obj.email || '');
			_modal.find('#login').val(obj.login || '');
		};  

		_this.salvar = function salvar(event)
		{
			// Ao validar e tudo estiver correto, é disparado o método submitHandler(),
			// que é definido nas opções de validação.
			_modal.find("#usuario").validate(criarOpcoesValidacao());
		};

		_this.cancelar = function cancelar(event)
		{
			event.preventDefault();
			_modal.find("#usuario").validate(criarOpcoesValidacao());
		};

		// Cria as opções de validação do formulário
		var criarOpcoesValidacao = function criarOpcoesValidacao(){	
			var opcoes = {
				focusInvalid: false,
				onkeyup: false,
				onfocusout: true,
				errorElement: "div",
				errorPlacement: function(error, element) {
					error.appendTo(".modal div#msg");
				}, 
				rules: {
					"nome": {
						required	: true,
						rangelength : [ 6, 50 ]
					},

					"email": {
						required	: true,
						email	: true,
						rangelength : [ 6, 50 ]
					},

					"login": {
						required	: true,
						rangelength : [ 6, 50 ]
					}, 

					"senha": {
						required	: true,
						rangelength : [ 6, 50 ]
					},				

					"confirmacao_senha": {
						equalTo : ".modal #senha"
					} 
				},

				messages: {
					"nome": {
						required	: "O campo login/email é obrigatório.",
						rangelength	: $.validator.format("A login/email deve ter entre {0} e {1} caracteres.")
					},

					"email": {
						required	: "O campo login/email é obrigatório.",
						email : "Insira um e-mail valido.",
						rangelength	: $.validator.format("O email deve ter entre {0} e {1} caracteres.")
					},

					"login": {
						required	: "O campo login/email é obrigatório.",
						rangelength	: $.validator.format("O login deve ter entre {0} e {1} caracteres.")
					},					

					"senha": {
						required	: "O campo senha é obrigatório.",
						rangelength	: $.validator.format("A senha deve ter entre {0} e {1} caracteres.")
					},

					"confirmacao_senha": {
						equalTo	: "O campo senha e confirmação de senha devem ser iguais."
					}				
				}
			};
			// Irá disparar quando a validação passar, após chamar o método validate().
			opcoes.submitHandler = function submitHandler(form) {
				//Habilita/desabilita os controles
				var controlesHabilitados = function controlesHabilitados(b) {
					_modal.find('#nome input').prop("disabled", !b);
					_modal.find('#email input').prop("disabled", !b);
					_modal.find('#login input').prop("disabled", !b);
					_modal.find('#senha input').prop("disabled", !b);
					_modal.find('#confirmacao_senha input').prop("disabled", !b);
					_modal.find('#entrar button').prop("disabled", !b);
				};

				controlesHabilitados(false);
				
				var sucesso = function sucesso(data, textStatus, jqXHR) {
					toastr.success('Usuário Cadastrado com sucesso.');
				};
				
				var erro = function erro(jqXHR, textStatus, errorThrown) {
					var mensagem = jqXHR.responseText;
					$('.modal #msg').append('<div class="error" >' + mensagem + '</div>');
				};
				
				var terminado = function() {
					controlesHabilitados(true);
				};

				var obj = _this.conteudo();
				var jqXHR = servico.adicionar(obj);

				jqXHR
					.done(sucesso)
					.fail(erro)
					.always(terminado)
					;	
			}; // submitHandler

			return opcoes;
		}; // criarOpcoesValidacao

		// Configura os eventos do formulário
		_this.configurar = function configurar(){
			_modal.find('#nome').focus(); // Coloca o foco no 1° input = nome;
			_modal.find("#usuario").submit(false);
			_modal.find('#salvar').click(_this.salvar);
			_modal.find('#cancelar').click(_this.cancelar);           
		};
	}; // ControladoraFormUsuario
	 
	// Registrando
	app.ControladoraFormUsuario = ControladoraFormUsuario;

})(window, app, jQuery, toastr);


