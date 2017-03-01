/**
 *  farmacia.form.ctrl.js
 *
 *  @author  Rafael Vinicius Barros Ferreira
 *	 @version 1.0
 */
(function(window, app, $, toastr)
{
	'use strict';

	function ControladoraFormFarmacia(servicoFarmacia, servicoEndereco, controladoraEdicao)
	{ // Model

		var _this = this;
		var _modoAlteracao = true;

		var _obj = null;

		// Cria as opções de validação do formulário
		var criarOpcoesValidacao = function criarOpcoesValidacao()
		{
			var opcoes = {
				rules:
				{
					"nome": {
						required    : true,
						rangelength : [ 2, 50 ]
					},

					"logradouro": {
						required    : true
					},

					"bairro": {
						required    : true
					},

					"cidade": {
						required    : true
					}
				},

				messages:
				{
					"nome": {
						required    : "O campo nome  é obrigatório.",
						rangelength : $.validator.format("O campo nome deve ter no mínimo  {0} e no máximo {1} caracteres.")
					},

					"logradouro": {
						required    : "O campo  logradouro é obrigatório."
					},

					"bairro": {
						required    : "O campo bairro é obrigadorio."
					},

					"cidade": {
						required    : "O campo cidade é obrigadorio."
					}
				}
			};

			// Irá disparar quando a validação passar, após chamar o método validate().
			opcoes.submitHandler = function submitHandler(form)
			{
				// Habilita/desabilita os controles
				var controlesHabilitados = function controlesHabilitados(b)
				{
					$('#farmacia_form input').prop("disabled", !b);
					$('#cadastrar').prop("disabled", !b);
					$('#salvar').prop("disabled", !b);
					$('#visualizar').prop("disabled", !b);
					$('#cancelar').prop("disabled", !b);
				};

				controlesHabilitados(false);

				var sucesso = function sucesso(data, textStatus, jqXHR)
				{
					toastr.success('Salvo');

					renderizarModoVisualizacao();

					var controladoraListagem  = app.controladoraListagem();

					controladoraListagem.atualizar();
				};

				var erro = function erro(jqXHR, textStatus, errorThrown)
				{
					var mensagem = jqXHR.responseText;
					$('#msg').append('<div class="error" >' + mensagem + '</div>');
					controlesHabilitados(true);
				};

				var terminado = function()
				{
					controlesHabilitados(true);
				};

				var obj = _this.conteudo();

				if(_this.modoAlteracao())
				{

					var sucesso = function sucesso(data, textStatus, jqXHR)
					{
						toastr.success('Salvo');

						renderizarModoVisualizacao();
					};

					var jqXHR = servicoFarmacia.atualizar(obj);

					jqXHR
						.done(sucesso)
						.fail(erro)
					;
				}
				else
				{
					var sucesso = function sucesso(data, textStatus, jqXHR)
					{
						toastr.success('Salvo');
						encerrarModal();
						irPraListagem();
					};

					var jqXHR =  servicoFarmacia.adicionar(obj);
					jqXHR
						.done(sucesso)
						.fail(erro)
						.always( terminado )
					;
				}
			}; // submitHandler

			return opcoes;
		};
		// criarOpcoesValidacao

		var irPraListagem = function irPraListagem()
		{
			controladoraEdicao.modoListagem(true); // Vai pro modo de listagem
		};

		var encerrarModal = function encerrarModal()
		{
			$('#farmacia_modal').modal('hide');

			$('.modal').on('hidden.bs.modal', function(){
					$(this).find('#farmacia_form')[0].reset();
			});
		};

		var renderizarModoVisualizacao =  function renderizarModoVisualizacao()
		{
			app.desabilitarFormulario();
			$('.modal-footer').empty();
			$('.modal-title').html('Visualizar Farmácia');
			$('.modal-footer').append('<button class="btn btn-success" id="alterar">Alterar</button>');
			$('.modal-footer').append('<button class="btn btn-danger" id="remover">Remover</button>');
			$('.modal-footer').append('<button class="btn btn-info" id="cancelar">Cancelar</button>');
		};

		var renderizarModoEdicao =  function renderizarModoEdicao()
		{
			app.desabilitarFormulario(false);
			$('.modal-footer').empty();
			$('.modal-title').html('Editar Farmácia');
			$('.modal-footer').append('<button class="btn btn-success" id="salvar">Salvar</button>');
			$('.modal-footer').append('<button class="btn btn-danger" id="cancelar">Cancelar</button>');
		};

		var renderizarModoCadastro = function renderizarModoCadastro()
		{
			app.desabilitarFormulario(false);
			$('.modal-footer').empty();
			$('.modal-title').html('Cadastrar Farmácia');
			$('.modal-footer').append('<button class="btn btn-success" id="cadastrar">Cadastrar</button>');
			$('.modal-footer').append('<button class="btn btn-danger" id="cancelar">Cancelar</button>');
		};

		var definirMascaras = function definirMascaras()
		{
			$("#telefone").mask("(999)9999-9999");
			$('#cep').mask('99999-999');
		};

		_this.modoAlteracao = function modoAlteracao(b)
		{ // getter/setter
			if (b !== undefined)
			{
				_modoAlteracao = b;
			}
			return _modoAlteracao;
		};

		// Obtém o conteúdo atual do form como um objeto
		_this.conteudo = function conteudo()
		{
			return servicoFarmacia.criar(
				$('#id').val(),
				$('#nome').val(),
				$('#telefone').val(),
				servicoEndereco.criar(
					$('#endereco_id').val(),
					$('#cep').val(),
					$('#logradouro').val(),
					$('#numero').val(),
					$('#complemento').val(),
					$('#referencia').val(),
					$('#bairro').val(),
					$('#cidade').val(),
					$('#estado').val(),
					$('#pais').val()
				)
			);
		};

		_this.iniciarFormularioFarmacia = function iniciarFormularioFarmacia()
		{
			var opcoes = {
				show : true,
				keyboard : false,
				backdrop : true
			};

			var modal = $('#areaForm').find('#farmacia_modal').modal(opcoes);

			$('#nome').focus();
		};

		// Desenha o objeto no formulário
		_this.desenhar = function desenhar(obj)
		{
			_obj = obj;
			_this.iniciarFormularioFarmacia();

			$('#id').val(obj.id || 0);
			$('#nome').val(obj.nome ||'');
			$('#telefone').val(obj.telefone ||'');
			$('#endereco_id').val(obj.endereco.id || 0);
			$('#cep').val(obj.endereco.cep || '');
			$('#logradouro').val(obj.endereco.logradouro || '');
			$('#numero').val(obj.endereco.numero || '');
			$('#complemento').val(obj.endereco.complemento || '');
			$('#referencia').val(obj.endereco.referencia || '');
			$('#bairro').val(obj.endereco.bairro || '');
			$('#cidade').val(obj.endereco.cidade || '');
			$('#estado').val(obj.endereco.estado || '');
			$('#pais').val(obj.endereco.pais || '');

			if(obj.id == null)
			{
				renderizarModoCadastro();
			}
			else
			{
				if(obj.id > 0 )
				{
					renderizarModoVisualizacao();
				}
			}

			definirMascaras();
		};

		_this.salvar = function salvar(event)
		{
			// Ao validar e tudo estiver correto, é disparado o método submitHandler(),
			// que é definido nas opções de validação.

			$("#farmacia_form").validate(criarOpcoesValidacao());
		};

		_this.cancelar = function cancelar(event)
		{
			event.preventDefault();
			encerrarModal();
			irPraListagem();
		};

		_this.alterar = function alterar(event){
			event.preventDefault();
			renderizarModoEdicao();
		};

		_this.visualizar = function visualizar(event){
			event.preventDefault();
			renderizarModoVisualizacao();
		};

		_this.remover = function remover(event)
		{
			event.preventDefault();

			var sucesso = function sucesso( data, textStatus, jqXHR )
			{
				// Mostra mensagem de sucesso
				toastr.success( 'Removido' );

				encerrarModal();

				irPraListagem();

			};

			var erro = function erro( jqXHR, textStatus, errorThrown )
			{
				var mensagem = jqXHR.responseText || 'Ocorreu um erro ao tentar remover.';
				toastr.error( mensagem );
			};

			var solicitarRemocao = function solicitarRemocao()
			{
				if(_this.modoAlteracao())
				{
					servicoFarmacia.remover( _obj.id ).done( sucesso ).fail( erro );
				}
			};

			BootstrapDialog.show( {
				type	: BootstrapDialog.TYPE_DANGER,
				title	: 'Remover?',
				message	: _obj.nome,
				size	: BootstrapDialog.SIZE_LARGE,
				buttons	: [
					{
						label	: '<u>S</u>im',
						hotkey	: 'S'.charCodeAt( 0 ),
						action	: function( dialog ){
							dialog.close();
							solicitarRemocao();
						}
					},
					{
						label	: '<u>N</u>ão',
						hotkey	: 'N'.charCodeAt( 0 ),
						action	: function( dialog ){
							dialog.close();
						}
					}
				]
			} );
		}; // remover

		_this.pesquisarCep = function pesquisarCep ()
		{

			var sucesso =  function sucesso (data, textStatus, jqXHR)
			{
				$("#logradouro").val(data.logradouro);
				$("#bairro").val(data.bairro);
				$("#cidade").val(data.cidade);
				$("#estado").val(data.estado_info.nome);
			};

			var erro = function erro(jqXHR, textStatus, errorThrown)
			{

				var mensagem = jqXHR.statusText;
				$('#msg').append('<div class="error" >' + mensagem + '</div>');
				toastr.error( mensagem );
			};

			var cep = app.retornarInteiroEmStrings($("#cep").val());

			var jqXHR = servicoEndereco.consultarCepOnline(cep);

			jqXHR
				.done(sucesso)
				.fail(erro);
		}

		// Configura os eventos do formulário
		_this.configurar = function configurar()
		{
			controladoraEdicao.adicionarEvento(function evento(b)
			{
				$('#areaForm').toggle(!b);

				if (!b)
				{
					$('input:first-child').focus(); // Coloca o foco no 1° input
				}
			});

			$('.modal').find(" #farmacia_form").submit(false);
			$('.modal').find('.modal-body').on('click', '.pesquisar_cep', _this.pesquisarCep);
			$('.modal').find('.modal-footer').on('click', '#cancelar', _this.cancelar);
			$('.modal').find('.modal-footer').on('click', '#cadastrar', _this.salvar);
			$('.modal').find('.modal-footer').on('click', '#salvar', _this.salvar);
			$('.modal').find('.modal-footer').on('click', '#alterar', _this.alterar);
			$('.modal').find('.modal-footer').on('click', '#remover', _this.remover);
			$('.modal').find('.modal-footer').on('click', '#visualizar', _this.visualizar);
		};
	}; // ControladoraFormFarmacia

	// Registrando
	app.ControladoraFormFarmacia = ControladoraFormFarmacia;

})(window, app, jQuery, toastr);


