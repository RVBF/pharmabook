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

		//Defini as máscaras do formulário
		var definirMascaras = function definirMascaras()
		{
			$("#preco").maskMoney({
				symbol:'R$ ', 
				showSymbol:true,
				thousands:'.',
				decimal:',',
				symbolStay: true
			});	
		};

		var paraFloat = function paraFloat(moeda){

			moeda = moeda.replace(".","");

			moeda = moeda.replace(",",".");

			return parseFloat(moeda);
		};

		var  converterFloatEmReal = function converterFloatEmReal(valor)
		{
			var inteiro = null, decimal = null, c = null, j = null;
			var aux = new Array();

			valor = ""+valor;
			c = valor.indexOf(".",0);
			//encontrou o ponto na string
			if(c > 0)
			{
				//separa as partes em inteiro e decimal
				inteiro = valor.substring(0,c);
				decimal = valor.substring(c+1,valor.length);
			}
			else
			{
				inteiro = valor;
			}

			//pega a parte inteiro de 3 em 3 partes
			for (j = inteiro.length, c = 0; j > 0; j-=3, c++)
			{
				aux[c]=inteiro.substring(j-3,j);
			}

			//percorre a string acrescentando os pontos
			inteiro = "";
			for(c = aux.length-1; c >= 0; c--)
			{
				inteiro += aux[c]+'.';
			}
			//retirando o ultimo ponto e finalizando a parte inteiro

			inteiro = inteiro.substring(0,inteiro.length-1);

			decimal = parseInt(decimal);
			if(isNaN(decimal))
			{
				decimal = "00";
			}
			else
			{
				decimal = ""+decimal;

				if(decimal.length === 1)
				{
					decimal = "0"+decimal;
				}
			}
			valor = inteiro+","+decimal;
			
			return valor;
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
					"medicamento_id": {
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
					"medicamento_id": {
						required    : "O meicamento selecionado não corresponde a nenhum cadastrado na base de dados."
					},

					"pesquisar_laboratorio": {
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
					$('#medicamento_precificado_form input').prop("disabled", !b);
					$('#medicamento_precificado_form select').prop("disabled", !b);
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

			$('#pesquisar_medicamento').focus();
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
			$('#medicamento_precificado_form input').prop("disabled", true);
			$('#medicamento_precificado_form select').prop("disabled", true);
			$('.modal .modal-footer').empty();
			$('.modal .modal-title').html('Visualizar Medicamento');
			$('.modal .modal-footer').append('<button class="btn btn-success" id="alterar">Alterar</button>');
			$('.modal .modal-footer').append('<button class="btn btn-danger" id="remover">Remover</button>');
			$('.modal .modal-footer').append('<button class="btn btn-info" id="cancelar">Cancelar</button>');
		};

		var renderizarModoEdicao =  function renderizarModoEdicao()
		{
			$('#preco').prop("disabled", false);
			$('.modal .modal-footer').empty();
			$('.modal .modal-title').html('Editar Medicamento');
			$('.modal .modal-footer').append('<button class="btn btn-success" id="salvar">Salvar</button>');
			$('.modal .modal-footer').append('<button class="btn btn-danger" id="cancelar">Cancelar</button>');
		};

		var renderizarModoCadastro = function renderizarModoCadastro()
		{
			$('#medicamento_precificado_form input').prop("disabled", false);
			$('#medicamento_precificado_form select').prop("disabled", false);
			$('.modal .modal-footer').empty();
			$('.modal .modal-title').html('Precificar Medicamento');
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
				paraFloat($('#preco').val()),
				
				servicoFarmacia.criar(
					$('#farmacia').val()
				),					
				
				servicoMedicamento.criar(
					$('#medicamento_id').val()
				),

				servicoUsuario.criar(
					usuarioSessao.id
				)
		 	);
		};

		/* Busca o medicamento no servidor 
		  caso o usuário tenha preenchido os campos de pesquisa do
		  laborátorio ou Medicamento. 
		*/
		_this.getMedicamentoDoSistema = function getMedicamentoDoSistema(event)
		{
			var sucesso = function (data)
			{
				if(data[0] != null )
				{
					$("#medicamento_id").val(data[0].id);
				}
			};

			var medicamento = $("#pesquisar_medicamento").val();
			var laboratorioId = $("#laboratorio_id").val();

			if(laboratorioId != '' &&  medicamento  != '')
			{
				var  jqXHR = servicoMedicamento.getMedicamentoDoSistema(medicamento, laboratorioId);
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

				var laboratorioId = $("#laboratorio_id").val();

				var  jqXHR = servicoMedicamento.pesquisarMedicamento(request.term, laboratorioId);
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

				var medicamento = $("#pesquisar_laboratorio").val();

				var  jqXHR = servicoLaboratorio.pesquisarLaboratorio(request.term, medicamento);
				jqXHR.done(sucesso);
			};

			var preencherCombosDaPesquisa =  function preencherCombosDaPesquisa(event, ui)
			{
				$('.modal-body').find('#laboratorio_id').val(ui.item.id);
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

			popularSelectFarmacia(obj.farmacia.id);

			$("#id").val(obj.id || 0);
			$("#medicamento_id").val(obj.medicamento.id || 0);
			$("#laboratorio_id").val(obj.medicamento.laboratorio || 0);
			$('#farmacia option[text='+obj.farmacia.id+']').prop('selected', true);
			$("#pesquisar_medicamento").val(obj.medicamento.nomeComercial || '');
			$("#preco").val((obj.preco > 0) ? converterFloatEmReal(obj.preco) : '');

			if(obj.id == null)
			{
				console.log('entrei');
				renderizarModoCadastro();
			}
			else
			{
				if(obj.id > 0 )
				{
					var sucesso = function(data, textStatus, jqXHR)
					{
						$("#pesquisar_laboratorio").val(data.nome || 0);
					};
				
					var jqXHR = servicoLaboratorio.comId(obj.medicamento.laboratorio);

					jqXHR.done(sucesso);

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

		_this.limparIdLaboratorio = function limparIdLaboratorio()
		{
			var laboratorioId = $("#laboratorio_id");

			if(laboratorioId.val() != null && event.keyCode == 8)
			{
				laboratorioId.val('');
			}
		};		

		_this.limparIdMedicamento = function limparIdMedicamento()
		{
			var medicamentoId = $("#medicamento_id");
		
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

			$(".modal-body").on("focus", "#pesquisar_medicamento", _this.definirAutoCompleteMedicamento);
			$(".modal-body").on("focus", "#pesquisar_laboratorio", _this.definirAutoCompleteLaboratorio);
			$(".modal-body").on("keyup", "#pesquisar_medicamento", _this.getMedicamentoDoSistema);
			$(".modal-body").on("keyup", "#pesquisar_medicamento", _this.limparIdMedicamento);
			$(".modal-body").on("keyup", "#pesquisar_laboratorio", _this.getMedicamentoDoSistema);
			$(".modal-body").on("keyup", "#pesquisar_laboratorio", _this.limparIdLaboratorio);

			$(" #medicamento_precificado_form").submit(false);
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


