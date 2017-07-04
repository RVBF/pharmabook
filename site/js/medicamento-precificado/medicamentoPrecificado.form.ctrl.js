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
		servicoFarmacia
	)
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
			var regras = {
				rules:
				{
					"medicamento": {
						required    : true,
					},

					"laboratorio": {
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
					"medicamento": {
						required    : "O meicamento selecionado não corresponde a nenhum cadastrado na base de dados."
					},

					"laboratorio": {
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
				var jqXHR = _this.alterar ? servicoMedicamentoPrecificado.atualizar(obj) : servicoMedicamentoPrecificado.adicionar(obj);
				jqXHR.done(sucesso).fail(erro).always(terminado);
			}; // submitHandler

			return regras;
		};

		// Encaminha o usuário para a listagem
		_this.redirecionarParaListagem = function redirecionarParaListagem()
		{
			router.navigate('/medicamentos-precificados/');
		};

		// Encaminha o usuário para a edição
		_this.redirecionarParaEdicao = function redirecionarParaEdicao()
		{
			router.navigate('/medicamentos-precificados/editar/'+ _obj.id +'/');
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
			$('.panel-heading').html('Visualizar Medicamento Precificado');
			$("#medicamento").on("keyup", _this.definirAutoCompleteMedicamento);

			desabilitarFormulario();
			var id = pegarId(window.location.href, 'visualizar')
			var sucesso = function sucesso(data, textStatus, jqXHR)
			{
				_this.desenhar(data);
			}

			servicoMedicamentoPrecificado.comId(id).done(sucesso);

			_this.botaoAlterar.on('click', _this.redirecionarParaEdicao);
			_this.botaoRemover.on('click', _this.remover);
			_this.botaoCancelar.on('click', _this.redirecionarParaListagem);
			definirMascarasPadroes();
		};

		//Função para renderizar o modo de edição
		_this.renderizarModoEdicao =  function renderizarModoEdicao()
		{
			$('.panel-heading').html('Editar Medicamento Precificado');
			desabilitarFormulario(false);
			$('#medicamento').prop('disabled', true);
			$('#laboratorio').prop('disabled', true);
			$('#farmacia').prop('disabled', true);
			var id = pegarId(window.location.href, 'editar');
			var sucesso = function sucesso(data, textStatus, jqXHR)
			{
				_this.desenhar(data);
			}

			servicoMedicamentoPrecificado.comId(id).done(sucesso);
			_this.botaoAlterar.on('click', _this.salvar);
			_this.botaoCancelar.on('click', _this.redirecionarParaListagem);
			definirMascarasPadroes();
		};

		//Função para renderizar o modo de cadastro
		_this.renderizarModoCadastro = function renderizarModoCadastro()
		{
			$('.panel-heading').html('Colaborar');
			$(document).on('click', '#close-preview', function(){
		    $('.image-preview').popover('hide');
		    // Hover befor close the preview
		    $('.image-preview').hover(
		        function () {
		           $('.image-preview').popover('show');
		        },
		         function () {
		           $('.image-preview').popover('hide');
		        }
		    );
			});

			$(function() {
			    // Create the close button
			    var closebtn = $('<button/>', {
			        type:"button",
			        text: 'x',
			        id: 'close-preview',
			        style: 'font-size: initial;',
			    });
			    closebtn.attr("class","close pull-right");
			    // Set the popover default content
			    $('.image-preview').popover({
			        trigger:'manual',
			        html:true,
			        title: "<strong>Preview</strong>"+$(closebtn)[0].outerHTML,
			        content: "There's no image",
			        placement:'bottom'
			    });
			    // Clear event
			    $('.image-preview-clear').click(function(){
			        $('.image-preview').attr("data-content","").popover('hide');
			        $('.image-preview-filename').val("");
			        $('.image-preview-clear').hide();
			        $(".image-preview-input").show();
			        $('.image-preview-input input:file').val("");
			    });
			    // Create the preview image
			    $(".image-preview-input input:file").change(function (){
			        var img = $('<img/>', {
			            id: 'dynamic',
			            width:250,
			            height:200
			        });
			        var file = this.files[0];
			        var reader = new FileReader();
			        // Set preview image into the popover data-content
			        reader.onload = function (e) {
			            $(".image-preview-clear").show();
			            $(".image-preview-input").hide();
			            $(".image-preview-filename").val(file.name);
			            img.attr('src', e.target.result);
						$(".image-preview").attr("data-content",$(img)[0].outerHTML).popover("show");
			        }
			        reader.readAsDataURL(file);
			    });
			});

			$('.adicionar_farmacia').on('click', function()
			{
			// create the backdrop and wait for next modal to be triggered
				$('body').modalmanager('loading');

				setTimeout(function(){
						modal.load('formularioFarmacia.html', '', function(){
							modal.modal();
							modal.show();
						});
				}, 1000);
			});

			var modal = $('body').find('.modal');
			$('.adicionar_farmacia').on('click', function()
			{
			// create the backdrop and wait for next modal to be triggered
				$('body').modalmanager('loading');

				setTimeout(function(){
						modal.load('formularioFarmacia.html', '', function(){
							modal.modal();
							modal.show();
						});
				}, 1000);
			});

			modal.on('click', '.update', function(){
				modal.modal('loading');
				setTimeout(function(){
					modal.modal('loading').find('.modal-body').prepend('<div class="alert alert-info fade in">' +
					'Updated!<button type="button" class="close" data-dismiss="alert">&times;</button>' +
					'</div>');
				}, 1000);
			});

			$("#medicamento").on("keyup", _this.definirAutoCompleteMedicamento);
			desabilitarFormulario(false);
			$('#laboratorio').prop('disabled', true);
			_this.popularSelectFarmacia();
			_this.botaoCadastrar.on('click', _this.salvar);
			_this.botaoCancelar.on('click', _this.redirecionarParaListagem);
			definirMascarasPadroes();
		};
		//Função para renderizar o modo do formulário

		//Função para popular os dados do select de farmácias
		_this.popularSelectFarmacia  =  function popularSelectFarmacia(valor = 0)
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
				toastr.error(mensagem);
				return false;
			}

			var  jqXHR = servicoFarmacia.todos();
			jqXHR.done(sucesso).fail(erro);
		}

		// Obtém o conteúdo atual do form como um objeto
		_this.conteudo = function conteudo()
		{
			var sessao = new app.ServicoSessao();

			var usuarioSessao = JSON.parse(sessao.getSessao());

			return servicoMedicamentoPrecificado.criar(
				$('#id').val(),
				converterEmFloat($('#preco').val()),
				servicoFarmacia.criar(
					$('#farmacia').val()
				),
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

		_this.popularSelectLaboratorio =  function popularSelectLaboratorio(resposta)
		{
			var elemento = $("#laboratorio");

			elemento.empty();

			$.each(resposta, function(i ,item)
			{
				var opcao = new Option(item.nome, item.id ,true, false)
				elemento.append(opcao);
			});

			elemento.select2().trigger('change');
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

		// Desenha o objeto no formulário
		_this.desenhar = function desenhar(obj)
		{
			_obj = obj;

			_this.popularSelectFarmacia(obj.farmacia.id);

			$("#id").val(obj.id || 0);
			$("#medicamento_id").val(obj.medicamento.id);
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

			$("#preco").val((obj.preco > 0) ? converterEmMoeda(obj.preco) : '');
			_this.getLaboratoriosDoMedicamentoParaSelect(obj.medicamento.laboratorio.id);
		};
		//Funções para eventos dos botões

		//Chama a funcão de validação de dados e depois submete o formulário
		_this.salvar = function salvar(event)
		{
			// Ao validar e tudo estiver correto, é disparado o método submitHandler(),
			// que é definido nas opções de validação.

			$("#medicamento_precificado_form").validate(criarOpcoesValidacao());
		};

		//Remove o medicamento do sistema
		_this.remover = function remover(event) {
			event.preventDefault();

			var sucesso = function sucesso(data, textStatus, jqXHR)
			{
				// Mostra mensagem de sucesso
				toastr.success('Removido');
				redirecionarParaListagem();

			};

			var erro = function erro(jqXHR, textStatus, errorThrown)
			{
				var mensagem = jqXHR.responseText || 'Ocorreu um erro ao tentar remover.';
				toastr.error(mensagem);
				$('#msg').empty().append('<div class="error" >' + mensagem + '</div>');
			};

			var solicitarRemocao = function solicitarRemocao()
			{
				servicoMedicamentoPrecificado.remover(_obj.id).done(sucesso).fail(erro);
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
						action	: function(dialog){
							dialog.close();
							solicitarRemocao();
						}
					},
					{
						label	: '<u>N</u>ão',
						hotkey	: 'N'.charCodeAt(0),
						action	: function(dialog){
							dialog.close();
						}
					}
				]
			});
		}; // remover
		//fim Funções para eventos dos botões

		//Configura os eventos do formulário
		_this.configurar = function configurar()
		{
			_this.definirForm();
			_this.formulario = $('#medicamento_precificado_form');
			_this.formulario.submit(false);
		};
	}; // ControladoraFormMedicamentoPrecificado

	// Registrando
	app.ControladoraFormMedicamentoPrecificado = ControladoraFormMedicamentoPrecificado;
})(window, app, jQuery, toastr);


