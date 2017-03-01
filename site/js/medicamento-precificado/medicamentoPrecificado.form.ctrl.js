/**
 *  medicamentoPrecificado.form.ctrl.js
 *
 *  @author  Rafael Vinicius Barros Ferreira
 */
(function(window, app, $, toastr)
{
	'use strict';
	function ControladoraFormMedicamentoPrecificado(
		servicoMedicamentoPrecificado,
		servicoUsuario,
		servicoMedicamento,
		servicoLaboratorio,
		servicoFarmacia,
		controladoraForm,
		controladoraEdicao
	)
	{ // Model

		var _this = this;
		var _modoAlteracao = true;
		var _modal = $('#medicamento_precificado_modal');
		var _obj = null;

		//Muda o estado da acção do usuário para modo listagem
		var irPraListagem = function irPraListagem()
		{
			controladoraEdicao.modoListagem(true); // Vai pro modo de listagem
		};

		// Cria as opções de validação do formulário
		var criarOpcoesValidacao = function criarOpcoesValidacao()
		{
			var opcoes = {
				rules:
				{
					"medicamento": {
						required    : true,
					},

					"laboratorio": {
						required    : true
					},

					"farmacia": {
						required    : true
					},


					"preco": {
						required    : true
					}
				},

				messages:
				{
					"medicamento": {
						required    : "O meicamento selecionado não corresponde a nenhum cadastrado na base de dados."
					},

					"laboratorio": {
						required    : "O campo Pesquisar laboratorio é obrigatório."
					},

					"farmacia": {
						required    : "O campo farmácia é obrigatório."
					},

					"preco": {
						required    : "O campo preço é obrigatório."
					}
				}
			};

			// Irá disparar quando a validação passar, após chamar o método validate().
			opcoes.submitHandler = function submitHandler(form)
			{
				// Habilita/desabilita os controles
				var controlesHabilitados = function controlesHabilitados(b)
				{
					app.desabilitarFormulario(!b);
					$('#cadastrar').prop("disabled", !b);
					$('#salvar').prop("disabled", !b);
					$('#visualizar').prop("disabled", !b);
					$('#cancelar').prop("disabled", !b);
				};

				controlesHabilitados(false);

				var erro = function erro(jqXHR, textStatus, errorThrown)
				{
					var mensagem = jqXHR.responseText;
					$('#msg').empty();
					$('#msg').append('<div class="error" >' + mensagem + '</div>');
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
						renderizarModoVisualizacao();
						toastr.success('Atualizado');
					};

					var jqXHR = servicoMedicamentoPrecificado.atualizar(obj);
					jqXHR
						.done(sucesso)
						.fail(erro)
					;
				}
				else
				{
					var sucesso = function (data, textStatus, jqXHR)
					{
						toastr.success('Salvo');
						encerrarModal();
						irPraListagem();
					};

					var jqXHR =  servicoMedicamentoPrecificado.adicionar(obj);
					jqXHR
						.done(sucesso)
						.fail(erro)
						.always(terminado);
				}
			}; // submitHandler

			return opcoes;
		};

		//criarOpcoesValidacao
		var  iniciaModalDeCadastro = function iniciaModalDeCadastro()
		{
			//Defini as opções da modal
			var opcoes = {
				show : true,
				keyboard : false,
				backdrop : true
			};

			_modal.modal(opcoes);

			$('#medicamento').focus();
		};

		//Encerra a modal
		var encerrarModal = function encerrarModal()
		{
			$('#medicamento_precificado_modal').modal('hide');

			$('#medicamento_precificado_modal').on('hidden.bs.modal', function(){
				$(this).find('#medicamento_precificado_form')[0].reset();
			});
		};

		//Funções para renderizar  o tipo de edição

		var renderizarModoVisualizacao =  function renderizarModoVisualizacao()
		{
			app.desabilitarFormulario()
			$('.modal .modal-footer').empty();
			$('#msg').empty();
			$('.modal .modal-title').html('Medicamento Precificado');
			$('.modal .modal-footer').append('<button class="btn btn-success" id="alterar">Alterar</button>');
			$('.modal .modal-footer').append('<button class="btn btn-danger" id="remover">Remover</button>');
			$('.modal .modal-footer').append('<button class="btn btn-info" id="cancelar">Cancelar</button>');
		};

		var renderizarModoEdicao =  function renderizarModoEdicao()
		{
			app.desabilitarFormulario();
			$('#preco').prop('disabled', false);
			$('.modal .modal-footer').empty();
			$('.modal .modal-title').html('Editar Medicamento');
			$('.modal .modal-footer').append('<button class="btn btn-success" id="salvar">Salvar</button>');
			$('.modal .modal-footer').append('<button class="btn btn-danger" id="cancelar">Cancelar</button>');
		};

		var renderizarModoCadastro = function renderizarModoCadastro()
		{
			app.desabilitarFormulario(false);
			$('#laboratorio').prop('disabled', true);
			$('.modal .modal-footer').empty();
			$('.modal .modal-title').html('Cadastrar Medicamento Precificado');
			$('.modal .modal-footer').append('<button class="btn btn-success" id="cadastrar">Cadastrar</button>');
			$('.modal .modal-footer').append('<button class="btn btn-danger" id="cancelar">Cancelar</button>');
		};
		//Funções para renderizar o modo do formulário

		//Função para popular os dados do select de farmácias
		var popularSelectFarmacia  =  function popularSelectFarmacia(valor = 0)
		{
			var sucesso = function (resposta)
			{
				$("#farmacia").empty();
				$("#farmacia").append($('<option>', {
					value: '',
					text: 'Selecione'
				}));

				$.each(resposta.data, function(i ,item) {
					$("#farmacia").append($('<option>', {
						value: item.id,
						text: item.nome
					}));
				});

				if(valor != 0  || valor > 0)
				{
					$("#farmacia").val(valor || 0);
				}
			};

			var erro = function(resposta)
			{
				var mensagem = jqXHR.responseText || 'Erro ao popular select de farmácias.';
				toastr.error( mensagem );
				return false;
			}

			var  jqXHR = servicoFarmacia.todos();
			jqXHR.done(sucesso).fail(erro);
		}

		//Funcção para indicar se o usuário  está editando o medicamento
		_this.modoAlteracao = function modoAlteracao(b) { // getter/setter
			if (b !== undefined) {
				_modoAlteracao = b;
			}
			return _modoAlteracao;
		};

		// Obtém o conteúdo atual do form como um objeto
		_this.conteudo = function conteudo()
		{
			var sessao = new app.ServicoSessao();

			var usuarioSessao = JSON.parse(sessao.getSessao());

			return servicoMedicamentoPrecificado.criar(
				$('#id').val(),
				app.converterEmFloat($('#preco').val()),
				servicoFarmacia.criar(
					$('#farmacia').val()
				),
				servicoMedicamento.criar(
					null,
					null,
					null,
					null,
					null,
					$('#medicamento_nome').val(),
					$('#composicao').val(),
					servicoLaboratorio.criar($('#laboratorio').val())
				)
		 	);
		};

		var popularSelectLaboratorio =  function popularSelectLaboratorio(resposta)
		{
			var elemento = $("#laboratorio");

			elemento.empty();

			$.each(resposta, function(i ,item)
			{
				var opcao = new Option(item.nome, item.id ,true, false)
				elemento.append(opcao);
			});

			elemento.trigger('change');
		};

		var getLaboratoriosDoMedicamentoParaSelect  =  function getLaboratoriosDoMedicamentoParaSelect(valor = 0)
		{
			var sucesso = function (resposta)
			{
				popularSelectLaboratorio(resposta);

				if(valor != 0  || valor > 0)
				{
					$("#laboratorio").val(valor).trigger('change');
				}
			};

			var medicamento = $('#medicamento_nome').val();
			var composicao = $('#composicao').val();

			if(medicamento != null && composicao != null)
			{
				var  jqXHR = servicoLaboratorio.getLaboratoriosDoMedicamento(medicamento, composicao);
				jqXHR.done(sucesso);
			}
		}

		//Pesquisa Medicamentos na Base de dados da Anvisa.
		_this.definirAutoCompleteMedicamento = function definirAutoCompleteMedicamento()
		{
			var elemento = $(this);
			var laboratorio = $('#laboratorio');

			if(elemento.val() == undefined || elemento.val() == '')
			{
				laboratorio.val('');
				laboratorio.prop('disabled', true);
				laboratorio.empty().trigger('change');
			}
			else
			{
				var getLaboratoriosDoMedicamento = function getLaboratoriosDoMedicamento(event, ui)
				{
					var sucesso = function (data)
					{
						popularSelectLaboratorio(data);
						laboratorio.prop('disabled', false);
					};

					var medicamento = ui.item.nomeComercial;
					var composicao = ui.item.composicao;

					if(medicamento)
					{
						var  jqXHR = servicoLaboratorio.getLaboratoriosDoMedicamento(medicamento, composicao);
						jqXHR.done(sucesso);
					}

					$('#medicamento_nome').val(medicamento);
					$('#composicao').val(composicao);
				};

				var efetuarRequisaoAutoComplete = function efetuarRequisaoAutoComplete(request, response)
				{
					var sucesso = function (data)
					{
						response(data);
					};

					var erro = function erro( jqXHR, textStatus, errorThrown )
					{
						var mensagem = jqXHR.responseText || 'Erro ao pesquisar medicamento.';
						toastr.error( mensagem );
					};

					var  jqXHR = servicoMedicamento.pesquisarMedicamentoParaAutoComplete(request.term);
					jqXHR.done(sucesso).fail(erro);
				};

				var opcoesAutoComplete = {
					minLength: 3,
					autoFocus: true,
					source: efetuarRequisaoAutoComplete,
					select : getLaboratoriosDoMedicamento,
				};

				elemento.autocomplete(opcoesAutoComplete).data("ui-autocomplete");
			}
		};

		// Desenha o objeto no formulário
		_this.desenhar = function desenhar(obj)
		{
			_obj = obj;
			iniciaModalDeCadastro();

			popularSelectFarmacia(obj.farmacia.id);

			$("#id").val(obj.id || 0);
			$("#medicamento_id").val(obj.medicamento.id || 0);
			$("#medicamento_nome").val(obj.medicamento.nomeComercial || '');
			$("#composicao").val(obj.medicamento.composicao || '');

			if(obj.medicamento.nomeComercial != undefined && obj.medicamento.composicao != undefined)
			{
				$("#medicamento").val(obj.medicamento.nomeComercial + ' ' + obj.medicamento.composicao);
			}
			else
			{
				$("#medicamento").val('');
			}

			$("#preco").val((obj.preco > 0) ? app.converterEmMoeda(obj.preco) : '');
			getLaboratoriosDoMedicamentoParaSelect(obj.medicamento.laboratorio.id);

			if(obj.id == 0)
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
		};
		//Funções para eventos dos botões

		//Chama a funcão de validação de dados e depois submete o formulário
		_this.salvar = function salvar(event)
		{
			// Ao validar e tudo estiver correto, é disparado o método submitHandler(),
			// que é definido nas opções de validação.

			$("#medicamento_precificado_form").validate(criarOpcoesValidacao());
		};

		//Fecha a modal e altera para o modo de listagem
		_this.cancelar = function cancelar(event) {
			event.preventDefault();
			encerrarModal();
			irPraListagem();
		};

		// Desbloqueia os campos para edição
		_this.alterar = function alterar(event){
			event.preventDefault();
			renderizarModoEdicao();
		};

		// BLoqueia os campos para apenas uma visualização
		_this.visualizar = function visualizar(event){
			event.preventDefault();
			renderizarModoVisualizacao();
		};

		//Remove o medicamento do sistema
		_this.remover = function remover(event) {
			event.preventDefault();

			var sucesso = function sucesso( data, textStatus, jqXHR ) {
				// Mostra mensagem de sucesso
				toastr.success( 'Removido' );

				encerrarModal();

				irPraListagem();

			};

			var erro = function erro( jqXHR, textStatus, errorThrown ) {
				var mensagem = jqXHR.responseText || 'Ocorreu um erro ao tentar remover.';
				toastr.error( mensagem );
			};

			var solicitarRemocao = function solicitarRemocao() {
				if(_this.modoAlteracao())
				{
					servicoMedicamentoPrecificado.remover( _obj.id ).done( sucesso ).fail( erro );
				}
			};

			BootstrapDialog.show( {
				type	: BootstrapDialog.TYPE_DANGER,
				title	: 'Remover?',
				message	: _obj.medicamento.nomeComercial,
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
		//fim Funções para eventos dos botões

		//Configura os eventos do formulário
		_this.configurar = function configurar()
		{
			controladoraEdicao.adicionarEvento(function evento(b) {
				$('#areaForm').toggle(!b);
				if (!b) {
					$('input:first-child').focus(); // Coloca o foco no 1° input
				}
			});

			app.definirMascarasPadroes();

			$(" #medicamento_precificado_form").submit(false);

			_modal.find(".modal-body").on("keyup", "#medicamento", _this.definirAutoCompleteMedicamento);
			_modal.on('hide.bs.modal', _this.encerrarModal);
			_modal.find('.modal-header').on('click', '.encerrar_modal', _this.cancelar);
			_modal.find('.modal-footer').on('click', '#cancelar', _this.cancelar);
			_modal.find('.modal-footer').on('click', '#cadastrar', _this.salvar);
			_modal.find('.modal-footer').on('click', '#salvar', _this.salvar);
			_modal.find('.modal-footer').on('click', '#alterar', _this.alterar);
			_modal.find('.modal-footer').on('click', '#remover', _this.remover);
			_modal.find('.modal-footer').on('click', '#visualizar', _this.visualizar);
		};
	}; // ControladoraFormMedicamentoPrecificado

	// Registrando
	app.ControladoraFormMedicamentoPrecificado = ControladoraFormMedicamentoPrecificado;
})(window, app, jQuery, toastr);


