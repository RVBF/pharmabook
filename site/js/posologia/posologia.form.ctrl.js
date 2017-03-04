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
				rules:
				{
					"tipo_periodicidade": {
						required    : true
					},

					"periodicidade": {
						required    : true
					},

					"dose": {
						required    : true
					},
				},

				messages:
				{
					"tipo_periodicidade": {
						required    : "O campo tipo de periodicidade é obrigátorio, corrija os dados e tente novamente."
					},

					"periodicidade": {
						required    : "O campo periodicidade é obrigátorio, corrija os dados e tente novamente."
					},

					"dose": {
						required    : "O campo dose é obrigátorio, corrija os dados e tente novamente."
					}
				}
			};

			// Irá disparar quando a validação passar, após chamar o método validate().
			opcoes.submitHandler = function submitHandler(form)
			{
				// Habilita/desabilita os controles
				var controlesHabilitados = function controlesHabilitados(b)
				{
					app.desabilitarFormulario(b);
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

			$('#posologia_modal').on('hidden.bs.modal', function(){
					$(this).find('#posologia_form')[0].reset();
			});
		};

		var renderizarModoVisualizacao =  function renderizarModoVisualizacao()
		{
			app.desabilitarFormulario();
			$('#posologia_modal .modal-footer').empty();
			$('#posologia_modal .modal-title').html('Visualizar Posologia');
			$('#posologia_modal .modal-footer').append('<button class="btn btn-success" id="alterar">Alterar</button>');
			$('#posologia_modal .modal-footer').append('<button class="btn btn-danger" id="remover">Remover</button>');
			$('#posologia_modal .modal-footer').append('<button class="btn btn-info" id="cancelar">Cancelar</button>');
			_this.modoVisualizacao(false);
		};

		var renderizarModoEdicao =  function renderizarModoEdicao()
		{
			app.desabilitarFormulario(false);
			$('#posologia_modal .modal-footer').empty();
			$('#posologia_modal .modal-title').html('Editar Posologia');
			$('#posologia_modal .modal-footer').append('<button class="btn btn-success" id="salvar">Salvar</button>');
			$('#posologia_modal .modal-footer').append('<button class="btn btn-danger" id="cancelar">Cancelar</button>');
		};

		var renderizarModoCadastro = function renderizarModoCadastro()
		{
			app.desabilitarFormulario(false);
			$('#posologia_form #dose').prop('disabled', true);
			$('#posologia_form #periodicidade').prop('disabled', true);
			$('#posologia_modal .modal-footer').empty();
			$('#posologia_modal .modal-title').html('Cadastrar Posologia');
			$('#posologia_modal .modal-footer').append('<button class="btn btn-success" id="cadastrar">Cadastrar</button>');
			$('#posologia_modal .modal-footer').append('<button class="btn btn-danger" id="cancelar">Cancelar</button>');
		};

		//Função para popular os dados do select de posologias
		var popularSelectTiposDePeriodicidade  =  function popularSelectTiposDePeriodicidade(valor = '')
		{
			var elementoTipoPeriodicidade =  $('#tipo_periodicidade');

			var sucesso = function sucesso(resposta)
			{
				elementoTipoPeriodicidade.empty().trigger('change');

				var opcao = new Option('Selecione', '');
 				elementoTipoPeriodicidade.append(opcao);

				$.each(resposta, function(i ,item)
				{
					var opcao = new Option(item, i);
 					elementoTipoPeriodicidade.append(opcao);
				});

				if(valor != null || valor != undefined)
				{
					elementoTipoPeriodicidade.val(unidadeMedida);
				}
			};

			var erro = function(resposta)
			{
				var mensagem = jqXHR.responseText || 'Erro ao popular select de tipos de periodicidade.';
				toastr.error( mensagem );
				return false;
			}

			var  jqXHR = servicoPosologia.tempoUnidades();
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

			var modal = $('#areaFormPosologia').find('#posologia_modal').modal(opcoes);

			$('#nome').focus();
		};

		// Desenha o objeto no formulário
		_this.desenhar = function desenhar(obj)
		{
			_obj = obj;
			_this.iniciarFormularioPosologia();

			// $("#id").val( obj.id || 0);
			// $("#medicamento_pessoal_id").val( obj.medicamentoPessoal.id || 0);
			// $("#dose").val( obj.dose || '');
			// $("#periodicidade").val( obj.descricao || '');

			popularSelectTiposDePeriodicidade(obj.tipoPeriodicidade);

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

		// Configura os eventos do formulário
		_this.configurar = function configurar()
		{
			app.definirMascarasPadroes();

			controladoraEdicao.adicionarEvento(function evento(b) {
				$('#areaForm').toggle(!b);
				if (!b) {
					$('input:first-child').focus(); // Coloca o foco no 1° input
				}
			});

			$('#posologia_modal').find(" #posologia_form").submit(false);
			$('#posologia_modal').find('.modal-body').on('change', '#tipo_periodicidade', _this.popularPeriodicidade);
			$('#posologia_modal').find('.modal-footer').on('click', '#cancelar', _this.cancelar);
			$('#posologia_modal').find('.modal-footer').on('click', '#cadastrar', _this.salvar);
			$('#posologia_modal').find('.modal-footer').on('click', '#salvar', _this.salvar);
			$('#posologia_modal').find('.modal-footer').on('click', '#alterar', _this.alterar);
			$('#posologia_modal').find('.modal-footer').on('click', '#remover', _this.remover);
			$('#posologia_modal').find('.modal-footer').on('click', '#visualizar', _this.visualizar);
		};
	}; // ControladoraFormPosologia

	// Registrando
	app.ControladoraFormPosologia = ControladoraFormPosologia;

})(window, app, jQuery, toastr);


