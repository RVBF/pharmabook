/**
 *  farmacia.form.ctrl.js
 *
 *  @author  Rafael Vinicius Barros Ferreira
 *	 @version 1.0
 */
(function(window, app, $, toastr)
{
	'use strict';

	function ControladoraFormFarmacia(servicoFarmacia, servicoEndereco)
	{ // Model

		var _this = this;
		var _obj = null;

		_this.formulario = null;
		_this.router = window.router;
		_this.alterar = false;
		_this.modal = $('#farmacia_form').parents('.modal');
		_this.botaoCadastrar = _this.modal.find('#cadastrar');
		_this.botaoAlterar = _this.modal.find('#alterar');
		_this.botaoRemover = _this.modal.find('#remover');
		_this.botaoCancelar =  _this.modal.find('#cancelar');
		_this.botaoPesquisarCep = $('#farmacia_form').find('.pesquisar_cep');
		_this.modo = $('#farmacia_form').find('#modo');

		var fecharModal = function fecharModal()
		{
			_this.modal.modal('hide');
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
					},

					"estado": {
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
					},

					"estado": {
						required    : "O campo estado é obrigadorio."
					}
				}
			};

			// Irá disparar quando a validação passar, após chamar o método validate().
			opcoes.submitHandler = function submitHandler(form)
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
					_this.formulario.find('#msg').empty().append('<div class="error" >' + mensagem + '</div>');
				};

				var terminado = function terminado()
				{
					controlesHabilitados(true);
				};

				var sucesso = function sucesso(data, textStatus, jqXHR)
				{
					toastr.success('Salvo');

					var nomeFarmacia = _this.formulario.find('#nome');
					_this.modal.find('form')[0].reset();
					fecharModal();

					var controladraMedicamentoPrecificado = new  app.ControladoraFormMedicamentoPrecificado(
						undefined,
						undefined,
						undefined,
						undefined,
						servicoFarmacia
					);

					controladraMedicamentoPrecificado.popularSelectFarmacia();
				};

				var obj = _this.conteudo();
				var jqXHR = _this.alterar ? servicoFarmacia.atualizar(obj) : servicoFarmacia.adicionar(obj);
				jqXHR.done(sucesso).fail(erro).always(terminado);
			}; // submitHandler

			return opcoes;
		};

		// Encaminha o usuário para a edição
		_this.redirecionarParaEdicao = function redirecionarParaEdicao()
		{
			router.navigate('/farmacias/editar/'+ _obj.id +'/');
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
				_this.renderizarModoCadastroFarmacia();
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
			$('.panel-heading').html('Visualizar Farmácia');
			desabilitarFormulario();
			var id = pegarId(window.location.href, 'visualizar')

			var sucesso = function sucesso(data, textStatus, jqXHR)
			{
				_this.desenhar(data);
			}
			servicoFarmacia.comId(id).done(sucesso);

			_this.botaoAlterar.on('click', _this.redirecionarParaEdicao);
			_this.botaoRemover.on('click', _this.remover);
			_this.botaoCancelar.on('click', _this.redirecionarParaListagem);
			_this.definirMascaras();
			definirMascarasPadroes();
		};

		//Função para renderizar o modo de edição
		_this.renderizarModoEdicao =  function renderizarModoEdicao()
		{
			$('.panel-heading').html('Editar Farmácia');
			desabilitarFormulario(false);
			var id = pegarId(window.location.href, 'editar');
			var sucesso = function sucesso(data, textStatus, jqXHR)
			{
				_this.desenhar(data);
			}

			servicoFarmacia.comId(id).done(sucesso);

			_this.botaoAlterar.on('click', _this.salvar);
			_this.botaoCancelar.on('click', _this.redirecionarParaListagem);
			_this.definirMascaras();
			definirMascarasPadroes();
		};

		//Função para renderizar o modo de cadastro
		_this.renderizarModoCadastroFarmacia = function renderizarModoCadastroFarmacia()
		{
			_this.pesquisarComLocalizacao();
			$('.modal').find('.modal-header').find('h3').html('Cadastrar Farmácia');
			desabilitarFormulario(false);
			_this.botaoCadastrar.on('click', _this.salvar);
			_this.botaoCancelar.on('click', fecharModal);
			_this.botaoPesquisarCep.on('click', _this.pesquisarCep);
			_this.definirMascaras();
			definirMascarasPadroes();
			_this.popularSelectEstado();
		};

		_this.definirMascaras = function definirMascaras()
		{
			$("#telefone").mask("(999)9999-9999");
			$('#cep').mask('99999-999');
		};

		// Obtém o conteúdo atual do form como um objeto
		_this.conteudo = function conteudo()
		{
			return servicoFarmacia.criar(
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
					$('latitude').val(),
					$('longitude').val()
				)
			);
		};

		_this.iniciarFormularioFarmacia = function iniciarFormularioFarmacia()
		{
			var opcoes = {
				show : true,
				keyboard : false,
				backdrop : true
			};

			var modal = $('#areaForm').find('#farmacia_modal').modal(opcoes);

			$('#nome').focus();
		};

		// Desenha o objeto no formulário
		_this.desenhar = function desenhar(obj)
		{
			_obj = obj;
			$('#id').val(obj.id);
			$('#nome').val(obj.nome);
			$('#telefone').val(obj.telefone);
			$('#endereco_id').val(obj.endereco.id);
			$('#cep').val(obj.endereco.cep);
			$('#logradouro').val(obj.endereco.logradouro);
			$('#numero').val(obj.endereco.numero);
			$('#complemento').val(obj.endereco.complemento);
			$('#referencia').val(obj.endereco.referencia);
			$('#bairro').val(obj.endereco.bairro);
			$('#cidade').val(obj.endereco.cidade);
			$('#estado').val(obj.endereco.estado);
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

			var sucesso = function sucesso( data, textStatus, jqXHR )
			{
				// Mostra mensagem de sucesso
				toastr.success( 'Removido' );
				_this.redirecionarParaListagem();
			};

			var erro = function erro( jqXHR, textStatus, errorThrown )
			{
				var mensagem = jqXHR.responseText || 'Ocorreu um erro ao tentar remover.';
				toastr.error( mensagem );
				$('#msg').empty().append('<div class="error" >' + mensagem + '</div>');
			};

			var solicitarRemocao = function solicitarRemocao()
			{
				servicoFarmacia.remover( _obj.id ).done( sucesso ).fail( erro );
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

		_this.pesquisarCep = function pesquisarCep ()
		{
			var sucesso =  function sucesso (data, textStatus, jqXHR)
			{
				$("#logradouro").val(data.logradouro);
				$("#latitude").val(data.logradouro.latitude);
				$("#latitude").val(data.logradouro.longitude);
				$("#bairro").val(data.logradouro.bairro.nome);
				$("#cidade").val(data.logradouro.bairro.cidade.nome);
				$("#estado").val(data.logradouro.bairro.cidade.estado.sigla);
			};

			var erro = function erro(jqXHR, textStatus, errorThrown)
			{
				var mensagem = jqXHR.statusText;
				$('#msg').empty().append('<div class="error" >' + mensagem + '</div>');
				toastr.error( mensagem );
			};

			var cep = retornarInteiroEmStrings($("#cep").val());

			var jqXHR = servicoEndereco.comCep(cep);

			jqXHR
				.done(sucesso)
				.fail(erro);
		}

		_this.pesquisarComLocalizacao = function pesquisarComLocalizacao()
		{

			var sucesso =  function sucesso (data, textStatus, jqXHR)
			{
				_this.formulario.find("#endereco_id").val(data.id);
				_this.formulario.find("#cep").val(data.cep);
				_this.formulario.find("#logradouro").val(data.logradouro);
				_this.formulario.find("#latitude").val(data.latitude);
				_this.formulario.find("#longitude").val(data.longitude);
				_this.formulario.find("#bairro").val(data.bairro.nome);

				var cidadeSelecionada = data.bairro.cidade.nome;
				_this.formulario.find('#estado').find('option').each(function(i,value)
				{
					if($(this).attr('sigla') == data.bairro.cidade.estado.sigla)
					{
						$(this).attr({selected : 'selected'}).trigger('change');
					}
				});

				var sucesso = function (data, textStatus, jqXHR)
				{
					var elementoCidade = _this.formulario.find("#cidade");
					elementoCidade.empty();
					var opcao = new Option('Selecione', '' ,true, false)
					elementoCidade.append(opcao);

					$.each(data, function(i ,item) {
						var opcao = new Option(item.nome, item.id ,false, false);

						if(cidadeSelecionada == item.nome)
						{
							$(opcao).attr({selected : 'selected'}).trigger('change');
						}
						elementoCidade.append(opcao);
					});

					elementoCidade.trigger('change');
				}

				var erro = function erro(jqXHR, textStatus, errorThrown)
				{
					var mensagem = jqXHR.statusText;
					$('#msg').empty().append('<div class="error" >' + mensagem + '</div>');
					toastr.error( mensagem );
				};

				var jqXHR = servicoEndereco.comUf(data.bairro.cidade.estado.sigla);
				jqXHR.done(sucesso).fail(erro);
			};

			var erro = function erro(jqXHR, textStatus, errorThrown)
			{
				var mensagem = jqXHR.statusText;
				$('#msg').empty().append('<div class="error" >' + mensagem + '</div>');
				toastr.error( mensagem );
			};

			navigator.geolocation.getCurrentPosition(function(position)
			{
				var jqXHR = servicoEndereco.comGeolocalizacao(position.coords.latitude, position.coords.longitude);

				jqXHR.done(sucesso).fail(erro);
			});
		};

		_this.popularSelectEstado  =  function popularSelectEstado(valor = 0)
		{
			var sucesso = function (data, textStatus, jqXHR)
			{
				var elemento  = _this.formulario.find('#estado');
				elemento.empty();

				var opcao = new Option('Selecione', '' ,true, false)
				elemento.append(opcao);

				$.each(data ,function(i ,item)
				{
					var opcao = new Option(item.nome + '/' + item.sigla, item.id ,false, false);
					elemento.append(opcao);
					$(opcao).attr('sigla', item.sigla);
				});

				if(valor != 0  || valor > 0)
				{
					elemento.val(valor || 0);
				}

				elemento.trigger('change');
			};

			var erro = function(resposta)
			{
				var mensagem = jqXHR.responseText || 'Erro ao popular select de farmácias.';
				toastr.error(mensagem);
				return false;
			}

			var  jqXHR = servicoEndereco.todosEstados();
			jqXHR.done(sucesso).fail(erro);
		}

		_this.popularSelectCidade  =  function popularSelectCidade(valor = 0)
		{
			var sucesso = function (resposta)
			{
				$("#cidade").empty();
				$("#cidade").append($('<option>', {
					value: '',
					text: 'Selecione'
				}));

				$.each(resposta.data, function(i ,item) {
					$("#cidade").append($('<option>', {
						value: item.id,
						text: item.nome + '/' + item.sigla
					}));
				});

				if(valor != 0  || valor > 0)
				{
					$("#cidade").val(valor || 0);
				}
			};

			var erro = function(resposta)
			{
				var mensagem = jqXHR.responseText || 'Erro ao popular select de farmácias.';
				toastr.error(mensagem);
				return false;
			}

			var  jqXHR = servicoEnderecoo.comUf($('#estado').text().split('/')[1]);

			jqXHR.done(sucesso).fail(erro);
		}

		// Configura os eventos do formulário
		_this.configurar = function configurar()
		{
			_this.definirForm();
			_this.formulario = $('#farmacia_form');
			_this.formulario.submit(false);
		};
	}; // ControladoraFormFarmacia

	// Registrando
	app.ControladoraFormFarmacia = ControladoraFormFarmacia;

})(window, app, jQuery, toastr);


