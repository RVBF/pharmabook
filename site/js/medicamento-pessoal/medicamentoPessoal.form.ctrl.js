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

					"valor_recipiente":
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

					"menu_unidade":
					{
						required : true
					}
				},

				messages:
				{
					"medicamento" :
					{
						required : "Selecione um medicamento."
					 },

					"laboratorio":
					{
						required : "Campo obrigatório."
					},

					"validade" :
					{
						required : "Informe o prazo de validade do medicamento."
					},

					"valor_recipiente" :
					{
						required : "Informe a quantidade contida no recipiente."
					},

					"quantidade_estoque" :
					{
						required : "Informe a quantidade de medicamentos  que será inserida no estoque."
					},

					"menu_unidade" :
					{
						required : "Selecione a unidade contida no recipiente"
					},

					"administracao_tipo" :
					{
						required : "Selecione a forma de administração do medicamento."
					}
				}
			};

			// Irá disparar quando a validação passar, após chamar o método validate().
			regras.submitHandler = function submitHandler(form)
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
						renderizarModoVisualizacao();
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
			app.desabilitarFormulario();
			_modal.find('.modal-footer').empty();
			_modal.find('.modal-title').html('Visualizar Medicamento Pessoal');
			_modal.find('.modal-footer').append('<button class="btn btn-success" id="alterar">Alterar</button>');
			_modal.find('.modal-footer').append('<button class="btn btn-danger" id="remover">Remover</button>');
			_modal.find('.modal-footer').append('<button class="btn btn-info" id="cancelar">Cancelar</button>');
		};

		//Função para renderizar o modo de edição
		var renderizarModoEdicao =  function renderizarModoEdicao()
		{
			app.desabilitarFormulario(false);
			$('#medicamento').prop('disabled', true);
			$('#laboratorio').prop('disabled', true);
			_modal.find('.modal-footer').empty();
			_modal.find('.modal-title').html('Editar Medicamento Pessoal');
			_modal.find('.modal-footer').append('<button class="btn btn-success" id="salvar">Salvar</button>');
			_modal.find('.modal-footer').append('<button class="btn btn-danger" id="cancelar">Cancelar</button>');
		};

		//Função para renderizar o modo de cadastro
		var renderizarModoCadastro = function renderizarModoCadastro()
		{
			app.desabilitarFormulario(false);
			$('#laboratorio').prop('disabled', true);
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
				$('#valor_recipiente').val(),
				$('#quantidade_estoque').val(),
				$('#administracao_tipo').val(),
				$('#menu_unidade').val(),
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

		var getAdministracaoesMedicamentos  =  function getAdministracaoesMedicamentos(valor = 0)
		{
			var elemento = $('#administracao_tipo');

			var sucesso = function sucesso(resposta)
			{
				elemento.empty();

				$.each(resposta, function(i ,item)
				{
					var opcao = new Option(item, i);
					elemento.append(opcao);
				});

				elemento.trigger('change');

				if(valor != 0  || valor > 0)
				{
					elemento.val(valor).trigger('change');
				}
			};

			var  jqXHR = servicoMedicamentoPessoal.getAdministracaoesMedicamentos();
			jqXHR.done(sucesso);
		};

		var popularUnidadesDeMedida = function popularUnidadesDeMedida(unidadeMedida = undefined)
		{
			var elementoFormaMedicamento =  $('#forma_medicamento');

			var elementoUnidadeMenu = $('#menu_unidade');

			var sucesso = function sucesso(resposta)
			{
				elementoUnidadeMenu.empty().trigger('change');

				var opcao = new Option('Selecione', '');
 				elementoUnidadeMenu.append(opcao);

				$.each(resposta, function(i ,item)
				{
					var opcao = new Option(item, i);
 					elementoUnidadeMenu.append(opcao);
				});

				if(unidadeMedida != null || unidadeMedida != undefined)
				{
					console.log(unidadeMedida);
					elementoUnidadeMenu.val(unidadeMedida);
				}
			};

			if(elementoFormaMedicamento.val() == 'LIQUIDO')
			{
				var  jqXHR = servicoMedicamentoPessoal.unidadesLiquidas();
				jqXHR.done(sucesso);
			}
			else if(elementoFormaMedicamento.val() == "COMPRIMIDOS" || elementoFormaMedicamento.val() == "CAPSULAS")
			{
				var  jqXHR = servicoMedicamentoPessoal.unidadesInteiras();
				jqXHR.done(sucesso);
			}
			else if(elementoFormaMedicamento.val() == "POMADA" || elementoFormaMedicamento.val() == "PASTA" || elementoFormaMedicamento.val() == "CREME" || elementoFormaMedicamento.val() == "GEL")
			{
				var  jqXHR = servicoMedicamentoPessoal.unidadesSolidas();
				jqXHR.done(sucesso);
			}
		};

		var getMedicamentosFormas  =  function getMedicamentosFormas(valor = undefined)
		{
			var elemento  = $('#forma_medicamento');

			var sucesso = function sucesso(resposta)
			{
				elemento.empty();

				$.each(resposta, function(i ,item)
				{
					var opcao = new Option(item, i);
					elemento.append(opcao);
				});

				if(valor != 0  || valor > 0)
				{
					elemento.val(valor);
				}
				
				elemento.trigger('change');
			};

			var  jqXHR = servicoMedicamentoPessoal.getMedicamentosFormas();
			jqXHR.done(sucesso);
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
			}
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
			$("#valor_recipiente").val(obj.capacidadeRecipiente || '');
			$("#quantidade_estoque").val(obj.quantidade || '');
			
			getLaboratoriosDoMedicamentoParaSelect(obj.medicamento.laboratorio.id);
			getAdministracaoesMedicamentos(app.key_array(administracoes, obj.administracao));
			getMedicamentosFormas(app.key_array(medicamentosFormas, obj.medicamentoForma));
				
			if(app.key_array(unidadesTipos, obj.tipoUnidade) == null)
			{
				popularUnidadesDeMedida();	
			}
			else
			{
				popularUnidadesDeMedida(app.key_array(unidadesTipos, obj.tipoUnidade));	
			}

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

		_this.limparIdMedicamento = function limparIdMedicamento()
		{
			var medicamentoId = $("#medicamento_id");

			if(medicamentoId.val() != null && event.keyCode == 8)
			{
				medicamentoId.val('');
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

			$("#medicamento_pessoal_form").submit(false);

			app.definirMascarasPadroes();

			_modal.find(".modal-body").on("keyup", "#medicamento", _this.definirAutoCompleteMedicamento);
			_modal.find('.modal-body').on("change", "#forma_medicamento", popularUnidadesDeMedida)
			_modal.on('hide.bs.modal', _this.encerrarModal);
			_modal.find('.modal-header').on('click', '.encerrar_modal', _this.cancelar);
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


