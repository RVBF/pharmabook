/**
 *  usuario.form.ctrl.js
 *
 *  @author  Rafael Vinicius Barros Ferreira
 */
(function(window, app, $, toastr)
{
	'use strict';

	function ControladoraFormUsuario(servicoUsuario, servicoLogout, servicoEndereco)
	{ // Model

		var _this = this;
		var _obj = null;
		_this.formulario = null;
		_this.router = window.router;
		_this.alterar = false;
		_this.botaoCadastrar = $('#cadastrar');
		_this.botaoAlterar = $('#alterar');
		_this.botaoAlterarSenha = $('#alterar_senha');
		_this.botaoRemover = $('#remover');
		_this.botaoCancelar = $('#cancelar');
		_this.modo = $('#modo');
		_this.botaoPesquisarCep = $('#usuario_form').find('.pesquisar_cep');

		_this.redirecionarParaPaginaInicial = function redirecionarParaPaginaInicial()
		{
		   window.location.href = 'rafael.pharmabook';
		};

		_this.irProLogin = function irProLogin()
		{
			window.location.href = 'login.html';
		};

		_this.redirecionarParaLogin = function redirecionarParaLogin()
		{
			window.location.href = 'login.html';
		};

		// Encaminha o usuário para a edição
		_this.redirecionarParaEdicao = function redirecionarParaEdicao()
		{
			router.navigate('/usuario/editar/'+ _obj.id +'/');
		};

		_this.redirecinarParaTrocaDesenha = function redirecinarParaTrocaDesenha()
		{
			router.navigate('/usuario/alterar-senha/'+ _obj.id +'/');
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
		_this.criarOpcoesValidacaoDeEdicao = function criarOpcoesValidacaoDeEdicao()
		{
			var regras = {
				rules: {
					"nome": {
						required	: true,
						rangelength : [ 2, 100 ]
					},

					"sobrenome": {
						required	: true,
						rangelength : [ 2, 100 ]
					},

					"email": {
						required	: true,
						email	: true
					},

					"login": {
						required	: true,
						rangelength : [ 5, 30 ]
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

				messages: {
					"nome": {
						required	: "O campo nome é obrigatório.",
						rangelength	: $.validator.format("A login/email deve ter entre {0} e {1} caracteres.")
					},

					"sobrenome": {
						required	: "O campo sobrenome é obrigatório.",
						rangelength	: $.validator.format("A login/email deve ter entre {0} e {1} caracteres.")
					},

					"email": {
						required	: "O campo e-mail é obrigatório.",
						email : "Insira um e-mail valido.",
					},

					"login": {
						required	: "O campo login é obrigatório.",
						rangelength	: $.validator.format("O login deve ter entre {0} e {1} caracteres.")
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
			regras.submitHandler = function submitHandler(form) {
				// Habilita/desabilita os controles
				var controlesHabilitados = function controlesHabilitados(b)
				{
					desabilitarFormulario(!b);
					desabilitarBotoesDeFormulario(!b)
				};

				controlesHabilitados(false);

				var sucesso = function sucesso(data, textStatus, jqXHR)
				{
					toastr.success('Senha Alterada Com sucesso.');

					_this.redirecionarParaPaginaInicial();
				};

				var erro = function erro(jqXHR, textStatus, errorThrown)
				{
					var mensagem = jqXHR.responseText;
					$('#msg').empty().append('<div class="error" >' + mensagem + '</div>');
				};

				var terminado = function()
				{
					controlesHabilitados(true);
				};

				var obj = _this.conteudo();
				var jqXHR = servicoUsuario.atualizar(obj);
				jqXHR.done(sucesso).fail(erro).always(terminado);
			}; // submitHandler

			return regras;
		};

		// Cria as opções de validação do formulário
		_this.criarOpcoesValidacaoDeCadastro = function criarOpcoesValidacaoDeCadastro()
		{
			var regras = {
				rules: {
					"nome": {
						required	: true,
						rangelength : [ 2, 100 ]
					},

					"sobrenome": {
						required	: true,
						rangelength : [ 2, 100 ]
					},

					"email": {
						required	: true,
						email	: true
					},

					"login": {
						required	: true,
						rangelength : [ 5, 30 ]
					},

					"senha": {
						required	: true,
						rangelength : [ 8, 50 ]
					},

					"confirmacao_senha": {
						equalTo : "#senha"
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
				},

				messages: {
					"nome": {
						required	: "O campo nome é obrigatório.",
						rangelength	: $.validator.format("A login/email deve ter entre {0} e {1} caracteres.")
					},

					"sobrenome": {
						required	: "O campo sobrenome é obrigatório.",
						rangelength	: $.validator.format("A login/email deve ter entre {0} e {1} caracteres.")
					},

					"email": {
						required	: "O campo e-mail é obrigatório.",
						email : "Insira um e-mail valido.",
					},

					"login": {
						required	: "O campo login é obrigatório.",
						rangelength	: $.validator.format("O login deve ter entre {0} e {1} caracteres.")
					},

					"senha": {
						required	: "O campo senha é obrigatório.",
						rangelength	: $.validator.format("A senha deve ter entre {0} e {1} caracteres.")
					},

					"confirmacao_senha": {
						equalTo	: "O campo senha e confirmação de senha devem ser iguais."
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
			regras.submitHandler = function submitHandler(form)
			{
				// Habilita/desabilita os controles
				var controlesHabilitados = function controlesHabilitados(b)
				{
					desabilitarFormulario(!b);
					desabilitarBotoesDeFormulario(!b)
				};

				controlesHabilitados(false);

				var sucesso = function sucesso(data, textStatus, jqXHR)
				{
					if(data.estaLogado)
					{
						_this.redirecionarParaPaginaInicial();
						toastr.success('Usuário logado com sucesso.');
					}
					else
					{
						toastr.erro('Erro ao efetuar Login.');

						_this.irProLogin();
					}

					toastr.success('Usuário cadastrado com sucesso.');
				};

				var erro = function erro(jqXHR, textStatus, errorThrown)
				{
					var mensagem = jqXHR.responseText;
					$('#msg').empty().append('<div class="error" >' + mensagem + '</div>');
				};

				var terminado = function()
				{
					controlesHabilitados(true);
				};

				var obj = _this.conteudoCadastro();
				var jqXHR = servicoUsuario.adicionar(obj);
				jqXHR.done(sucesso).fail(erro).always(terminado);
			}; // submitHandler

			return regras;
		};

		_this.criarOpcoesValidacaoDeAlteracaoDeSenha = function criarOpcoesValidacaoDeAlteracaoDeSenha()
		{
			var regras =
			{
				rules: {
					"senha_atual":
					{
						required	: true,
						rangelength : [ 8, 50 ]
					},

					"senha":
					{
						required	: true,
						rangelength : [ 8, 50 ]
					},

					"confirmacao_senha":
					{
						equalTo : "#senha"
					}
				},

				messages:
				{
					"senha_atual":
					{
						required	: "O campo senha atual é obrigatório.",
						rangelength	: $.validator.format("A senha deve ter entre {0} e {1} caracteres.")
					},

					"senha":
					{
						required	: "O campo senha é obrigatório.",
						rangelength	: $.validator.format("A senha deve ter entre {0} e {1} caracteres.")
					},

					"confirmacao_senha": {
						equalTo	: "O campo senha e confirmação de senha devem ser iguais."
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
					desabilitarBotoesDeFormulario(!b)
				};

				controlesHabilitados(false);

				var sucesso = function sucesso(data, textStatus, jqXHR)
				{
					toastr.success('Senha Alterada Com sucesso.');
					_this.redirecionarParaPaginaInicial();
				};

				var erro = function erro(jqXHR, textStatus, errorThrown)
				{
					var mensagem = jqXHR.responseText;
					$('#msg').empty().append('<div class="error" >' + mensagem + '</div>');
				};

				var terminado = function()
				{
					controlesHabilitados(true);
				};

				var obj = _this.objetoAlteracaoDeSenha();
				var jqXHR = servicoUsuario.novaSenha(obj);
				jqXHR.done(sucesso).fail(erro).always(terminado);
			}; // submitHandler

			return regras;
		};
		// criarOpcoesValidacao

		_this.definirForm = function definirForm()
		{
			var url = window.location.href;

			if(url.search('editar') != -1)
			{
				_this.alterar = true;
				_this.botoesDeEdicao();
				_this.renderizarModoEdicao();
				_this.modo = 'Edicão';
			}
			else if(url.search('alterar-senha') != -1)
			{
				this.botoesDeAlteracaoDeSenha();
				_this.renderizarModoAlteracaoDeSenha();
				_this.modo = 'Alteração De Senha';
			}
			else if(url.search('visualizar') != -1)
			{
				_this.botoesDeVisualizacao();
				_this.renderizarModoVisualizacao();
				_this.modo = 'Visualização';
			}
			else if(url.search('cadastrar') != -1)
			{
				_this.botoesDeCadastro();
				_this.renderizarModoCadastro();
				_this.modo = 'Cadastro';
			}
		}

		_this.botoesDeCadastro = function botoesDeCadastro()
		{
			_this.botaoCadastrar.removeClass('hide');
			_this.botaoCancelar.removeClass('hide');
		};

		_this.botoesDeAlteracaoDeSenha = function botoesDeAlteracaoDeSenha()
		{
			_this.botaoAlterarSenha.removeClass('hide');
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
			_this.botaoAlterarSenha.removeClass('hide');
		};

		//Função para renderizar  o modo de visualização
		_this.renderizarModoVisualizacao =  function renderizarModoVisualizacao()
		{
			$('.panel-heading').html('Visualizar Perfil');

			desabilitarFormulario();

			$('#nome').focus();

			var id = pegarId(window.location.href, 'visualizar')

			var sucesso = function sucesso(data, textStatus, jqXHR)
			{
				_this.desenhar(data);
			};

			servicoUsuario.comId(id).done(sucesso);

			_this.botaoAlterar.on('click', _this.redirecionarParaEdicao);
			_this.botaoAlterarSenha.on('click', _this.redirecinarParaTrocaDesenha);
			_this.botaoRemover.on('click', _this.remover);
			_this.botaoCancelar.on('click', _this.redirecionarParaPaginaInicial);
			definirMascarasPadroes();
		};

		var renderizarModoAlteracaoDeSenha = function renderizarModoAlteracaoDeSenha()
		{
			$('.panel-heading').html('Alterar Senha');
			$('#senha_atual').focus();
			desabilitarFormulario(false);
			var id = pegarId(window.location.href, 'editar');

			var sucesso = function sucesso(data, textStatus, jqXHR)
			{
				$('#id').val(data.id);
			};

			servicoUsuario.comId(id).done(sucesso);

			_this.botaoAlterarSenha.on('click', _this.alterarSenha);
			_this.botaoCancelar.on('click', _this.redirecionarParaPaginaInicial);
		};

		//Função para renderizar o modo de edição
		_this.renderizarModoEdicao =  function renderizarModoEdicao()
		{
			$('.panel-heading').html('Editar Peril');
			$('#nome').focus();
			desabilitarFormulario(false);
			var id = pegarId(window.location.href, 'editar');

			var sucesso = function sucesso(data, textStatus, jqXHR)
			{
				_this.desenhar(data);
			}

			servicoUsuario.comId(id).done(sucesso);

			_this.botaoAlterar.on('click', _this.alterarUsuario);
			_this.botaoCancelar.on('click', _this.redirecionarParaPaginaInicial);
			definirMascarasPadroes();
		};

		//Função para renderizar o modo de cadastro
		_this.renderizarModoCadastro = function renderizarModoCadastro()
		{
			$('.panel-heading').html('Cadastrar Usuário');
			desabilitarFormulario(false);
			$('#nome').focus();
			_this.botaoCadastrar.on('click', _this.salvarUsuario);
			_this.botaoCancelar.on('click', _this.redirecionarParaLogin);
			_this.botaoPesquisarCep.on('click', _this.pesquisarCep);
			_this.popularSelectEstado();
			definirMascarasPadroes();
		};

		// Obtém o conteúdo atual do form como um objeto
		_this.conteudo = function conteudo()
		{
			return servicoUsuario.criar(
				$('#id').val(),
				$('#nome').val(),
				$('#sobrenome').val(),
				$('#email').val(),
				$('#login').val()
			);
		};

		_this.objetoAlteracaoDeSenha = function objetoAlteracaoDeSenha()
		{
			return servicoUsuario.alterarSenha(
				$('#id').val(),
				$('#senha_atual').val(),
				$('#senha').val(),
				$('#confirmacao_senha').val()
			);
		};

		_this.conteudoCadastro = function conteudoCadastro()
		{
			return servicoUsuario.criar(
				$('#id').val(),
				$('#nome').val(),
				$('#sobrenome').val(),
				$('#email').val(),
				$('#login').val(),
				$('#senha').val()
			);
		};

		// Desenha o objeto no formulário
		_this.desenhar = function desenhar(obj)
		{
			_obj = obj;
			$('#id').val(obj.id);
			$('#nome').val(obj.nome );
			$('#sobrenome').val(obj.nome );
			$('#email').val(obj.email );
			$('#login').val(obj.login );
		};

		_this.salvarUsuario = function salvarUsuario(event)
		{
			// Ao validar e tudo estiver correto, é disparado o método submitHandler(),
			// que é definido nas opções de validação.
			_this.formulario.validate(_this.criarOpcoesValidacaoDeCadastro());
		};

		_this.alterarUsuario = function alterarUsuario(event)
		{
			// Ao validar e tudo estiver correto, é disparado o método submitHandler(),
			// que é definido nas opções de validação.
			_this.formulario.validate(_this.criarOpcoesValidacaoDeEdicao());
		};

		_this.alterarSenha = function alterarSenha(event)
		{
			// Ao validar e tudo estiver correto, é disparado o método submitHandler(),
			// que é definido nas opções de validação.
			_this.formulario.validate(_this.criarOpcoesValidacaoDeAlteracaoDeSenha());
		};

		_this.remover = function remover(event)
		{
			event.preventDefault();

			var sucesso = function sucesso(data, textStatus, jqXHR)
			{
				var servicoLogout = new app.ServicoLogout();
				var controladoraLogout = new app.ControladoraLogout(servicoLogout);
				// Mostra mensagem de sucesso
				toastr.success('Removido');
				controladoraLogout.sair();
			};

			var erro = function erro(jqXHR, textStatus, errorThrown)
			{
				var mensagem = jqXHR.responseText || 'Ocorreu um erro ao tentar remover.';
				toastr.error(mensagem);
			};

			var solicitarRemocao = function solicitarRemocao()
			{
				servicoUsuario.remover(_obj.id).done(sucesso).fail(erro);
			};

			BootstrapDialog.show({
				type	: BootstrapDialog.TYPE_DANGER,
				title	: 'Remover?',
				message	: _obj.nome + _obj.sobrenome,
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
		};

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
		};

		// Configura os eventos do formulário
		_this.configurar = function configurar()
		{
			_this.definirForm();
			_this.formulario = $('#usuario_form');
			_this.formulario.submit(false);
		};
	}; // ControladoraFormPosologia

	// Registrando
	app.ControladoraFormUsuario = ControladoraFormUsuario;

})(window, app, jQuery, toastr);


