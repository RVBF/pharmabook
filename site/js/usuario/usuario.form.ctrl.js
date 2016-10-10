/**
 *  usuario.form.ctrl.js
 *  
 *  @author  Rafael Vinicius Barros Ferreira
 */
(function(window, app, $, toastr) 
{
	'use strict'; 
	 
	function ControladoraFormUsuario(servico, controladoraEdicao) 
	{ // Model

		var _this = this;
		var _modoAlteracao = true;

		var irPraListagem = function irPraListagem()
		{
			controladoraEdicao.modoListagem(true); // Vai pro modo de listagem
		};

		_this.modoAlteracao = function modoAlteracao(b)
		{ 
			// getter/setter
			if (b !== undefined)
			{
				_modoAlteracao = b;
			}

			return _modoAlteracao;
		};

		// Obtém o conteúdo atual do form como um objeto
		_this.conteudo = function conteudo()
		{
			return servico.criar(
				$('#id').val(),
				$('#nome').val(),
				$('#email').val(),
				$('#login').val()
		 	);
		};

		// Desenha o objeto no formulário
		_this.desenhar = function desenhar(obj)
		{
			$('#id').val(obj.id || 0);
			$('#nome').val(obj.nome || '');
			$('#email').val(obj.email || '');
			$('#login').val(obj.login || '');
		};  

		_this.salvar = function salvar(event)
		{
			// Ao validar e tudo estiver correto, é disparado o método submitHandler(),
			// que é definido nas opções de validação.
			$("#medicamento").validate(criarOpcoesValidacao());
		};

		_this.cancelar = function cancelar(event)
		{
			event.preventDefault();
			irPraListagem();
		};


		_this.abrirCadastroUsuario = function abrirCadastroUsuario()
		{
			$('#usuario_form').modal('show');
		};

		// Cria as opções de validação do formulário
		var criarOpcoesValidacao = function criarOpcoesValidacao()
		{
			var opcoes = 
			{
				focusInvalid: false,
				onkeyup: false,
				onfocusout: true,
				errorElement: "div",
				errorPlacement: function(error, element)
				{
				 	error.appendTo("div#msg");
				},

				rules: 
				{
					"nome": {
						required    : true,
						rangelength : [ 2, 60 ]
					},

					"email": {
						required    : true,
						email: true,
						rangelength : [ 6, 50 ],
					},

					"login": {
						required    : true,
						rangelength : [ 8, 20 ]
					},  

					"senha": {
						required    : true,
						rangelength : [ 6, 50 ]
					},
				},

				messages: 
				{
					"nome": {
						required    : "O campo é obrigatório.",
						rangelength : $.validator.format("O Nome deve ter entre {0} e {1} caracteres.")
					},

					"email": {
						required    : "O campo é obrigatório.",
						email       : "Insira um email válido",
						rangelength : $.validator.format("O Email deve ter entre {0} e {1} caracteres.")
					},

					"login": {
						required    : "O Login é obrigatório.",
						rangelength : $.validator.format("O Login deve ter entre {0} e {1} caracteres.")
					},

					"senha": {
						required    : "A Senha é obrigatória.",
						rangelength : $.validator.format("A Senha deve ter entre {0} e {1} caracteres.")
					},       
				}
			}; 

			// Irá disparar quando a validação passar, após chamar o método validate().
			opcoes.submitHandler = function submitHandler(form)
			{
				
				// Habilita/desabilita os controles
				var controlesHabilitados = function controlesHabilitados(b)
				{
					$('#medicamento input').prop("disabled", !b);
					$('#salvar').prop("disabled", !b);
					$('#cancelar').prop("disabled", !b);
				};
				
				controlesHabilitados(false);  

				var sucesso = function sucesso(data, textStatus, jqXHR)
				{
					toastr.success('Salvo');
					irPraListagem();
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
				
				var jqXHR = _this.modoAlteracao() ? servico.atualizar(obj) : servico.adicionar(obj);
				
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

			controladoraEdicao.adicionarEvento(function evento(b) 
			{
				$('#areaForm').toggle(!b);
				if (!b) 
				{
					$('#nome').focus(); // Coloca o foco no 1° input = nome;
				}
			});
			
			$('#cadastrar_usuario').click(_this.abrirCadastroUsuario);			

			$("#medicamento").submit(false);
			$('#salvar').click(_this.salvar);
			$('#cancelar').click(_this.cancelar);           
		};
	}; // ControladoraFormUsuario
	 
	// Registrando
	app.ControladoraFormUsuario = ControladoraFormUsuario;

})(window, app, jQuery, toastr);


