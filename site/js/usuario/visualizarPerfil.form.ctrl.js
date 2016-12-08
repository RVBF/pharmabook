/**
 *  usuario.form.ctrl.js
 *  
 *  @author  Rafael Vinicius Barros Ferreira
 */
(function(window, app, $, toastr) 
{
	'use strict'; 
	 
	function ControladoraVisualizacaoFormUsuario(servicoUsuario)
	{ // Model

		var _this = this;
		var _modoAlteracao = true;
		var _modoAlteracaoDeSenha = false;
		var _modal = $('.modal');

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
					}
				}
			};
			// Irá disparar quando a validação passar, após chamar o método validate().
			opcoes.submitHandler = function submitHandler(form) {
				//Habilita/desabilita os controles
				var controlesHabilitados = function controlesHabilitados(b) {
					$('#usuario input').prop("disabled", !b);
					$('#salvar').prop("disabled", !b);
					$('#cancelar').prop("disabled", !b);
				};

				controlesHabilitados(false);
				
				var sucesso = function sucesso(data, textStatus, jqXHR) {
					
					toastr.success('O usuário foi atualizado com sucesso.');

					renderizarModoVisualizacao();
				};
				
				var erro = function erro(jqXHR, textStatus, errorThrown) {
					var mensagem = jqXHR.responseText;
					$('.modal #msg').append('<div class="error" >' + mensagem + '</div>');
				};
				
				var terminado = function() {
					controlesHabilitados(true);
				};

				var obj = _this.conteudo();
				var jqXHR = servicoUsuario.atualizar(obj);

				jqXHR
					.done(sucesso)
					.fail(erro)
					;	
			}; // submitHandler

			return opcoes;
		}; // criarOpcoesValidacao

		var encerrarModal = function encerrarModal()
		{
			_modal.modal('hide');

			_modal.on('hidden.bs.modal', function(){
				$(this).find('#usuario')[0].reset();			
			});
		};

		var renderizarModoVisualizacao =  function renderizarModoVisualizacao()
		{
			$('#usuario input').prop("disabled", true);
			$('.modal .modal-footer').empty();
			$('.modal .modal-title').html('Visualizar Usuário');
			$('.modal .modal-footer').append('<button class="btn btn-primary" id="alterar_senha">Alterar Senha</button>');
			$('.modal .modal-footer').append('<button class="btn btn-success" id="alterar">Alterar</button>');
			$('.modal .modal-footer').append('<button class="btn btn-danger" id="remover">Remover</button>');
			$('.modal .modal-footer').append('<button class="btn btn-info" id="cancelar">Cancelar</button>');

			if($("#alterar_perfil").hasClass('hide'))
			{
				$("#alterar_perfil").removeClass('hide');
				$("#div_alterar_senha").addClass('hide');
			}

			if(_this.modoAlteracaoDeSenha())
			{
				_this.modoAlteracaoDeSenha(false);
			}
		};

		var renderizarModoEdicao =  function renderizarModoEdicao()
		{
			$('#usuario input').prop("disabled", false);
			$('.modal .modal-footer').empty();
			$('.modal .modal-title').html('Editar Usuário');
			$('.modal .modal-footer').append('<button class="btn btn-success" id="salvar">Salvar</button>');
			$('.modal .modal-footer').append('<button class="btn btn-danger" id="cancelar">Cancelar</button>');

			if(_this.modoAlteracaoDeSenha())
			{
				_this.modoAlteracaoDeSenha(false);
			}
		};

		var renderizarModoAlteracaoDeSenha = function renderizarModoAlteracaoDeSenha()
		{
			$('#usuario input').prop("disabled", false);
			$('.modal .modal-footer').empty();
			$('.modal .modal-title').html('Alterar senha do Usuário');
			$('.modal .modal-footer').append('<button class="btn btn-success" id="salvar">Salvar</button>');
			$('.modal .modal-footer').append('<button class="btn btn-danger" id="cancelar">Cancelar</button>');

			if($("#div_alterar_senha").hasClass('hide'))
			{
				$("#div_alterar_senha").removeClass('hide');
				$("#alterar_perfil").addClass('hide');
			}

			if(_this.modoAlteracaoDeSenha() == false)
			{
				_this.modoAlteracaoDeSenha(true);
			}
		};

		_this.modoAlteracao = function modoAlteracao( b ) { // getter/setter
			if (b !== undefined)
			{
				_modoAlteracao = b;
			}

			return _modoAlteracao;
		};		

		_this.modoAlteracaoDeSenha = function modoAlteracaoDeSenha( b ) { // getter/setter
			if (b !== undefined)
			{
				_modoAlteracaoDeSenha = b;
			}

			return _modoAlteracaoDeSenha;
		};

		// Obtém o conteúdo atual do form como um objeto
		_this.conteudo = function conteudo()
		{
			return servicoUsuario.criar(
				_modal.find('#id').val(),
				_modal.find('#nome').val(),
				_modal.find('#sobrenome').val(),
				_modal.find('#email').val(),
				_modal.find('#login').val()
			);
		};

		// Desenha o objeto no formulário
		_this.desenhar = function desenhar(obj)
		{
			renderizarModoVisualizacao();

			_modal.find('#id').val(obj.id || 0);
			_modal.find('#nome').val(obj.nome || '');
			_modal.find('#sobrenome').val(obj.sobrenome || '');
			_modal.find('#email').val(obj.email || '');
			_modal.find('#login').val(obj.login || '');
		};  

		_this.salvar = function salvar(event)
		{
			// Ao validar e tudo estiver correto, é disparado o método submitHandler(),
			// que é definido nas opções de validação.

			$("#usuario").validate(criarOpcoesValidacao());
		};

		_this.cancelar = function cancelar(event) {
			event.preventDefault();

			if(_this.modoAlteracaoDeSenha())
			{
				renderizarModoVisualizacao();
			}
			else
			{
				encerrarModal();
			}
		};

		_this.alterar = function alterar(event){
			event.preventDefault();
			renderizarModoEdicao();
		};			

		_this.visualizar = function visualizar(event){
			event.preventDefault();
			renderizarModoVisualizacao();
		};	

		_this.alterarSenha = function alterarSenha(){	
			event.preventDefault();
			renderizarModoAlteracaoDeSenha();
		};

		// Configura os eventos do formulário
		_this.configurar = function configurar(){

			$("#usuario").focus('nome');

			$('.modal').find(" #farmacia_form").submit(false);
			$('.modal').find('.modal-footer').on('click', '#cancelar', _this.cancelar);
			$('.modal').find('.modal-footer').on('click', '#cadastrar', _this.salvar);
			$('.modal').find('.modal-footer').on('click', '#salvar', _this.salvar);
			$('.modal').find('.modal-footer').on('click', '#alterar', _this.alterar);
			$('.modal').find('.modal-footer').on('click', '#alterar_senha', _this.alterarSenha);
			$('.modal').find('.modal-footer').on('click', '#remover', _this.remover);
			$('.modal').find('.modal-footer').on('click', '#visualizar', _this.visualizar);     
		};
	}; // ControladoraVisualizacaoFormUsuario
	 
	// Registrando
	app.ControladoraVisualizacaoFormUsuario = ControladoraVisualizacaoFormUsuario;

})(window, app, jQuery, toastr);


