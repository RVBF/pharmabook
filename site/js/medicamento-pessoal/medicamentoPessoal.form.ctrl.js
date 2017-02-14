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

		var medicamentosFormas = {
			POMADA : 'Pomada',
			PASTA : 'Pasta',
			CREME : 'Creme',
			GEL : 'Gel',
			COMPRIMIDOS : 'Comprimidos',
			CAPSULAS : 'Cápsulas',
			PO : 'Pó',
			LIQUIDO : 'Líquido'
		};

		var administracoes = {
			ADMINISTRACAO_ORAL : 'Oral',
			ADMINISTRACAO_SUBLINGUAL : 'Sublingual',
			ADMINISTRACAO_RETAL : 'Retal',
			ADMINISTRACAO_INTRA_VENOSA : 'Intra-Venosa',
			ADMINISTRACAO_INTRA_MUSCULAR : 'Intra-Muscular',
			ADMINISTRACAO_SUBCUTÂNEA : 'Subcutânea',
			ADMINISTRACAO_INTRADÉRMICA : 'Intradérmica',
			ADMINISTRACAO_INTRA_ARTERIAL :  'Intra-arterial',
			ADMINISTRACAO_INTRACARDÍACA :  'Intracardíaca',
			ADMINISTRACAO_INTRATECAL :  'Intratecal',
			ADMINISTRACAO_PERIDURAL :  'Peridural',
			ADMINISTRACAO_INTRA_ARTICULAR :  'Intra-articular',
			ADMINISTRACAO_CUTÂNEA :  'Cutânea',
			ADMINISTRACAO_RESPIRATÓRIA :  'Respiratória',
			ADMINISTRACAO_CONJUNTIVAL :  'Conjuntival',
			ADMINISTRACAO_GENITURINÁRIA :  'Geniturinária',
			ADMINISTRACAO_INTRACANAL :  'Intracanal'
		};

		var unidadesTipos = {
			QUILO : 'Quilo',
			GRAMA : 'Grama',
			MICROGRAMA : 'Micrograma',
			MILIGRAMA : 'Miligrama',
			LITRO : 'Litro',
			MILILITRO : 'Mililitro',
			COMPRIMIDO : 'Comprimido',
			CAPSULAS : 'Cápsulas'
		};
		
		//Muda o estado da acção do usuário para modo listagem
		var irPraListagem = function irPraListagem()
		{
			controladoraEdicao.modoListagem(true); // Vai pro modo de listagem
		};

		// Cria as opções de validação do formulário
		var criarOpcoesValidacao = function criarOpcoesValidacao()
		{
			var regras = {
				rules:
				{
					"medicamento":
					{
						required : true
					},

					"laboratorio":
					{
						required : true
					},

					"validade":
					{
						required : true
					},					

					"quantidade_recipiente":
					{
						required : true
					},					

					"quantidade_estoque":
					{
						required : true
					},				

					"administracao_tipo":
					{
						required : true
					},

					"unidade_tipo":
					{
						required : true
					}
				},

				messages:
				{
					"medicamento" :
					{
						required : "Insira o nome do medicamento."
					 },				

					"laboratorio":
					{
						required : "Campo obrigatório."
					},

					"validade" : 
					{
						required : "Informe a validade"
					},					

					"quantidade_recipiente" : 
					{
						required : "Informe a quantiade do recipiente"
					},				

					"quantidade_estoque" : 
					{
						required : "Informe a quantiade do recipiente"
					},					

					"unidade_tipo" : 
					{
						required : "Informe o tipo de unidade"
					},					

					"administracao_tipo" : 
					{
						required : "Informe o tipo de administração."
					}
				}
			};

			// Irá disparar quando a validação passar, após chamar o método validate().
			regras.submitHandler = function submitHandler(form)
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
						// encerrarModal();
						// irPraListagem();
					};

					var jqXHR =  servicoMedicamentoPessoal.adicionar(obj);
					jqXHR
						.done(sucesso)
						.fail(erro)
						.always(terminado);
				}
			}; // submitHandler

			return regras;
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
			$("#medicamento_pessoal_form select").chosen().prop('disabled', true).trigger("chosen:updated");
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
					$("#laboratorio").val(valor).trigger('chosen:updated');
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
					$("#administracao_tipo").val(valor || 0).trigger('chosen:updated');
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
					$("#forma_medicamento").val(valor || 0).trigger('chosen:updated');
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

			$("#id").val(obj.id || 0);
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

			$("#validade").val(obj.validade);
			$("#quantidade_recipiente").val(obj.capacidadeRecipiente || '');
			$("#quantidade_estoque").val(obj.quantidade || '');

			getAdministracaoesMedicamentos(app.key_array(administracoes, obj.administracao));
			getMedicamentosFormas(app.key_array(medicamentosFormas, obj.medicamentoForma));
			getLaboratoriosDoMedicamentoParaSelect(obj.medicamento.laboratorio.id);
			$("#unidade_tipo").val(obj.tipoUnidade || '');
			$("#unidade_tipo").val(obj.tipoUnidade || '');
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

			var sucesso = function sucesso(resposta)
			{
				$("#menu_unidades").empty();
				$.each(resposta, function(i ,item) {
					$('#menu_unidades')
						.append($('<li></li>')
						.attr('data-value', i)
						.html(item));
				});
			};

			if(elemento.val() == 'LIQUIDO')
			{
				var  jqXHR = servicoMedicamentoPessoal.unidadesLiquidas();
				jqXHR.done(sucesso);
			}
			else if(elemento.val() == "COMPRIMIDOS" || elemento.val() == "CAPSULAS")
			{
				var  jqXHR = servicoMedicamentoPessoal.unidadesInteiras();
				jqXHR.done(sucesso);
			}
			else if(elemento.val() == "POMADA" || elemento.val() == "PASTA" || elemento.val() == "CREME" || elemento.val() == "GEL")
			{
				var  jqXHR = servicoMedicamentoPessoal.unidadesSolidas();
				jqXHR.done(sucesso);
			}
		};
		//fim Função para eventos dos botões

		//Configura os eventos do formulário
		_this.configurar = function configurar()
		{
			controladoraEdicao.adicionarEvento(function evento(b) {
				$('#areaForm').toggle(!b);
				if (!b) {
					$('input:first-child').focus(); // Coloca o foco no 1° input
				}
			});
			$(" #medicamento_pessoal_form").submit(false);

			app.definirMascarasPadroes();			
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


