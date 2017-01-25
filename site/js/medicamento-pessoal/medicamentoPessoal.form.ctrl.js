/**
 *  medicamentoPessoal.form.ctrl.js
 *
 *  @author  Rafael Vinicius Barros Ferreira
 */
(function(window, app, $, toastr)
{
	'use strict';
	function ControladoraFormMedicamentoPessoal(
		servicoLaboratorio,
		servicoMedicamento,
		servicoMedicamentoPessoal,
		controladoraEdicao
	)
	{
		var _this = this;
		var _modoAlteracao = true;
		var _modal = $('#medicamento_pessoal_modal');
		var _obj = null;

		var _unidadesDeMedidasDeMassa = {
			quilo:  'K - Quilo',
			grama: 'G - Grama',
			micrograma: 'MCG - Micrograma',
			miligrama : 'MLG - Miligrama'
		};

		var _unidadeDeMedidaDeVolume = {
			litro: 'L - Litro',
			mililitro: 'ML - Mililitro'
		};

		var _unidadesInteiras = {
			comprimido: 'Comprimido',
			capsulas: 'Cápsulas'
		};

		//Muda o estado da acção do usuário para modo listagem
		var irPraListagem = function irPraListagem()
		{
			controladoraEdicao.modoListagem(true); // Vai pro modo de listagem
		};

		//Defini as máscaras do formulário
		var definirMascaras = function definirMascaras()
		{
			var optionsChosen = {
				disable_search: false,
				no_results_text : 'Valor não encontrado.',
				placeholder_text_single : ' ',
				max_shown_results : 20
			};

			$(".chosen-select").chosen(optionsChosen);

			var optionsDatePicker = {
				format: "dd/mm/yyyy",
				language: 'pt-BR',
				startView: 0,
				startDate: "today",
				autoclose: true,
				todayHighlight: true,
				todayBtn: true
			};

			$('.datepicker').datepicker(optionsDatePicker);
		};

		var defirnirMascaraFloat = function definirMascaraFLoat()
		{

		};

		// Cria as opções de validação do formulário
		var criarOpcoesValidacao = function criarOpcoesValidacao()
		{
			var opcoes = {
				focusInvalid: false,
				onkeyup: false,
				onfocusout: true,
				errorElement: "div",
				errorPlacement: function(error,element) {
					if (element.is(":hidden"))
					{
						//console.log(element.next().parent());
						element.next().parent().append(error);
					}
					else{
						error.insertAfter(element);
					}
				},

				rules:
				{
					"chosen": {
						required    : true
					},

					"validade": {
						required: true
						// date: true
    				},

					"quantidade": {
						required    : true
					}
				},

				messages:
				{
					"chosen": {
						required : "Campo obrigatório."
					},

					"validade": {
						required : "O campo validade é obrigatório."
						// date : "O campo validade dever receber um valor do tipo data."
					},

					"quantidade": {
						required : "O campo quantidade é obrigatório."
					}
				}
			};

			// Irá disparar quando a validação passar, após chamar o método validate().
			opcoes.submitHandler = function submitHandler(form)
			{
				// Habilita/desabilita os controles
				var controlesHabilitados = function controlesHabilitados(b)
				{
					$('#medicamento_pessoal_form input').prop("disabled", !b);
					$('#medicamento_pessoal_form select').prop("disabled", !b);
					$('#cadastrar').prop("disabled", !b);
					$('#salvar').prop("disabled", !b);
					$('#visualizar').prop("disabled", !b);
					$('#cancelar').prop("disabled", !b);
				};

				controlesHabilitados(false);

				var erro = function erro(jqXHR, textStatus, errorThrown)
				{
					var mensagem = jqXHR.responseText;
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

					var jqXHR = servicoMedicamentoPessoal.atualizar(obj);
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

					var jqXHR =  servicoMedicamentoPessoal.adicionar(obj);
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

			$('#medicamento_precificado').focus();
		};

		//Encerra a modal
		var encerrarModal = function encerrarModal()
		{
			_modal.modal('hide');

			_modal.on('hidden.bs.modal', function(){
				$(this).find('#medicamento_pessoal_form')[0].reset();
			});
		};

		//Função para renderizar  o modo de visualização
		var renderizarModoVisualizacao =  function renderizarModoVisualizacao()
		{
			$('#medicamento_pessoal_form input').prop("disabled", true);
			_modal.find('.modal-footer').empty();
			_modal.find('.modal-title').html('Visualizar Medicamento Pessoal');
			_modal.find('.modal-footer').append('<button class="btn btn-success" id="alterar">Alterar</button>');
			_modal.find('.modal-footer').append('<button class="btn btn-danger" id="remover">Remover</button>');
			_modal.find('.modal-footer').append('<button class="btn btn-info" id="cancelar">Cancelar</button>');
		};

		//Função para renderizar o modo de edição
		var renderizarModoEdicao =  function renderizarModoEdicao()
		{
			$('#medicamento_pessoal_form #quantidade').prop("disabled", false);
			_modal.find('.modal-footer').empty();
			_modal.find('.modal-title').html('Editar Medicamento Pessoal');
			_modal.find('.modal-footer').append('<button class="btn btn-success" id="salvar">Salvar</button>');
			_modal.find('.modal-footer').append('<button class="btn btn-danger" id="cancelar">Cancelar</button>');
		};

		//Função para renderizar o modo de cadastro
		var renderizarModoCadastro = function renderizarModoCadastro()
		{
			$('#medicamento_pessoal_form input').prop("disabled", false);
			_modal.find('.modal-footer').empty();
			_modal.find('.modal-title').html('Cadastrar Medicamento Pessoal');
			_modal.find('.modal-footer').append('<button class="btn btn-success" id="cadastrar">Cadastrar</button>');
			_modal.find('.modal-footer').append('<button class="btn btn-danger" id="cancelar">Cancelar</button>');
		};
		//Função para renderizar o modo do formulário

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
			console.log($('#unidade_tipo').val());
			return servicoMedicamentoPessoal.criar(
				$('#id').val(),
				$('#validade').val(),
				$('#quantidade_recipiente').val(),
				$('#quantidade_estoque').val(),
				$('#administracao_tipo').val(),
				$('#unidade_tipo').val(),
				$('#forma_medicamento').val(),
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
			$("#laboratorio").empty();

			$.each(resposta, function(i ,item) {
				$('#laboratorio')
				.append($('<option></option>')
				.val(item.id)
				.attr('selected', 'selected')
				.html(item.nome)).trigger('chosen:updated');
			});
		};

		var getLaboratoriosDoMedicamentoParaSelect  =  function getLaboratoriosDoMedicamentoParaSelect(valor = 0)
		{
			var sucesso = function (resposta)
			{
				popularSelectLaboratorio(resposta);

				if(valor != 0  || valor > 0)
				{
					$("#laboratorio").val(valor || 0);
				}
			};
		}

		var getAdministracaoesMedicamentos  =  function getAdministracaoesMedicamentos(valor = 0)
		{
			var sucesso = function sucesso(resposta)
			{
				$("#administracao_tipo").empty();
				$.each(resposta, function(i ,item) {
					$('#administracao_tipo')
					.append($('<option></option>')
					.val(i)
					.html(item)).trigger('chosen:updated');
				});

				if(valor != 0  || valor > 0)
				{
					$("#administracao_tipo").val(valor || 0);
				}
			};

			var  jqXHR = servicoMedicamentoPessoal.getAdministracaoesMedicamentos();
			jqXHR.done(sucesso);
		}

		var getMedicamentosFormas  =  function getMedicamentosFormas(valor = 0)
		{
			var sucesso = function sucesso(resposta)
			{
				$("#forma_medicamento").empty();
				$.each(resposta, function(i ,item) {
					$('#forma_medicamento')
					.append($('<option></option>')
					.val(i)
					.html(item)).trigger('chosen:updated');
				});

				if(valor != 0  || valor > 0)
				{
					$("#forma_medicamento").val(valor || 0);
				}

				_this.definirAlteracaoFormaDeMedicamento($("#forma_medicamento"));
			};

			var  jqXHR = servicoMedicamentoPessoal.getMedicamentosFormas();
			jqXHR.done(sucesso);
		}

		//Pesquisa Medicamentos na Base de dados da Anvisa.
		_this.definirAutoCompleteMedicamento = function definirAutoCompleteMedicamento()
		{
			var elemento = $(this);

			var getLaboratoriosDoMedicamento = function getLaboratoriosDoMedicamento(event, ui)
			{
				var sucesso = function (data)
				{
					popularSelectLaboratorio(data)
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

				var erro = function erro( jqXHR, textStatus, errorThrown ) {
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
		};

		// Desenha o objeto no formulário
		_this.desenhar = function desenhar(obj)
		{
			_obj = obj;
			iniciaModalDeCadastro();
			getAdministracaoesMedicamentos();
			getMedicamentosFormas();

			$("#id").val(obj.id || 0);
			$("#medicamentoPrecificado_id").val(obj.medicamentoPrecificado.medicamento.id || 0);
			$("#farmacia_id").val(obj.medicamentoPrecificado.farmacia.id || 0);
			$("#medicamento_precificado").val(obj.medicamentoPrecificado.medicamento.nomeComercial || '');
			$("#farmacia").val(obj.medicamentoPrecificado.farmacia.nome || '');
			$("#validade").val(obj.validade || '');
			$("#quantidalaboratoriode").val(obj.quantidade || '');

			if(obj.id == undefined)
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

		//Função para eventos dos botões

		//Chama a funcão de validação de dados e depois submete o formulário
		_this.salvar = function salvar(event)
		{
			// Ao validar e tudo estiver correto, é disparado o método submitHandler(),
			// que é definido nas opções de validação.
			$("#medicamento_pessoal_form").validate(criarOpcoesValidacao());
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
					servicoMedicamentoPessoal.remover( _obj.id ).done( sucesso ).fail( erro );
				}
			};

			BootstrapDialog.show( {
				type	: BootstrapDialog.TYPE_DANGER,
				title	: 'Remover?',
				message	: _obj.medicamentoPrecificado.medicamento.nomeComercial,
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

		_this.limparIdMedicamento = function limparIdMedicamento()
		{
			var medicamentoId = $("#medicamento_id");

			if(medicamentoId.val() != null && event.keyCode == 8)
			{
				medicamentoId.val('');
			}
		};


		_this.definirAlteracaoFormaDeMedicamento = function definirAlteracaoFormaDeMedicamento(elementoParametro = null)
		{
			var elemento = (elementoParametro == null) ? $(this) : elementoParametro;

			$('#menu_unidades').append($('<li></li>').attr('data-value', '').html('Selecione')).trigger('chosen:updated');
			if(elemento.val() == 'LIQUIDO')
			{
				$("#menu_unidades").empty();
				$('#menu_unidades').append($('<li></li>').attr('data-value', '').html('Selecione')).trigger('chosen:updated');

				$.each(_unidadeDeMedidaDeVolume, function(i, item)
				{
					$('#menu_unidades').append($('<li></li>').attr('data-value', i).html(item)).trigger('chosen:updated');
				});
			}
			else if(elemento.val() == "COMPRIMIDOS" || elemento.val() == "CAPSULAS")
			{
				$("#menu_unidades").empty();
				$('#menu_unidades').append($('<li></li>').attr('data-value', '').html('Selecione')).trigger('chosen:updated');

				$.each(_unidadesInteiras, function(i, item)
				{
					$('#menu_unidades').append($('<li></li>').attr('data-value', i).html(item)).trigger('chosen:updated');
				});
			}
			else if(elemento.val() == "POMADA" || elemento.val() == "PASTA" || elemento.val() == "CREME" || elemento.val() == "GEL")
			{
				$("#menu_unidades").empty();
				$('#menu_unidades').append($('<li></li>').attr('data-value', '').html('Selecione')).trigger('chosen:updated');

				$.each(_unidadesDeMedidasDeMassa, function(i, item)
				{
					$('#menu_unidades').append($('<li></li>').attr('data-value', i).html(item)).trigger('chosen:updated');
				});
			}
		};
		//fim Função para eventos dos botões

		//Configura os eventos do formulário
		_this.configurar = function configurar()
		{
			// controladoraEdicao.adicionarEvento(function evento(b) {
			// 	$('#areaForm').toggle(!b);
			// 	if (!b) {
			// 		$('input:first-child').focus(); // Coloca o foco no 1° input
			// 	}
			// });

			$(" #medicamento_pessoal_form").submit(false);

			definirMascaras();

			_modal.find(".modal-body").on("focus", "#medicamento", _this.definirAutoCompleteMedicamento);
			_modal.find('.modal-body').on("change", "#forma_medicamento", _this.definirAlteracaoFormaDeMedicamento)
			_modal.find('.modal-footer').on('click', '#cancelar', _this.cancelar);
			_modal.find('.modal-footer').on('click', '#cadastrar', _this.salvar);
			_modal.find('.modal-footer').on('click', '#salvar', _this.salvar);
			_modal.find('.modal-footer').on('click', '#alterar', _this.alterar);
			_modal.find('.modal-footer').on('click', '#remover', _this.remover);
			_modal.find('.modal-footer').on('click', '#visualizar', _this.visualizar);
		};
	}; // ControladoraFormMedicamentoPessoal

	// Registrando
	app.ControladoraFormMedicamentoPessoal = ControladoraFormMedicamentoPessoal;
})(window, app, jQuery, toastr);


