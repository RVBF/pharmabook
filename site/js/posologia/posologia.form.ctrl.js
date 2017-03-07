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

		var _tempoUnidades = {	SEGUNDO : 'Segundo',
			MINUTO : 'Minuto',
			HORA : 'Hora',
			DIA : 'Dia',
			SEMANA : 'Semana',
			MES : 'Mês'
		};

		var _tempoUnidadesPlural = {	SEGUNDO : 'Segundos',
			MINUTO : 'Minutos',
			HORA : 'Horas',
			DIA : 'Dias',
			SEMANA : 'Semanas',
			MES : 'Mêses'
		};

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
			$('#posologia_modal .modal-footer').empty();
			$('#posologia_modal .modal-title').html('Cadastrar Posologia');
			$('#posologia_modal .modal-footer').append('<button class="btn btn-success" id="cadastrar">Cadastrar</button>');
			$('#posologia_modal .modal-footer').append('<button class="btn btn-danger" id="cancelar">Cancelar</button>');
		};

		//Função para popular os dados do select de posologias
		var popularSelectTiposDePeriodicidade  =  function popularSelectTiposDePeriodicidade(tipoPeriodicidade = '')
		{
			var elementoTipoPeriodicidade =  $('#tipo_periodicidade');

			var sucesso = function sucesso(resposta)
			{
				elementoTipoPeriodicidade.empty().trigger('change');

				$.each(resposta, function(i ,item)
				{
					var opcao = new Option(item, i);
 					elementoTipoPeriodicidade.append(opcao);
				});

				if(tipoPeriodicidade != null && tipoPeriodicidade != undefined && tipoPeriodicidade != '')
				{
					elementoTipoPeriodicidade.val(tipoPeriodicidade);
				}

				elementoTipoPeriodicidade.trigger('change');
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
				$('#periodicidade').val(),
				$('#tipo_periodicidade').val(),
				servicoMedicamentoPessoal.criar(
					$('#medicamento_Pessoal_id').val()
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

			popularSelectTiposDePeriodicidade(app.key_array(_tempoUnidadesPlural ,obj.tipoPeriodicidade));
			$("#id").val(obj.id || 0);
			$("#medicamento_Pessoal_id").val(obj.medicamentoPessoal.id || 0);
			$("#unidade").html(obj.medicamentoPessoal.tipoUnidade || '');
			$("#dose").val(obj.dose || '');
			$("#descricao").val(obj.descricao || '');
			$("#periodicidade").val(obj.periodicidade || '');
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
				message	: _obj.medicamentoPessoal.medicamento.nomeComercial,
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


