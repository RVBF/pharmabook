/**
 *  medicamentoPessoal.form.ctrl.js
 *
 *  @author  Rafael Vinicius Barros Ferreira
 */
(function(window, app, $, toastr)
{
	'use strict';
	function ControladoraFormMedicamentoPessoal(servicoLaboratorio,servicoMedicamento,servicoMedicamentoPessoal)
	{
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
		_this.criarOpcoesValidacao = function criarOpcoesValidacao()
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
					desabilitarFormulario(!b);
					desabilitarBotoesDeFormulario(!b);
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
			$('.panel-heading').html('Cadastrar Medicamento Pessoal');
			$("#medicamento").on("keyup", _this.definirAutoCompleteMedicamento);
			$("#forma_medicamento").on("change", _this.popularUnidadesDeMedida);
			desabilitarFormulario(false);
			$('#laboratorio').prop('disabled', true);
			_this.getLaboratoriosDoMedicamentoParaSelect();
			_this.getAdministracaoesMedicamentos();
			_this.getMedicamentosFormas();
			_this.popularUnidadesDeMedida();
			_this.botaoCadastrar.on('click', _this.salvar);
			_this.botaoCancelar.on('click', _this.redirecionarParaListagem);
			definirMascarasPadroes();
		};
		//Função para renderizar o modo do formulário

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

		_this.desenhar = function desenhar(obj)
		{
			_obj = obj;
			$("#id").val(obj.id);
			$("#medicamento_nome").val(obj.medicamento.nomeComercial);
			$("#composicao").val(obj.medicamento.composicao);

			if(obj.medicamento.nomeComercial != undefined && obj.medicamento.composicao != undefined)
			{
				$("#medicamento").val(obj.medicamento.nomeComercial + ' ' + obj.medicamento.composicao);
			}
			else
			{
				 $("#medicamento").val('');
			}

			$("#validade").val(obj.validade);
			$("#valor_recipiente").val(obj.capacidadeRecipiente);
			$("#quantidade_estoque").val(obj.quantidade);

			_this.getLaboratoriosDoMedicamentoParaSelect(obj.medicamento.laboratorio.id);
			_this.getAdministracaoesMedicamentos(key_array(administracoes, obj.administracao));
			_this.getMedicamentosFormas(key_array(medicamentosFormas, obj.medicamentoForma));
			_this.popularUnidadesDeMedida(key_array(unidadesTipos, obj.tipoUnidade));
		};

		_this.popularSelectLaboratorio =  function popularSelectLaboratorio(resposta)
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

		_this.getLaboratoriosDoMedicamentoParaSelect  =  function getLaboratoriosDoMedicamentoParaSelect(valor = 0)
		{
			var sucesso = function (resposta)
			{
				_this.popularSelectLaboratorio(resposta);

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

		_this.getAdministracaoesMedicamentos  =  function getAdministracaoesMedicamentos(valor = 0)
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


				if(valor != 0  || valor > 0)
				{
					elemento.val(valor);
				}

				elemento.trigger('change');
			};

			var  jqXHR = servicoMedicamentoPessoal.getAdministracaoesMedicamentos();
			jqXHR.done(sucesso);
		};

		_this.popularUnidadesDeMedida = function popularUnidadesDeMedida(unidadeMedida = undefined)
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

		_this.getMedicamentosFormas  =  function getMedicamentosFormas(valor = undefined)
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
						_this.popularSelectLaboratorio(data);
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

					var erro = function erro(jqXHR, textStatus, errorThrown)
					{
						var mensagem = jqXHR.responseText || 'Erro ao pesquisar medicamento.';
						toastr.error(mensagem);
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

		//Chama a funcão de validação de dados e depois submete o formulário
		_this.salvar = function salvar(event)
		{
			// Ao validar e tudo estiver correto, é disparado o método submitHandler(),
			// que é definido nas opções de validação.
			_this.formulario.validate(_this.criarOpcoesValidacao());
		};

		//Remove o medicamento do sistema
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
				toastr.error(mensagem);
			};

			var solicitarRemocao = function solicitarRemocao()
			{
				servicoMedicamentoPessoal.remover(_obj.id).done(sucesso).fail(erro);
			};

			BootstrapDialog.show({
				type	: BootstrapDialog.TYPE_DANGER,
				title	: 'Remover?',
				message	: _obj.medicamento.nomeComercial,
				size	: BootstrapDialog.SIZE_LARGE,
				buttons	: [
					{
						label	: '<u>S</u>im',
						hotkey	: 'S'.charCodeAt(0),
						action	: function(dialog)
						{
							dialog.close();
							solicitarRemocao();
						}
					},
					{
						label	: '<u>N</u>ão',
						hotkey	: 'N'.charCodeAt(0),
						action	: function(dialog)
						{
							dialog.close();
						}
					}
				]
			});
		}; // remover

		_this.limparIdMedicamento = function limparIdMedicamento()
		{
			var medicamentoId = $("#medicamento_id");

			if(medicamentoId.val() != null && event.keyCode == 8)
			{
				medicamentoId.val('');
			}
		};

		//Configura os eventos do formulário
		_this.configurar = function configurar()
		{
			_this.definirForm();
			_this.formulario = $('#medicamento_pessoal_form');
			_this.formulario.submit(false);
		};
	}; // cancelar

	// Registrando
	app.ControladoraFormMedicamentoPessoal = ControladoraFormMedicamentoPessoal;
})(window, app, jQuery, toastr);


