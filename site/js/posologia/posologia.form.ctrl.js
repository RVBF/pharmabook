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
		var _modoVisualizacao = true;

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
					"medicamentoPessoal": {
						required    : true
					},

					"administracao": {
						required    : true
					}, 		

					"tipo_unidade": {
						required    : true
					},

					"dose": {
						required    : true
					},					

					"tipo_periodicidade": {
						required    : true
					},					

					"periodicidade": {
						required    : true
					},				
				},

				messages: 
				{
					"medicamentoPessoal": {
						required    : "O campo medicamento pessoal é obrigátorio, selecione uma opção."
					},					

					"administracao": {
						required    : "O campo administração do medicamento é obrigátorio, selecione uma opção."
					},

					"tipo_unidade": {
						required    : "O campo tipo unidade é obrigátorio, selecione uma opção."
					},

					"dose": {
						required    : "O campo é obrigátorio."
					},					

					"tipo_periodicidade": {
						required    : "O campo tipo de periodicidade é obrigátorio, selecione uma opção."
					},

					"periodicidade": {
						required    : "O campo é obrigátorio."
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
			$('#posologia_form select').prop('disabled', true);
			$('#posologia_form textarea').prop('disabled', true);
			$('.modal .modal-footer').empty();
			$('.modal .modal-title').html('Visualizar Posologia');
			$('.modal .modal-footer').append('<button class="btn btn-success" id="alterar">Alterar</button>');
			$('.modal .modal-footer').append('<button class="btn btn-danger" id="remover">Remover</button>');
			$('.modal .modal-footer').append('<button class="btn btn-info" id="cancelar">Cancelar</button>');
			_this.modoVisualizacao(false);
		};

		var renderizarModoEdicao =  function renderizarModoEdicao()
		{
			$('#posologia_form input').prop("disabled", false);
			$('#posologia_form select').prop('disabled', false);
			$('#posologia_form textarea').prop('disabled', false);
			$('#posologia_form #dose').prop('disabled', false);
			$('#posologia_form #periodicidade').prop('disabled', false);
			$('.modal .modal-footer').empty();
			$('.modal .modal-title').html('Editar Posologia');
			$('.modal .modal-footer').append('<button class="btn btn-success" id="salvar">Salvar</button>');
			$('.modal .modal-footer').append('<button class="btn btn-danger" id="cancelar">Cancelar</button>');
		};

		var renderizarModoCadastro = function renderizarModoCadastro()
		{
			$('#posologia_form input').prop("disabled", false);
			$('#posologia_form #dose').prop('disabled', true);
			$('#posologia_form #periodicidade').prop('disabled', true);
			$('.modal .modal-footer').empty();
			$('.modal .modal-title').html('Cadastrar Posologia');
			$('.modal .modal-footer').append('<button class="btn btn-success" id="cadastrar">Cadastrar</button>');
			$('.modal .modal-footer').append('<button class="btn btn-danger" id="cancelar">Cancelar</button>');
		};

		//Função para popular os dados do select de Posologias
		var popularSelectTiposDeAdministracao  =  function popularSelectTiposDeAdministracao(valor = '')
		{
			var sucesso = function (resposta)
			{
				$("#administracao").empty();
				$("#administracao").append($('<option>', {
					value: '',
					text: 'Selecione'
				}));
		
				$.each(resposta, function(i ,item) {
					$("#administracao").append($('<option>', {
						value : item.nome,
						text : item.nome
					}));
				});

				if(valor != ''  || valor > '')
				{
					$("#administracao").val(valor || '');
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
						value : item.nome,
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
						value : item.nome,
						text : item.nome
					}));
				});

				// if(valor != undefined)
				// {
				// 	if( valor == "Minutos")
				// 	{
				// 		$("#periodicidade").prop("disabled", true);
				// 		popularMinutos(_obj.periodicidade);
				// 	}
				// 	else
				// 	{
				// 		if( valor == "Horas")
				// 		{
				// 		$("#posologia_form #periodicidade").prop('disabled', true);
				// 			popularHoras(_obj.periodicidade);
				// 		}
				// 		else
				// 		{
				// 			if($(this.val == "popularSemanas"))
				// 			{
				// 			$("#posologia_form #periodicidade").prop('disabled', true);
				// 				popularSemanas(_obj.periodicidade);
				// 			}
				// 			else
				// 			{
				// 				if( valor == "meses")
				// 				{
				// 				$("#posologia_form #periodicidade").prop('disabled', true);
				// 					popularMeses(_obj.periodicidade);
				// 				}
				// 				else
				// 				{
				// 					if( valor == "")
				// 					{
				// 						$("#posologia_form #periodicidade").prop('disabled', true);
				// 					}
				// 				}
				// 			}
				// 		}
				// 	}					

				// 	$("#tipo_periodicidade").val(valor || '');
				// }
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

				$("#medicamentoPessoal").val(valor);
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
			var minutos = [];

			for(var i=0; i < 60 ; i++)
			{
				minutos[i] = i+1;
			}			

			$("#periodicidade").empty();
			$("#periodicidade").append($('<option>', {
				value: '',
				text: 'Selecione'
			}));

			for(var i= 0; i < minutos.length; i++)
			{				
				$("#periodicidade").append($('<option>', {
					value : minutos[i],
					text : minutos[i]
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
			var horas = [];

			for(var i=0; i < 24 ; i++)
			{
				horas[i] = i+1;
			}

			$("#periodicidade").empty();
			$("#periodicidade").append($('<option>', {
				value: '',
				text: 'Selecione'
			}));
	
			for(var i = 0; i< horas.length; i++)
			{
				$("#periodicidade").append($('<option>', {
					value : horas[i],
					text : horas[i]
				}));
			}

			if(valor != ''  || valor > '')
			{
				$("#periodicidade").val(valor || '');
			}
		};		

		//Função para popular os dados do select de posologias
		var popularSemanas  =  function popularSemanas(valor = '')
		{
			var diasDaSemana = [];

			for(var i=0; i < 7 ; i++)
			{
				diasDaSemana[i] = i+1;
			}

			$("#periodicidade").empty();
			$("#periodicidade").append($('<option>', {
				value: '',
				text: 'Selecione'
			}));
	
			for(var i = 0; i< diasDaSemana.length; i++)
			{
				$("#periodicidade").append($('<option>', {
					value : diasDaSemana[i],
					text : diasDaSemana[i]
				}));
			}

			if(valor != ''  || valor > '')
			{
				$("#periodicidade").val(valor || '');
			}
		};		

		var popularMeses  =  function popularMeses(valor = '')
		{
			var meses = [];

			for(var i=0; i < 12 ; i++)
			{
				meses[i] = i+1;
			}

			$("#periodicidade").empty();
			$("#periodicidade").append($('<option>', {
				value: '',
				text: 'Selecione'
			}));
	
			for(var i = 0; i< meses.length; i++)
			{
				$("#periodicidade").append($('<option>', {
					value : meses[i],
					text : meses[i]
				}));
			}
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

		_this.modoVisualizacao = function modoVisualizacao(b) { // getter/setter
			if (b !== undefined) {
				_modoVisualizacao = b;
			}
			return _modoVisualizacao;
		};

		// Obtém o conteúdo atual do form como um objeto
		_this.conteudo = function conteudo()
		{
			return servicoPosologia.criar(
				$('#id').val(),
				$('#dose').val(),
				$('#descricao').val(),
				$('#administracao').val(),
				$('#periodicidade').val(),
				$('#tipo_unidade').val(),
				$('#tipo_periodicidade').val(),
				servicoMedicamentoPessoal.criar(
					$('#medicamentoPessoal').val()
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

			$("#id").val( obj.id || 0);
			$("#administracao").val( obj.administracao || '');
			$("#dose").val( obj.dose || '');
			$("#periodicidade").val( obj.descricao || '');

			popularSelectTiposDeUnidade(obj.tipoUnidadeDose);
			popularSelectTiposDeAdministracao(obj.administracao);
			popularSelectTiposDePeriodicidade(obj.tipoPeriodicidade);
			popularSelectMedicamentoPessoal(obj.medicamentoPessoal.id);

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

			console.log(_obj);

			BootstrapDialog.show( {
				type	: BootstrapDialog.TYPE_DANGER,
				title	: 'Remover?',
				message	: _obj.medicamentoPessoal.medicamentoPrecificado.medicamento.nomeComercial,
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
			if($(this).val() == "Minutos")
			{
				$("#periodicidade").prop("disabled", false);
				popularMinutos(_obj.periodicidade);
			}
			else
			{
				if($(this).val() == "Horas")
				{
				$("#posologia_form #periodicidade").prop('disabled', false);
					popularHoras(_obj.periodicidade);
				}
				else
				{
					if($(this.val == "popularSemanas"))
					{
					$("#posologia_form #periodicidade").prop('disabled', false);
						popularSemanas(_obj.periodicidade);
					}
					else
					{
						if($(this).val() == "meses")
						{
						$("#posologia_form #periodicidade").prop('disabled', false);
							popularMeses(_obj.periodicidade);
						}
						else
						{
							if($(this).val() == "")
							{
								$("#posologia_form #periodicidade").prop('disabled', true);
							}
						}
					}
				}
			}
		};

		_this.verificarTipoUnidade = function verificarTipoUnidade()
		{
			if($(this).val() == "")
			{
				$("#posologia_form #dose").prop('disabled', true);
			}
			else
			{
				$("#posologia_form #dose").prop('disabled', false);
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
				$('.modal').find('.modal-body').on('change', '#tipo_unidade', _this.verificarTipoUnidade);
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


