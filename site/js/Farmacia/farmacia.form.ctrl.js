/**
 *  farmacia.form.ctrl.js
 *  
 *  @author  Rafael Vinicius Barros Ferreira
 */
(function(window, app, $, toastr) 
{
	'use strict'; 
	 
	function ControladoraFormFarmacia(servicoFarmacia, servicoEndereco) 
	{ // Model

		var _this = this;

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

		// Desenha o objeto no formulário
		_this.desenhar = function desenhar(obj)
		{
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
		};  


		_this.salvar = function salvar(event)
		{
			// Ao validar e tudo estiver correto, é disparado o método submitHandler(),
			// que é definido nas opções de validação.

			$("#farmacia_form").validate(criarOpcoesValidacao());
		};

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
					$('#farmacia_form input').prop("disabled", !b);
					$('#cadastrar').prop("disabled", !b);
					$('#cancelar').prop("disabled", !b);
				};
				
				controlesHabilitados(false);  

				var sucesso = function sucesso(data, textStatus, jqXHR)
				{
					$('#farmacia_modal').modal('hide');

					$('.modal').on('hidden.bs.modal', function(){
    					$(this).find('#farmacia_form')[0].reset();
					});

					toastr.success('Salvo');
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

				var jqXHR = servicoFarmacia.adicionar(obj);
				
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
			$('.modal').find("#farmacia_form").submit(false);
			$('#cadastrar').click(_this.salvar);
		};
	}; // ControladoraFormFarmacia
	 
	// Registrando
	app.ControladoraFormFarmacia = ControladoraFormFarmacia;

})(window, app, jQuery, toastr);


