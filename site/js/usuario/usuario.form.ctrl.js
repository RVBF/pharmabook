/**
 *  usuario.form.ctrl.js
 *  
 *  @author  Rafael Vinicius Barros Ferreira
 */
(function(window, app, $, toastr) 
{
	'use strict'; 
	 
	function ControladoraFormUsuario(servicoUsuario, servicoLogin) 
	{ // Model

		var _this = this;
	   var _modoAlteracao = true;
	   var _modal = $('.modal');

		var encerrarModal = function encerrarModal()
		{
			_modal.modal('hide');

			_modal.on('hidden.bs.modal', function(){
				$(this).find('#usuario')[0].reset();			
			});
		};

		var irProIndex = function irProIndex() {
			window.location.href = 'index.html';
		};
		
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
			return servicoUsuario.criar(
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
						rangelength : [ 2, 100 ]
					},			

					"sobrenome": {
						required	: true,
						rangelength : [ 2, 100 ]
					},

					"email": {
						required	: true,
						email	: true
					},

					"login": {
						required	: true,
						rangelength : [ 5, 30 ]
					}, 

					"senha": {
						required	: true,
						rangelength : [ 8, 50 ]
					},				

					"confirmacao_senha": {
						equalTo : ".modal #senha"
					} 
				},

				messages: {
					"nome": {
						required	: "O campo nome é obrigatório.",
						rangelength	: $.validator.format("A login/email deve ter entre {0} e {1} caracteres.")
					},					

					"sobrenome": {
						required	: "O campo sobrenome é obrigatório.",
						rangelength	: $.validator.format("A login/email deve ter entre {0} e {1} caracteres.")
					},

					"email": {
						required	: "O campo e-mail é obrigatório.",
						email : "Insira um e-mail valido.",
					},

					"login": {
						required	: "O campo login é obrigatório.",
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
					
					encerrarModal();

					toastr.success('Usuário Cadastrado com sucesso.');
					
					var sucesso = function sucesso()
					{
						var erro  = function erro()
						{
							var mensagem = jqXHR.responseText;
							$('.modal #msg').append('<div class="error" >' + mensagem + '</div>');					
						};

						var sucesso = function sucesso()
						{
							irProIndex();
						}

						var servicoSessao = new app.ServicoSessao();

						servicoSessao.adicionarUsuarioSessao(JSON.stringify(data))

						var jqXHR = servicoSessao.verificarSessao();

						jqXHR.done(sucesso).fail(erro);	

						toastr.success('Usuário Logado com sucesso.');
					};

					var erro = function erro()
					{
						toastr.success('Erro ao logar no sistema.');
					};

					var login = servicoLogin.criar(data.login, data.senha);

					var jqXHR = servicoLogin.logar(login);
					
					jqXHR.done(sucesso).fail(erro);	
				};
				
				var erro = function erro(jqXHR, textStatus, errorThrown) {
					var mensagem = jqXHR.responseText;
					$('.modal #msg').append('<div class="error" >' + mensagem + '</div>');
				};
				
				var terminado = function() {
					controlesHabilitados(true);
				};

				var obj = _this.conteudo();
				var jqXHR = servicoUsuario.adicionar(obj);

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


