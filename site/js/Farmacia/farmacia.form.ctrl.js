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
		_this.botaoCadastrar = $('#cadastrar');
		_this.botaoAlterar = $('#alterar');
		_this.botaoRemover = $('#remover');
		_this.botaoCancelar = $('#cancelar');
		_this.botaoPesquisarCep = $('.pesquisar_cep');
		_this.modo = $('#modo');

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
					},

					"pais": {
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
				var jqXHR = _this.alterar ? servicoFarmacia.atualizar(obj) : servicoFarmacia.adicionar(obj);
				jqXHR.done(sucesso).fail(erro).always(terminado);
			}; // submitHandler

			return opcoes;
		};
		// criarOpcoesValidacao
		_this.redirecionarParaListagem = function redirecionarParaListagem()
		{
			router.navigate('/farmacias/');
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
		_this.renderizarModoCadastro = function renderizarModoCadastro()
		{
			$('.modal').find('.modal-header').find('h3').html('Cadastrar Farmácia');
			desabilitarFormulario(false);
			_this.botaoCadastrar.on('click', _this.salvar);
			_this.botaoCancelar.on('click', _this.redirecionarParaListagem);
			_this.botaoPesquisarCep.on('click', _this.pesquisarCep);
			_this.definirMascaras();
			definirMascarasPadroes();
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
					$('#pais').val()
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
			$('#pais').val(obj.endereco.pais);
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
				$("#bairro").val(data.bairro);
				$("#cidade").val(data.cidade);
				$("#estado").val(data.estado_info.nome);
			};

			var erro = function erro(jqXHR, textStatus, errorThrown)
			{
				var mensagem = jqXHR.statusText;
				$('#msg').empty().append('<div class="error" >' + mensagem + '</div>');
				toastr.error( mensagem );
			};

			var cep = retornarInteiroEmStrings($("#cep").val());

			var jqXHR = servicoEndereco.consultarCepOnline(cep);

			jqXHR
				.done(sucesso)
				.fail(erro);
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


