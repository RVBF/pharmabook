/**
 *  medicamento.form.ctrl.js
 *  
 *  @author  Rafael Vinicius Barros Ferreira
 */
(function(window, app, $, toastr) 
{
	'use strict'; 
	 
	function ControladoraFormMedicamentoPrecificado(servicoMedicamentoPrecificado, servicoUsuario, servicoMedicamento, servicoFarmacia, controladoraEdicao) 
	{ // Model

		var _this = this;
		var _modoAlteracao = true;

		var _obj = null;

		var irPraListagem = function irPraListagem() {
			controladoraEdicao.modoListagem(true); // Vai pro modo de listagem
		};

		var encerrarModal = function encerrarModal()
		{
			$('#medicamento_precificado_modal').modal('hide');

			$('.modal').on('hidden.bs.modal', function(){
					$(this).find('#medicamento_form')[0].reset();			
			});
		};
		
		var renderizarModoVisualizacao =  function renderizarModoVisualizacao()
		{
			$('#medicamento_form input').prop("disabled", true);
			$('.modal .modal-footer').empty();
			$('.modal .modal-title').html('Visualizar Farmácia');
			$('.modal .modal-footer').append('<button class="btn btn-success" id="alterar">Alterar</button>');
			$('.modal .modal-footer').append('<button class="btn btn-danger" id="remover">Remover</button>');
			$('.modal .modal-footer').append('<button class="btn btn-info" id="cancelar">Cancelar</button>');
		};

		var renderizarModoEdicao =  function renderizarModoEdicao()
		{
			$('#medicamento_form input').prop("disabled", false);
			$('.modal .modal-footer').empty();
			$('.modal .modal-title').html('Editar Farmácia');
			$('.modal .modal-footer').append('<button class="btn btn-info" id="visualizar">Visualizar</button>');
			$('.modal .modal-footer').append('<button class="btn btn-success" id="salvar">Salvar</button>');
			$('.modal .modal-footer').append('<button class="btn btn-danger" id="cancelar">Cancelar</button>');
		};

		var renderizarModoCadastro = function renderizarModoCadastro()
		{
			$('#medicamento_form input').prop("disabled", false);
			$('.modal .modal-footer').empty();
			$('.modal .modal-title').html('Cadastrar Farmácia');
			$('.modal .modal-footer').append('<button class="btn btn-success" id="cadastrar">Cadastrar</button>');
			$('.modal .modal-footer').append('<button class="btn btn-danger" id="cancelar">Cancelar</button>');
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
			return servicoMedicamnentoPrecificado.criar(
				$('#id').val(),
				$('#preco').val(),
				
				servicoFarmacia,criar(
					$('#id_farmacia').val()
				),

				servicoUsuario.criar(
					$('#id_usuario').val()
				),					
				
				servicoMedicamento.criar(
					$('#id_medicamento').val()
				),
				$('#dataCriacao').val(),
				$('#dataAtualizacao').val()
		 	);
		};

		_this.iniciarFormularioMedicamentoPrecificado = function iniciarFormularioMedicamentoPrecificado()
		{
			var opcoes = {
				show : true,
				keyboard : false,
				backdrop : true
			};

			var modal = $('#areaForm').find('#medicamento_precificado_modal').modal(opcoes);

			$('#nome').focus();
		};

		// Desenha o objeto no formulário
		_this.desenhar = function desenhar(obj, operacao = '')
		{
			_obj = obj;
			_this.iniciarFormularioMedicamentoPrecificado();

			$('#id').val(obj.id || 0);
			$('#nome').val(obj.nome ||'');
			$('#telefone').val(obj.telefone ||'');
			$('#endereco_id').val(obj.endereco.id || 0); 
			$('#cep').val(obj.endereco.cep || ''); 
			$('#logradouro').val(obj.endereco.logradouro || ''); 
			$('#numero').val(obj.endereco.numero || ''); 
			$('#complemento').val(obj.endereco.complemento || ''); 
			$('#referencia').val(obj.endereco.referencia || ''); 
			$('#bairro').val(obj.endereco.bairro || ''); 
			$('#cidade').val(obj.endereco.cidade || ''); 
			$('#estado').val(obj.endereco.estado || ''); 
			$('#pais').val(obj.endereco.pais || '');

			if(operacao == 'visualizar')
			{
				renderizarModoVisualizacao();
			}
			else
			{
				if(operacao == 'cadastrar')
				{
					renderizarModoCadastro();
				}
			}

			definirMascaras();
		};

		_this.salvar = function salvar(event)
		{
			// Ao validar e tudo estiver correto, é disparado o método submitHandler(),
			// que é definido nas opções de validação.

			$("#medicamento_precificado_form").validate(criarOpcoesValidacao());
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
					servicoMedicamnentoPrecificado.remover( _obj.id ).done( sucesso ).fail( erro );
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

					"numero": {
						required    : true
					},				

					"bairro": {
						required    : true,
					},


					"estado": {
						required    : true,
					},

					"pais": {
						required    : true,
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

					"numero": {
						required    : "O campo número é obrigatório."
					},

					"bairro": {
						required    : "O campo bairro é obrigadorio."
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
					$('#medicamento_form input').prop("disabled", !b);
					$('#cadastrar').prop("disabled", !b);
					$('#salvar').prop("disabled", !b);
					$('#visualizar').prop("disabled", !b);
					$('#cancelar').prop("disabled", !b);
				};
				
				controlesHabilitados(false);  

				var sucesso = function sucesso(data, textStatus, jqXHR)
				{
					encerrarModal();

					toastr.success('Salvo');

					irPraListagem();

					encerrarModal();
				};
				
				var erro = function erro(jqXHR, textStatus, errorThrown)
				{
					var mensagem = jqXHR.responseText;
					$('#msg').append('<div class="error" >' + mensagem + '</div>');
				};
				
				var terminado = function()
				{
					controlesHabilitados(true);
				};
				
				var obj = _this.conteudo();

				if(_this.modoAlteracao())
				{
					var jqXHR = servicoMedicamnentoPrecificado.atualizar(obj);
				}
				else
				{
					var jqXHR =  servicoMedicamnentoPrecificado.adicionar(obj);
				}
				
				jqXHR
					.done(sucesso)
					.fail(erro)
					.always(terminado)
				;
				
			}; // submitHandler
			
			return opcoes;
		};
		// criarOpcoesValidacao  

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
				$('.modal').find(" #medicamento_form").submit(false);
				$('.modal').find('.modal-footer').on('click', '#cancelar', _this.cancelar);
				$('.modal').find('.modal-footer').on('click', '#cadastrar', _this.salvar);
				$('.modal').find('.modal-footer').on('click', '#salvar', _this.salvar);
				$('.modal').find('.modal-footer').on('click', '#alterar', _this.alterar);
				$('.modal').find('.modal-footer').on('click', '#remover', _this.remover);
				$('.modal').find('.modal-footer').on('click', '#visualizar', _this.visualizar);
				$('.modal').find('.modal-body').on('click', '.pesquisar_cep', _this.consultarEnderecoPorCep);
			});
		};
	}; // ControladoraFormMedicamentoPrecificado
	 
	// Registrando
	app.ControladoraFormMedicamentoPrecificado = ControladoraFormMedicamentoPrecificado;

})(window, app, jQuery, toastr);


