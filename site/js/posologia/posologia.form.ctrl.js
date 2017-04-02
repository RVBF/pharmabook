/**
 *  posologia.form.ctrl.js
 *
 *  @author  Rafael Vinicius Barros Ferreira
 */
(function(window, app, $, toastr)
{
	'use strict';

	function ControladoraFormPosologia(servicoPosologia, servicoMedicamentoPessoal)
	{ // Model

		var _this = this;
		var _obj = null;
		_this.formulario = null;
		_this.router = window.router;
		_this.alterar = false;
		_this.botaoCadastrar = $('#cadastrar');
		_this.botaoAlterar = $('#alterar');
		_this.botaoRemover = $('#remover');
		_this.botaoCancelar = $('#cancelar');
		_this.modo = $('#modo');
		_this.id = null;

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

		var pegarId = function pegarId(url, palavra)
		{
			// Terminando com "ID/palavra"
			var regexS = palavra+'+\/[0-9]+\/';
			var regex = new RegExp(regexS);
			var resultado = url.match(regex);

			if (!resultado || resultado.length < 1)
			{
				return 0;
			}

			var array = resultado[0].split('/');

			return array[1];
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
			regras.submitHandler = function submitHandler(form)
			{
				// Habilita/desabilita os controles
				var controlesHabilitados = function controlesHabilitados(b)
				{
					desabilitarFormulario(!b);
				};

				controlesHabilitados(false);

				var erro = function erro(jqXHR, textStatus, errorThrown)
				{
					var mensagem = jqXHR.responseText;
					$('#msg').empty().append('<div class="error" >' + mensagem + '</div>');
				};

				var terminado = function terminado()
				{
					controlesHabilitados(true);
				};

				var sucesso = function sucesso(data, textStatus, jqXHR)
				{
					toastr.success('Salvo');
					_this.redirecionarParaListagem();
				};

				var obj = _this.conteudo();
				var jqXHR = _this.alterar ? servicoMedicamentoPessoal.atualizar(obj) : servicoMedicamentoPessoal.adicionar(obj);
				jqXHR.done(sucesso).fail(erro).always(terminado);
			}; // submitHandler

			return regras;
		};
		// criarOpcoesValidacao


		// Encaminha o usuário para a listagem
		_this.redirecionarParaListagem = function redirecionarParaListagem()
		{
			router.navigate('/medicamentos-pessoais/');
		};

		// Encaminha o usuário para a edição
		_this.redirecionarParaEdicao = function redirecionarParaEdicao()
		{
			router.navigate('/medicamentos-pessoais/editar/'+ _obj.id +'/');
		}

		_this.definirForm = function definirForm()
		{
			var url = window.location.href;

			if(url.search('editar') != -1)
			{
				_this.alterar = true;
				_this.botoesDeEdicao();
				_this.renderizarModoEdicao();
			}
			else if(url.search('visualizar') != -1)
			{
				_this.botoesDeVisualizacao();
				_this.renderizarModoVisualizacao();
			}
			else if(url.search('cadastrar') != -1)
			{
				_this.botoesDeCadastro();
				_this.renderizarModoCadastro();
			}
		}

		_this.botoesDeCadastro = function botoesDeCadastro()
		{
			_this.botaoCadastrar.removeClass('hide');
			_this.botaoCancelar.removeClass('hide');
		};

		_this.botoesDeEdicao = function botoesDeEdicao()
		{
			_this.botaoCancelar.removeClass('hide');
			_this.botaoAlterar.removeClass('hide');
		};

		_this.botoesDeVisualizacao = function botoesDeVisualizacao()
		{
			_this.botaoCancelar.removeClass('hide');
			_this.botaoAlterar.removeClass('hide');
			_this.botaoRemover.removeClass('hide');
		};

		//Função para renderizar  o modo de visualização
		_this.renderizarModoVisualizacao =  function renderizarModoVisualizacao()
		{
			$('.panel-heading').html('Visualizar Medicamento Pessoal');
			$("#medicamento").on("keyup", _this.definirAutoCompleteMedicamento);
			$("#forma_medicamento").on("change", _this.popularUnidadesDeMedida)
			desabilitarFormulario();
			var id = pegarId(window.location.href, 'visualizar')

			var sucesso = function sucesso(data, textStatus, jqXHR)
			{
				_this.desenhar(data);
			}
			servicoMedicamentoPessoal.comId(id).done(sucesso);

			_this.botaoAlterar.on('click', _this.redirecionarParaEdicao);
			_this.botaoRemover.on('click', _this.remover);
			_this.botaoCancelar.on('click', _this.redirecionarParaListagem);
			definirMascarasPadroes();
		};

		//Função para renderizar o modo de edição
		_this.renderizarModoEdicao =  function renderizarModoEdicao()
		{
			$('.panel-heading').html('Editar Medicamento Pessoal');
			$("#medicamento").on("keyup", _this.definirAutoCompleteMedicamento);
			$("#forma_medicamento").on("change", _this.popularUnidadesDeMedida)
			desabilitarFormulario(false);
			var id = pegarId(window.location.href, 'editar');
			var sucesso = function sucesso(data, textStatus, jqXHR)
			{
				_this.desenhar(data);
			}

			servicoMedicamentoPessoal.comId(id).done(sucesso);

			_this.botaoAlterar.on('click', _this.salvar);
			_this.botaoCancelar.on('click', _this.redirecionarParaListagem);
			definirMascarasPadroes();
		};

		//Função para renderizar o modo de cadastro
		_this.renderizarModoCadastro = function renderizarModoCadastro()
		{
			$('.panel-heading').html('Cadastrar Posologia');
			desabilitarFormulario(false);
			$('#nome').focus();
			_this.botaoCadastrar.on('click', _this.salvar);
			_this.botaoCancelar.on('click', _this.redirecionarParaListagem);
			definirMascarasPadroes();
			popularSelectTiposDePeriodicidade();
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

		// Desenha o objeto no formulário
		_this.desenhar = function desenhar(obj)
		{
			_obj = obj;
			_this.iniciarFormularioPosologia();

			popularSelectTiposDePeriodicidade(key_array(_tempoUnidadesPlural ,obj.tipoPeriodicidade));
			$("#id").val(obj.id);
			$("#medicamento_Pessoal_id").val(obj.medicamentoPessoal.id);
			$("#unidade").html(obj.medicamentoPessoal.tipoUnidade);
			$("#dose").val(obj.dose);
			$("#descricao").val(obj.descricao);
			$("#periodicidade").val(obj.periodicidade);
		};

		_this.salvar = function salvar(event)
		{
			// Ao validar e tudo estiver correto, é disparado o método submitHandler(),
			// que é definido nas opções de validação.
			_this.formulario.validate(criarOpcoesValidacao());
		};

		_this.remover = function remover(event)
		{
			event.preventDefault();

			var sucesso = function sucesso(data, textStatus, jqXHR)
			{
				// Mostra mensagem de sucesso
				toastr.success('Removido');
				_this.redirecionarParaListagem();

			};

			var erro = function erro(jqXHR, textStatus, errorThrown)
			{
				var mensagem = jqXHR.responseText || 'Ocorreu um erro ao tentar remover.';
				toastr.error( mensagem );
			};

			var solicitarRemocao = function solicitarRemocao()
			{
				servicoPosologia.remover( _obj.id ).done( sucesso ).fail( erro );
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
			_this.definirForm();
			_this.formulario = $('#posologia_form');
			_this.formulario.submit(false);
		};
	}; // ControladoraFormPosologia

	// Registrando
	app.ControladoraFormPosologia = ControladoraFormPosologia;

})(window, app, jQuery, toastr);


