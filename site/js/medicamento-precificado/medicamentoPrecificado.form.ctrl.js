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

		var irPraListagem = function irPraListagem() {
			controladoraEdicao.modoListagem(true); // Vai pro modo de listagem
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
					"nome": {
						required    : true,
						rangelength : [ 2, 50 ]
					},

					"logradouro": {
						required    : true
					},  

					"numero": {
						required    : true
					},				

					"bairro": {
						required    : true,
					},


					"estado": {
						required    : true,
					},

					"pais": {
						required    : true,
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

					"numero": {
						required    : "O campo número é obrigatório."
					},

					"bairro": {
						required    : "O campo bairro é obrigadorio."
					},					

					"estado": {
						required    : "O campo estado é obrigadorio."
					},        					

					"pais": {
						required    : "O campo pais é obrigadorio."
					}         
				}
			};

			// Irá disparar quando a validação passar, após chamar o método validate().
			opcoes.submitHandler = function submitHandler(form)
			{
				// Habilita/desabilita os controles
				var controlesHabilitados = function controlesHabilitados(b)
				{
					$('#medicamento_form input').prop("disabled", !b);
					$('#cadastrar').prop("disabled", !b);
					$('#salvar').prop("disabled", !b);
					$('#visualizar').prop("disabled", !b);
					$('#cancelar').prop("disabled", !b);
				};
				
				controlesHabilitados(false);  

				var sucesso = function sucesso(data, textStatus, jqXHR)
				{
					encerrarModal();

					toastr.success('Salvo');

					irPraListagem();

					encerrarModal();
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
					var jqXHR = servicoMedicamnentoPrecificado.atualizar(obj);
				}
				else
				{
					var jqXHR =  servicoMedicamnentoPrecificado.adicionar(obj);
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

		var encerrarModal = function encerrarModal()
		{
			$('#medicamento_precificado_modal').modal('hide');

			$('.modal').on('hidden.bs.modal', function(){
					$(this).find('#medicamento_form')[0].reset();			
			});
		};
		
		//Funções para renderizar o modo do formulário
		var renderizarModoVisualizacao =  function renderizarModoVisualizacao()
		{
			$('#medicamento_form input').prop("disabled", true);
			$('.modal .modal-footer').empty();
			$('.modal .modal-title').html('Visualizar Medicamento Precificado');
			$('.modal .modal-footer').append('<button class="btn btn-success" id="alterar">Alterar</button>');
			$('.modal .modal-footer').append('<button class="btn btn-danger" id="remover">Remover</button>');
			$('.modal .modal-footer').append('<button class="btn btn-info" id="cancelar">Cancelar</button>');
		};

		var renderizarModoEdicao =  function renderizarModoEdicao()
		{
			$('#medicamento_form input').prop("disabled", false);
			$('.modal .modal-footer').empty();
			$('.modal .modal-title').html('Editar Medicamento Precificado');
			$('.modal .modal-footer').append('<button class="btn btn-info" id="visualizar">Visualizar</button>');
			$('.modal .modal-footer').append('<button class="btn btn-success" id="salvar">Salvar</button>');
			$('.modal .modal-footer').append('<button class="btn btn-danger" id="cancelar">Cancelar</button>');
		};

		var renderizarModoCadastro = function renderizarModoCadastro()
		{
			$('#medicamento_form input').prop("disabled", false);
			$('.modal .modal-footer').empty();
			$('.modal .modal-title').html('Cadastrar Medicamento Precificado');
			$('.modal .modal-footer').append('<button class="btn btn-success" id="cadastrar">Cadastrar</button>');
			$('.modal .modal-footer').append('<button class="btn btn-danger" id="cancelar">Cancelar</button>');
		};
		//Funções para renderizar o modo do formulário

		var criarOpcoesParaSelectFarmacia  =  function criarOpcoesParaSelectFarmacia()
		{
			var sucesso = function (resposta)
			{
				$("#dados_medicamento").find("#farmacia").append($('<option>', {
					value: '',
					text: 'Selecione'
				}));

				$.each(resposta.data, function(i ,item) {
					$("#dados_medicamento").find("#farmacia").append($('<option>', {
						value: item.id,
						text: item.nome
					}));
				});
			};

			var  jqXHR = servicoFarmacia.todos();
			jqXHR.done(sucesso);
		}

		var removerdadosDoFormulario = function removerdadosDoFormulario(elemento)
		{

			var limparInput = function limparInput(elemento)
			{
				elemento.each(function(index) {
     				 this.value = "";
		 		})
			};

			$("#dados_medicamento").addClass('hide');
			limparInput($("#dados_medicamento:input"));
			$("#dados_medicamento").find("#farmacia").val('');
			limparInput($(this).parent("input"));
		};

		_this.verificarExclusaoCaracter  =  function verificarExclusaoCaracter(event)
		{
			if(event.keyCode == 8)
			{
				removerdadosDoFormulario();
			}
		}

		_this.modoAlteracao = function modoAlteracao(b) { // getter/setter
			if (b !== undefined) {
				_modoAlteracao = b;
			}
			return _modoAlteracao;
		};

		// Obtém o conteúdo atual do form como um objeto
		_this.conteudo = function conteudo()
		{
			return servicoMedicamnentoPrecificado.criar(
				$('#id').val(),
				$('#preco').val(),
				
				servicoFarmacia,criar(
					$('#id_farmacia').val()
				),

				servicoUsuario.criar(
					$('#id_usuario').val()
				),					
				
				servicoMedicamento.criar(
					$('#id_medicamento').val()
				),
				$('#dataCriacao').val(),
				$('#dataAtualizacao').val()
		 	);
		};

		_this.verificarCamposPreenchidos = function verificarCamposPreenchidos()
		{
			var sucesso = function (data)
			{
				$("#dados_medicamento").find("#medicamento_id").val(data[0].id);
				$("#pesquisar_codico_medicamento").val(data[0].ean);
				$("#dados_medicamento").find("#registro").val(data[0].registro);
				$("#dados_medicamento").find("#preco_fabrica").val(data[0].precoFabrica);
				$("#dados_medicamento").find("#preco_maximo_consumidor").val(data[0].precoMaximoConsumidor);
				$("#dados_medicamento").removeClass('hide');
			};

			var pesquisarMedicamento = $("#pesquisar_medicamento");
			var pesquisarLaboratorio = $("#pesquisar_laboratorio");

			if(pesquisarLaboratorio.val() != '' &&  pesquisarLaboratorio.val()  != '')
			{
				var  jqXHR = servicoMedicamento.getMedicamentoComNomeELaboratorio(pesquisarMedicamento.val(), pesquisarLaboratorio.val());
				jqXHR.done(sucesso);
			}
		};

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
				$("#principio").val(ui.item.principio)
				$("#principio_id").val(ui.item.principioId)				
				$("#classe").val(ui.item.classe)
				$("#classe_id").val(ui.item.classeId)
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

			$("#id").val(obj.id || 0);
			$("#dados_medicamento").find("#medicamento_id").val(obj.medicamento.id || 0);
			$("#pesquisar_codico_medicamento").val(obj.medicamento.ean || '');
			$("#dados_medicamento").find("#registro").val(obj.medicamento.registro);
			$("#dados_medicamento").find("#preco_fabrica").val(obj.medicamento.precoFabrica || '0,00');
			$("#dados_medicamento").find("#preco_maximo_consumidor").val(obj.medicamento.precoFabrica || '0,00');
			
			criarOpcoesParaSelectFarmacia();
			$("#dados_medicamento").find("#farmacia").val(obj.farmacia.id || 0);
			
			if(operacao == 'visualizar')
			{
				renderizarModoVisualizacao();
				$("#dados_medicamento").removeClass('hide');
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
		_this.salvar = function salvar(event)
		{
			// Ao validar e tudo estiver correto, é disparado o método submitHandler(),
			// que é definido nas opções de validação.

			$("#medicamento_precificado_form").validate(criarOpcoesValidacao());
		};

		_this.cancelar = function cancelar(event) {
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
					servicoMedicamnentoPrecificado.remover( _obj.id ).done( sucesso ).fail( erro );
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
			// controladoraEdicao.adicionarEvento(function evento(b) {
			// 	$('#areaForm').toggle(!b);
				
			// 	if (!b)
			// 	{
			// 		$('input:first-child').focus(); // Coloca o foco no 1° input
			// 	}
			// });
			
			$(".modal").find('.modal-body').on("keyup", "#pesquisar_medicamento", _this.verificarExclusaoCaracter);
			$(".modal").find('.modal-body').on("keyup", "#pesquisar_laboratorio", _this.verificarExclusaoCaracter);
			$(".modal").find(".modal-body").on("focus", "#pesquisar_medicamento", _this.definirAutoCompleteMedicamento);
			$(".modal").find(".modal-body").on("focus", "#pesquisar_laboratorio", _this.definirAutoCompleteLaboratorio);
			$(".modal").find(".modal-body").on("keyup", "#pesquisar_medicamento", _this.verificarCamposPreenchidos);
			$(".modal").find(".modal-body").on("keyup", "#pesquisar_laboratorio", _this.verificarCamposPreenchidos);
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


