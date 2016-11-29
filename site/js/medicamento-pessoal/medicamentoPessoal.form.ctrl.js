/**
 *  medicamentoPessoal.form.ctrl.js
 *  
 *  @author  Rafael Vinicius Barros Ferreira
 */
(function(window, app, $, toastr) 
{
	'use strict'; 
	function ControladoraFormMedicamentoPrecificado(
		servicoMedicamentoPrecificado,
		servicoFarmacia,
		servicoUsuario,
		servicoMedicamentoPessoal,
		servicoPosologia,
		controladoraEdicao
	) 
	{ // Model

		var _this = this;
		var _modoAlteracao = true;
		var _modal = $('#medicamento_pessoal_modal');
		var _obj = null;

		//Muda o estado da acção do usuário para modo listagem
		var irPraListagem = function irPraListagem()
		{
			controladoraEdicao.modoListagem(true); // Vai pro modo de listagem
		};

		//Defini as máscaras do formulário
		var definirMascaras = function definirMascaras()
		{
			var opcoes = {
				format: "dd/mm/yyyy",
				language: 'pt-BR',
				startView: 0,
				startDate: "today",
				autoclose: true,
				todayHighlight: true,
				todayBtn: true
			};

			$('.datepicker').datepicker(opcoes);
		};

		// Cria as opções de validação do formulário
		var criarOpcoesValidacao = function criarOpcoesValidacao()
		{
			var opcoes = {
				focusInvalid: false,
				onkeyup: false,
				onfocusout: true,
				errorElement: "div",
				errorPlacement: function(error, element) {
					error.appendTo("div#msg");
				}, 
				rules: 
				{
				},

				messages: 
				{
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
		
		//Funções para renderizar  o tipo de edição

		var renderizarModoVisualizacao =  function renderizarModoVisualizacao()
		{
			$('#medicamento_pessoal_form input').prop("disabled", true);
			$('#medicamento_pessoal_form select').prop("disabled", true);
			_modal.find('.modal-footer').empty();
			_modal.find('.modal-title').html('Visualizar Medicamento');
			_modal.find('.modal-footer').append('<button class="btn btn-success" id="alterar">Alterar</button>');
			_modal.find('.modal-footer').append('<button class="btn btn-danger" id="remover">Remover</button>');
			_modal.find('.modal-footer').append('<button class="btn btn-info" id="cancelar">Cancelar</button>');
		};

		var renderizarModoEdicao =  function renderizarModoEdicao()
		{
			$('#preco').prop("disabled", false);
			_modal.find('.modal-footer').empty();
			_modal.find('.modal-title').html('Editar Medicamento');
			_modal.find('.modal-footer').append('<button class="btn btn-success" id="salvar">Salvar</button>');
			_modal.find('.modal-footer').append('<button class="btn btn-danger" id="cancelar">Cancelar</button>');
		};

		var renderizarModoCadastro = function renderizarModoCadastro()
		{
			$('#medicamento_pessoal_form input').prop("disabled", false);
			$('#medicamento_pessoal_form select').prop("disabled", false);
			_modal.find('.modal-footer').empty();
			_modal.find('.modal-title').html('Adicionar Medicamento ao Estoque');
			_modal.find('.modal-footer').append('<button class="btn btn-success" id="cadastrar">Cadastrar</button>');
			_modal.find('.modal-footer').append('<button class="btn btn-danger" id="cancelar">Cancelar</button>');
		};
		//Funções para renderizar o modo do formulário

		//Função para popular os dados do select de Posologias
		var popularSelectTiposDeAdministracao  =  function popularSelectTiposDeAdministracao(valor = '')
		{
			var sucesso = function (resposta)
			{
				$("#administracao_medicamento").empty();
				$("#administracao_medicamento").append($('<option>', {
					value: '',
					text: 'Selecione'
				}));
		
				$.each(resposta, function(i ,item) {
					$("#administracao_medicamento").append($('<option>', {
						value : item.id,
						text : item.nome
					}));
				});

				if(valor != ''  || valor > '')
				{
					$("#administracao_medicamento").val(valor || '');
				}
			};

			var erro = function(resposta)
			{
				var mensagem = jqXHR.responseText || 'Erro ao popular o select de tipos de administracao.';
				toastr.error( mensagem );
				return false;
			}

			var  jqXHR = servicoPosologia.getTiposDeAdministracao();
			jqXHR.done(sucesso).fail(erro);
		}	

		//Função para popular os dados do select de posologias
		var popularSelectTiposDeUnidade  =  function popularSelectTiposDeUnidade(valor = '')
		{
			var sucesso = function (resposta)
			{
				$("#tipo_unidade").empty();
				$("#tipo_unidade").append($('<option>', {
					value: '',
					text: 'Selecione'
				}));
		
				$.each(resposta, function(i ,item) {
					$("#tipo_unidade").append($('<option>', {
						value : item.id,
						text : item.nome
					}));
				});

				if(valor != ''  || valor > '')
				{
					$("#tipo_unidade").val(valor || '');
				}
			};

			var erro = function(resposta)
			{
				var mensagem = jqXHR.responseText || 'Erro ao popular o select de tipos de unidade.';
				toastr.error( mensagem );
				return false;
			}

			var  jqXHR = servicoPosologia.getTiposDeUnidade();
			jqXHR.done(sucesso).fail(erro);
		}	

		//Função para popular os dados do select de posologias
		var popularSelectTiposDePeriodicidade  =  function popularSelectTiposDePeriodicidade(valor = '')
		{
			var sucesso = function (resposta)
			{
				$("#tipo_periodicidade").empty();
				$("#tipo_periodicidade").append($('<option>', {
					value: '',
					text: 'Selecione'
				}));
		
				$.each(resposta, function(i ,item) {
					$("#tipo_periodicidade").append($('<option>', {
						value : item.id,
						text : item.nome
					}));
				});

				if(valor != ''  || valor > '')
				{
					$("#tipo_periodicidade").val(valor || '');
				}
			};

			var erro = function(resposta)
			{
				var mensagem = jqXHR.responseText || 'Erro ao popular select de tipos de periodicidade.';
				toastr.error( mensagem );
				return false;
			}

			var  jqXHR = servicoPosologia.getTiposDePeriodicidade();
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
			return servicoMedicamentoPessoal.criar(
				$('#id').val(),
				$('#validade').val(),
				$('#quantidade').val(),
				servicoMedicamentoPrecificado.criar(
					$('#medicamentoPrecificado_id').val()
				)
		 	);
		};

		/* Busca o medicamento no servidor 
		  caso o usuário tenha preenchido os campos de pesquisa do
		  laborátorio ou Medicamento. 
		*/
		_this.getMedicamentoPrecificados = function getMedicamentoPrecificados(event)
		{
			var sucesso = function (data)
			{
				if(data[0] != null )
				{
					console.log(data);
					$("#medicamentoPrecificado_id").val(data[0].id);
				}
			};

			var medicamentoPrecificado = $("#medicamento_precificado").val();
			var farmaciaId = $("#farmacia_id").val();

			if(farmaciaId != '' &&  medicamentoPrecificado  != '')
			{
				var  jqXHR = servicoMedicamentoPrecificado.getMedicamentoPrecificados(medicamentoPrecificado, farmaciaId);
				jqXHR.done(sucesso);
			}
		};

		//Pesquisa Medicamentos na Base de dados da Anvisa.
		_this.definirAutoCompleteMedicamentosPrecificado = function definirAutoCompleteMedicamentosPrecificado()
		{
			var elemento = $(this);

			var efetuarRequisaoAutoComplete = function efetuarRequisaoAutoComplete(request, response) {
				var sucesso = function (data)
				{
					response(data);
				};

				var erro = function erro( jqXHR, textStatus, errorThrown ) {
					var mensagem = jqXHR.responseText || 'Erro ao pesquisar medicamento precificado.';
					toastr.error( mensagem );
				};

				var farmaciaId = $("#farmacia_id").val();

				var  jqXHR = servicoMedicamentoPrecificado.pesquisarMedicamentoPrecificado(request.term, farmaciaId);
				jqXHR.done(sucesso).fail(erro);
			};

			var preencherCombosDaPesquisa =  function preencherCombosDaPesquisa(event, ui)
			{
				elemento.val(ui.item.value);
			};

			var opcoesAutoComplete = {
				minLength: 3,
				autoFocus: true,
				source: efetuarRequisaoAutoComplete,
				select: preencherCombosDaPesquisa,
				classes: {
					"ui-autocomplete": "highlight"
				},
				open: function () {
					$(this).removeClass("ui-corner-all").addClass("ui-corner-top");
				},

				close: function () {
					$(this).removeClass("ui-corner-top").addClass("ui-corner-all");
				},
			};

			elemento.autocomplete(opcoesAutoComplete).data("ui-autocomplete")._renderItem = function ( ul, item ) {
				return $( "<li>" )
					.attr( "data-value", item.value )
					.append( "<a> " + item.label + "  "+item.composicao+"</a>" )
					.appendTo( ul );
			};
		};		

		//Pesquisa o laboratórios na base de dados da anvisa
		_this.definirAutoCompleteFarmacias = function definirAutoCompleteFarmacias()
		{
			var elemento = $(this);

			var efetuarRequisaoAutoComplete = function efetuarRequisaoAutoComplete(request, response)
			{
				var sucesso = function (data)
				{
					response(data);
				};

				var medicamentoPrecificado = $("#medicamento_precificado").val();

				var  jqXHR = servicoFarmacia.pesquisarFarmacia(request.term, medicamentoPrecificado);
				jqXHR.done(sucesso);
			};

			var preencherCombosDaPesquisa =  function preencherCombosDaPesquisa(event, ui)
			{
				$('.modal-body').find('#farmacia_id').val(ui.item.id);
				elemento.val(ui.item.value);
			};

			var opcoesAutoComplete = {
				minLength: 3,
				autoFocus: true,
				source: efetuarRequisaoAutoComplete,
				select: preencherCombosDaPesquisa,
				classes: {
					"ui-autocomplete": "highlight"
				},
				open: function () {
					$(this).removeClass("ui-corner-all").addClass("ui-corner-top");
				},

				close: function () {
					$(this).removeClass("ui-corner-top").addClass("ui-corner-all");
				}
			};

			elemento.autocomplete(opcoesAutoComplete);	
		};

		// Desenha o objeto no formulário
		_this.desenhar = function desenhar(obj)
		{
			_obj = obj;
			iniciaModalDeCadastro();
			popularSelectTiposDePeriodicidade();
			popularSelectTiposDeUnidade();
			popularSelectTiposDeAdministracao();

			// $("#id").val(obj.id || 0);
			// $("#medicamentoPrecificado_id").val(obj.medicamento.id || 0);
			// $("#farmacia_id").val(obj.medicamento.laboratorio || 0);
			// $('#farmacia option[text='+obj.farmacia.id+']').prop('selected', true);
			// $("#medicamento_precificado").val(obj.medicamento.nomeComercial || '');
			// $("#preco").val((obj.preco > 0) ? app.converterEmMoeda(obj.preco) : '');

			if(obj.id == null)
			{
				renderizarModoCadastro();
			}
			else
			{
				if(obj.id > 0 )
				{
					// var sucesso = function(data, textStatus, jqXHR)
					// {
					// 	$("#farmacia").val(data.nome || 0);
					// };
				
					// var jqXHR = servicoFarmacia.comId(obj.medicamento.laboratorio);

					// jqXHR.done(sucesso);

					// renderizarModoVisualizacao();
				}
			}
		};

		//Funções para eventos dos botões

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

		_this.limparIdFarmacia = function limparIdFarmacia()
		{
			var laboratorioId = $("#farmacia_id");

			if(laboratorioId.val() != null && event.keyCode == 8)
			{
				laboratorioId.val('');
			}
		};		

		_this.limparIdMedicamentoPrecificados = function limparIdMedicamentoPrecificados()
		{
			var medicamentoId = $("#medicamentoPrecificado_id");
		
			if(medicamentoId.val() != null && event.keyCode == 8)
			{
				medicamentoId.val('');
			}
		};

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

			definirMascaras();

			$(".modal-body").on("focus", "#medicamento_precificado", _this.definirAutoCompleteMedicamentosPrecificado);
			$(".modal-body").on("focus", "#farmacia", _this.definirAutoCompleteFarmacias);
			$(".modal-body").on("keyup", "#medicamento_precificado", _this.getMedicamentoPrecificados);
			$(".modal-body").on("keyup", "#medicamento_precificado", _this.limparIdMedicamentoPrecificados);
			$(".modal-body").on("keyup", "#farmacia", _this.getMedicamentoPrecificados);
			$(".modal-body").on("keyup", "#farmacia", _this.limparIdFarmacia);
			$(" #medicamento_pessoal_form").submit(false);
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


