/**
 *  posologia.form.ctrl.js
 *  
 *  @author  Rafael Vinicius Barros Ferreira
 */
(function(window, app, $, toastr) 
{
	'use strict'; 
	 
	function ControladoraFormPosologia(servicoPosologia, servicoMedicamentoPessoal, controladoraEdicao) 
	{ // Model

		var _this = this;
		var _modoAlteracao = true;

		var _obj = null;

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
					$('#posologia_form input').prop("disabled", !b);
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

					var jqXHR = servicoPosologia.atualizar(obj);

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
					
					var jqXHR =  servicoPosologia.adicionar(obj);
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

		var irPraListagem = function irPraListagem() {
			controladoraEdicao.modoListagem(true); // Vai pro modo de listagem
		};

		var encerrarModal = function encerrarModal()
		{
			$('#posologia_modal').modal('hide');

			$('.modal').on('hidden.bs.modal', function(){
					$(this).find('#posologia_form')[0].reset();			
			});
		};
		
		var renderizarModoVisualizacao =  function renderizarModoVisualizacao()
		{
			$('#posologia_form input').prop("disabled", true);
			$('.modal .modal-footer').empty();
			$('.modal .modal-title').html('Visualizar Farmácia');
			$('.modal .modal-footer').append('<button class="btn btn-success" id="alterar">Alterar</button>');
			$('.modal .modal-footer').append('<button class="btn btn-danger" id="remover">Remover</button>');
			$('.modal .modal-footer').append('<button class="btn btn-info" id="cancelar">Cancelar</button>');
		};

		var renderizarModoEdicao =  function renderizarModoEdicao()
		{
			$('#posologia_form input').prop("disabled", false);
			$('.modal .modal-footer').empty();
			$('.modal .modal-title').html('Editar Farmácia');
			$('.modal .modal-footer').append('<button class="btn btn-success" id="salvar">Salvar</button>');
			$('.modal .modal-footer').append('<button class="btn btn-danger" id="cancelar">Cancelar</button>');
		};

		var renderizarModoCadastro = function renderizarModoCadastro()
		{
			$('#posologia_form input').prop("disabled", false);
			$('.modal .modal-footer').empty();
			$('.modal .modal-title').html('Cadastrar Farmácia');
			$('.modal .modal-footer').append('<button class="btn btn-success" id="cadastrar">Cadastrar</button>');
			$('.modal .modal-footer').append('<button class="btn btn-danger" id="cancelar">Cancelar</button>');
		};

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
		};	

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
		};	

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
		};		

		//Função para popular os dados do select de posologias
		var popularSelectMedicamentoPessoal  =  function popularSelectMedicamentoPessoal(valor = '')
		{
			var sucesso = function (resposta)
			{
				$("#medicamentoPessoal").empty();
				$("#medicamentoPessoal").append($('<option>', {
					value: '',
					text: 'Selecione'
				}));
			
				$.each(resposta.data, function(i ,item) {
					$("#medicamentoPessoal").append($('<option>', {
						value : item.id,
						text : item.medicamentoPrecificado.medicamento.nomeComercial
					}));
				});

				if(valor != ''  || valor > '')
				{
					$("#medicamentoPessoal").val(valor || '');
				}
			};

			var erro = function(resposta)
			{
				var mensagem = jqXHR.responseText || 'Erro ao popular de medicamentos pessoais';
				toastr.error( mensagem );
				return false;
			}

			var  jqXHR = servicoMedicamentoPessoal.todos();
			jqXHR.done(sucesso).fail(erro);
		};	

		//Função para popular os dados do select de posologias
		var popularMinutos  =  function popularMinutos(valor = '')
		{
			var array = Array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31);
			console.log('entrei');
			$("#periodicidade").empty();
			$("#periodicidade").append($('<option>', {
				value: '',
				text: 'Selecione'
			}));
			console.log(array);
			for(var i= 0; i < array.length; i++)
			{				
				$("#periodicidade").append($('<option>', {
					value : item[i],
					text : item[i]
				}));
			}

			if(valor != ''  || valor > '')
			{
				$("#periodicidade").val(valor || '');
			}
		};	

		//Função para popular os dados do select de posologias
		var popularHoras  =  function popularHoras(valor = '')
		{
			var array = Array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 16, 17, 18, 19, 20, 21, 22, 23, 24);

			$("#periodicidade").empty();
			$("#periodicidade").append($('<option>', {
				value: '',
				text: 'Selecione'
			}));
	
			$.each(array, function(i ,item) {
				$("#periodicidade").append($('<option>', {
					value : item[i],
					text : item[i]
				}));
			});

			if(valor != ''  || valor > '')
			{
				$("#periodicidade").val(valor || '');
			}
		};		

		//Função para popular os dados do select de posologias
		var popularSemanas  =  function popularSemanas(valor = '')
		{
			var array = Array(1, 2, 3, 4, 5, 6, 7);

			$("#periodicidade").empty();
			$("#periodicidade").append($('<option>', {
				value: '',
				text: 'Selecione'
			}));
	
			$.each(array, function(i ,item) {
				$("#periodicidade").append($('<option>', {
					value : item[i],
					text : item[i]
				}));
			});

			if(valor != ''  || valor > '')
			{
				$("#periodicidade").val(valor || '');
			}
		};		

		var popularMeses  =  function popularMeses(valor = '')
		{
			var array = Array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);

			$("#periodicidade").empty();
			$("#periodicidade").append($('<option>', {
				value: '',
				text: 'Selecione'
			}));
	
			$.each(array, function(i ,item) {
				$("#periodicidade").append($('<option>', {
					value : item[i],
					text : item[i]
				}));
			});

			if(valor != ''  || valor > '')
			{
				$("#periodicidade").val(valor || '');
			}
		};

		_this.modoAlteracao = function modoAlteracao(b) { // getter/setter
			if (b !== undefined) {
				_modoAlteracao = b;
			}
			return _modoAlteracao;
		};

		// Obtém o conteúdo atual do form como um objeto
		_this.conteudo = function conteudo()
		{
			return servicoPosologia.criar(
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

		_this.iniciarFormularioPosologia = function iniciarFormularioPosologia()
		{
			var opcoes = {
				show : true,
				keyboard : false,
				backdrop : true
			};

			var modal = $('#areaForm').find('#posologia_modal').modal(opcoes);

			$('#nome').focus();
		};

		// Desenha o objeto no formulário
		_this.desenhar = function desenhar(obj)
		{
			_obj = obj;
			_this.iniciarFormularioPosologia();

			popularSelectTiposDeUnidade();
			popularSelectTiposDeAdministracao();
			popularSelectTiposDePeriodicidade();
			popularSelectMedicamentoPessoal();

			// $('#id').val(obj.id || 0);
			// $('#nome').val(obj.nome ||'');
			// $('#telefone').val(obj.telefone ||'');
			// $('#endereco_id').val(obj.endereco.id || 0); 
			// $('#cep').val(obj.endereco.cep || ''); 
			// $('#logradouro').val(obj.endereco.logradouro || ''); 
			// $('#numero').val(obj.endereco.numero || ''); 
			// $('#complemento').val(obj.endereco.complemento || ''); 
			// $('#referencia').val(obj.endereco.referencia || ''); 
			// $('#bairro').val(obj.endereco.bairro || ''); 
			// $('#cidade').val(obj.endereco.cidade || ''); 
			// $('#estado').val(obj.endereco.estado || ''); 
			// $('#pais').val(obj.endereco.pais || '');

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

			$("#posologia_form").validate(criarOpcoesValidacao());
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
					servicoPosologia.remover( _obj.id ).done( sucesso ).fail( erro );
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


		_this.popularPeriodicidade = function popularPeriodicidade()
		{
			console.log($(this).html());
			if($(this).html() == "Horas")
			{
				$("#periodicidade").prop("disabled", false);
				popularHoras();
			}
			else
			{
				if($(this).html() == "Semanas")
				{
					$("#periodicidade").prop("disabled", false);
					popularSemanas();
				}
				else
				{
					if($(this.html == "Meses"))
					{
						$("#periodicidade").prop("disabled", false);
						popularMeses();
					}
					else
					{
						if($(this).html() == "Minutos")
						{
							console.log('entrei no if');
							$("#periodicidade").prop("disabled", false);
							popularMinutos();
						}
						else
						{
							if($(this).html() == "")
							{
								$("#periodicidade").prop("disabled", true);
							}
						}
					}
				}
			}
		};
		// Configura os eventos do formulário
		_this.configurar = function configurar() 
		{
			controladoraEdicao.adicionarEvento(function evento(b) {
				$('#areaForm').toggle(!b);
				if (!b) {
					$('input:first-child').focus(); // Coloca o foco no 1° input
				}
			});

			$(document).ready(function(){
				$('.modal').find(" #posologia_form").submit(false);
				$('.modal').find('.modal-body').on('change', '#tipo_periodicidade', _this.popularPeriodicidade);
				$('.modal').find('.modal-footer').on('click', '#cancelar', _this.cancelar);
				$('.modal').find('.modal-footer').on('click', '#cadastrar', _this.salvar);
				$('.modal').find('.modal-footer').on('click', '#salvar', _this.salvar);
				$('.modal').find('.modal-footer').on('click', '#alterar', _this.alterar);
				$('.modal').find('.modal-footer').on('click', '#remover', _this.remover);
				$('.modal').find('.modal-footer').on('click', '#visualizar', _this.visualizar);
			});
		};
	}; // ControladoraFormPosologia
	 
	// Registrando
	app.ControladoraFormPosologia = ControladoraFormPosologia;

})(window, app, jQuery, toastr);


