/**
 *  visualizarPefil.form.ctrl.js
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
	   var _modoVisualizacao = true;

		var renderizarModoEdicao =  function renderizarModoEdicao()
		{
			$('#usuario input').prop("disabled", false);
			$('.botoes_edicao_usuario').empty();
			$('.titulo_editar_perfil h2').html('Editar Usuário');
			$('#usuario .botoes_edicao_usuario').append('<button class="btn btn-success" id="salvar">Salvar</button>');
			$('#usuario .botoes_edicao_usuario').append('<a class="btn btn-info"  href= "#/">Cancelar</a>');
			_modoVisualizacao = false;
			_modoVisualizacao = false;
		};			

		var renderizarModoVisualizacao =  function renderizarModoVisualizacao()
		{
			$('#usuario input').prop("disabled", true);
			$('#usuario .botoes_edicao_usuario').empty();
			$('.titulo_editar_perfil h2').html('Visualizar Usuário');
			$('#usuario .botoes_edicao_usuario').append('<button class="btn btn-success" id="alterar">Alterar</button>');
			$('#usuario .botoes_edicao_usuario').append('<button class="btn btn-danger" id="remover">Remover</button>');
			$('#usuario .botoes_edicao_usuario').append('<a class="btn btn-info"  href= "#/">Cancelar</a>');
			_modoVisualizacao = true;
		};		

		// Obtém o conteúdo atual do form como um objeto
		_this.conteudo = function conteudo()
		{
			return servicoUsuario.criar(
				$('#id').val(),
				$('#nome').val(),
				$('#sobrenome').val(),
				$('#email').val(),
				$('#login').val()
			);
		};

		// Desenha o objeto no formulário
		_this.desenhar = function desenhar(obj)
		{
			$('#id').val(obj.id || 0);
			$('#nome').val(obj.nome || '');
			$('#sobrenome').val(obj.sobrenome || '');
			$('#email').val(obj.email || '');
			$('#login').val(obj.login || '');
		};  

		_this.salvar = function salvar(event)
		{
			// Ao validar e tudo estiver correto, é disparado o método submitHandler(),
			// que é definido nas opções de validação.
			$("#usuario").validate(criarOpcoesValidacao());
		};

		_this.alterar = function alterar(event)
		{
			event.preventDefault();
			renderizarModoEdicao();
		}
		_this.cancelar = function cancelar(event)
		{
			event.preventDefault();
			$("#usuario").validate(criarOpcoesValidacao());
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
					toastr.success('Usuário Atualizado com sucesso.');
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
					.always(terminado)
					;	
			}; // submitHandler

			return opcoes;
		}; // criarOpcoesValidacao

		// Configura os eventos do formulário
		_this.configurar = function configurar(){
			$('#nome').focus(); // Coloca o foco no 1° input = nome;
			$("#usuario").submit(false);
			$('#usuario').on('click', "#salvar", _this.salvar);
			$('#usuario').on('click', "#alterar", _this.alterar);
			$('#usuario').on('click', "#cancelar", _this.cancelar);
		};
	}; // ControladoraVisualizacaoFormUsuario
	 
	// Registrando
	app.ControladoraVisualizacaoFormUsuario = ControladoraVisualizacaoFormUsuario;

})(window, app, jQuery, toastr);


