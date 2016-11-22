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

		var _obj = null;

		//Muda o estado da acção do usuário para modo listagem
		var irPraListagem = function irPraListagem() {
			controladoraEdicao.modoListagem(true); // Vai pro modo de listagem
		};

		//Defini as máscaras do formulário
		var definirMascaras = function definirMascaras()
		{
			$("#preco").maskMoney({symbol:'R$ ', 
				showSymbol:true, thousands:'.', decimal:',', symbolStay: true
			});	
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
					"pesquisar_medicamento": {
						required    : true,
					},

					"pesquisar_laboratorio": {
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
					"pesquisar_medicamento": {
						required    : "É preciso selecionar ao menos uma fármacia",
					},

					"pesquisar_laboratorio": {
						required    : "É preciso selecionar ao menos um laborátorio"
					},

					"farmacia": {
						required    : "Selecione ao menos uma farmácia."
					},

					"preco": {
						required    : "O campo preco do medicamento é obrigadorio."
					}        
				}
			};

			// Irá disparar quando a validação passar, após chamar o método validate().
			opcoes.submitHandler = function submitHandler(form)
			{
				// Habilita/desabilita os controles
				var controlesHabilitados = function controlesHabilitados(b)
				{
					$('#medicamento_precificado_form input').prop("disabled", !b);
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
					// irPraListagem();

					// encerrarModal();
				};
				
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
					var jqXHR = servicoMedicamentoPrecificado.atualizar(obj);
				}
				else
				{
					var jqXHR =  servicoMedicamentoPrecificado.adicionar(obj);
				}
				
				jqXHR
					.done(sucesso)
					.fail(erro)
					.always(terminado)
				;
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

			var modal = $('#medicamento_precificado_form').find('#medicamento_precificado_modal').modal(opcoes);

			$('#nome').focus();
		};

		//Encerra a modal
		var encerrarModal = function encerrarModal()
		{
			$('#medicamento_precificado_modal').modal('hide');

			$('.modal').on('hidden.bs.modal', function(){
					$(this).find('#medicamento_precificado_form')[0].reset();			
			});
		};
		
		//Funções para renderizar  o tipo de edição

		//Visualização
		var renderizarModoVisualizacao =  function renderizarModoVisualizacao()
		{
			$('#medicamento_form input').prop("disabled", true);
			$('.modal .modal-footer').empty();
			$('.modal .modal-title').html('Visualizar Medicamento Precificado');
			$('.modal .modal-footer').append('<button class="btn btn-success" id="alterar">Alterar</button>');
			$('.modal .modal-footer').append('<button class="btn btn-danger" id="remover">Remover</button>');
			$('.modal .modal-footer').append('<button class="btn btn-info" id="cancelar">Cancelar</button>');
		};

		//edição
		var renderizarModoEdicao =  function renderizarModoEdicao()
		{
			$('#medicamento_form input').prop("disabled", false);
			$('.modal .modal-footer').empty();
			$('.modal .modal-title').html('Editar Medicamento Precificado');
			$('.modal .modal-footer').append('<button class="btn btn-success" id="salvar">Salvar</button>');
			$('.modal .modal-footer').append('<button class="btn btn-danger" id="cancelar">Cancelar</button>');
		};

		//Cadastro
		var renderizarModoCadastro = function renderizarModoCadastro()
		{
			$('#medicamento_form input').prop("disabled", false);
			$('.modal .modal-footer').empty();
			$('.modal .modal-title').html('Cadastrar Medicamento Precificado');
			$('.modal .modal-footer').append('<button class="btn btn-success" id="cadastrar">Cadastrar</button>');
			$('.modal .modal-footer').append('<button class="btn btn-danger" id="cancelar">Cancelar</button>');
		};
		//Funções para renderizar o modo do formulário

		//Função para popular os dados do select de farmácias
		var popularSelectFarmacia  =  function popularSelectFarmacia()
		{
			var sucesso = function (resposta)
			{
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
			};

			var  jqXHR = servicoFarmacia.todos();
			jqXHR.done(sucesso);
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
			sessao = new app.ServicoSessao();
			return servicoMedicamentoPrecificado.criar(
				$('#id').val(),
				$('#preco').val(),
				
				servicoFarmacia,criar(
					$('#farmacia').val()
				),

				servicoUsuario.criar(
					sessao.getSessao().id
				),					
				
				servicoMedicamento.criar(
					$('#id_medicamento').val()
				),
		 	);
		};

		  /* Busca o medicamento no servidor 
		  caso o usuário tenha preenchido os campos de pesquisa do
		  laborátorio ou Medicamento. */
		_this.buscarInformacoesFinaisDeMedicamento = function buscarInformacoesFinaisDeMedicamento()
		{
			var sucesso = function (data)
			{
				if(data != null )
				{
					$("#medicamento_id").val(data[0].id);
				}
			};

			var pesquisarMedicamento = $("#pesquisar_medicamento");
			var pesquisarLaboratorio = $("#pesquisar_laboratorio");

			if(pesquisarLaboratorio.val() != '' &&  pesquisarLaboratorio.val()  != '')
			{
				var  jqXHR = servicoMedicamento.getMedicamentoComNomeELaboratorio(pesquisarMedicamento.val(), pesquisarLaboratorio.val());
				jqXHR.done(sucesso);
			}
		};

		//Pesquisa Medicamentos na Base de dados da Anvisa.
		_this.definirAutoCompleteMedicamento = function definirAutoCompleteMedicamento()
		{
			var elemento = $(this);

			var efetuarRequisaoAutoComplete = function efetuarRequisaoAutoComplete(request, response) {
				var sucesso = function (data)
				{
					response(data);
				};

				var erro = function erro( jqXHR, textStatus, errorThrown ) {
					var mensagem = jqXHR.responseText || 'Erro ao pesquisar medicamento.';
					toastr.error( mensagem );
				};

				var laboratorio = $("#pesquisar_laboratorio").val();

				var  jqXHR = servicoMedicamento.pesquisarMedicamento(request.term, laboratorio);
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
		_this.definirAutoCompleteLaboratorio = function definirAutoCompleteLaboratorio()
		{
			var elemento = $(this);

			var efetuarRequisaoAutoComplete = function efetuarRequisaoAutoComplete(request, response)
			{
				var sucesso = function (data)
				{
					response(data);
				};

				var medicamento = $("#pesquisar_medicamento").val();

				var  jqXHR = servicoLaboratorio.pesquisarLaboratorio(request.term, medicamento);
				jqXHR.done(sucesso);
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
				}
			};

			elemento.autocomplete(opcoesAutoComplete);						
		};

		// Desenha o objeto no formulário
		_this.desenhar = function desenhar(obj, operacao = '')
		{
			_obj = obj;
			
			iniciaModalDeCadastro();

			popularSelectFarmacia();

			if(operacao == 'Cadastrar')
			{
				renderizarModoCadastro();
			}
			else
			{
				if(operacao == 'Visualizar')
				{
					renderizarModoVisualizacao();
				}
				else
				{
					renderizarModoEdicao();
				}
			}

			$("#id").val(obj.id || 0);
			$("#medicamento_id").val(obj.medicamento.id || 0);
			$("#medicamento_id").val(obj.medicamento.id || 0);
			$("#laboratorio_id").val(obj.laboratorio.id || 0);
			$("#pesquisar_medicamento").val(obj.medicamento.nome || '');
			$("#pesquisar_laboratorio").val(obj.laboratorio.nome || '');
			
			$("#precificar_medicamento").find("#farmacia").val(obj.farmacia.id || 0);
			
			if(operacao == 'visualizar')
			{
				renderizarModoVisualizacao();
				$("#precificar_medicamento").removeClass('hide');
			}
			else
			{
				if(operacao == 'cadastrar')
				{
					renderizarModoCadastro();
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

			$('.ui-autocomplete-input').css('width', $('#pesquisar_medicamento').width());
			$('.ui.autocomplete-input').css('width', $('#pesquisar_laboratorio').width());

			definirMascaras();
			$(".modal").find(".modal-body").on("focus", "#pesquisar_medicamento", _this.definirAutoCompleteMedicamento);
			$(".modal").find(".modal-body").on("focus", "#pesquisar_laboratorio", _this.definirAutoCompleteLaboratorio);
			$(".modal").find(".modal-body").on("keyup", "#pesquisar_medicamento", _this.buscarInformacoesFinaisDeMedicamento);
			$(".modal").find(".modal-body").on("keyup", "#pesquisar_laboratorio", _this.buscarInformacoesFinaisDeMedicamento);
		
			$(".modal").find(" #medicamento_precificado_form").submit(false);
			$(".modal").find(".modal-footer").on("click", "#cancelar", _this.cancelar);
			$(".modal").find(".modal-footer").on("click", "#cadastrar", _this.salvar);
			$(".modal").find(".modal-footer").on("click", "#salvar", _this.salvar);
			$(".modal").find(".modal-footer").on("click", "#alterar", _this.alterar);
			$(".modal").find(".modal-footer").on("click", "#remover", _this.remover);
			$(".modal").find(".modal-footer").on("click", "#visualizar", _this.visualizar);
		};
	}; // ControladoraFormMedicamentoPrecificado
	 
	// Registrando
	app.ControladoraFormMedicamentoPrecificado = ControladoraFormMedicamentoPrecificado;

})(window, app, jQuery, toastr);


