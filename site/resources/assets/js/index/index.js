/**
 *  index.js
 *
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(window, app, $, toastr){
	'use strict';
	function ControladoraIndex(servico, servicoSessao)
	{
		var _this = this;
		_this.verficarLogin = function (req, event, next)
		{
			var servicoSessao = new app.ServicoSessao();

			var erro = function erro(jqXHR, textStatus, errorThrown)
			{
				var mensagem = jqXHR.responseText || 'Erro ao acessar página.';
				toastr.error(mensagem);

				if(servicoSessao.getSessao() == null || servicoSessao.getSessao() == '')
				{
					servicoSessao.limparSessionStorage();
				}

				servicoSessao.redirecionarParalogin();
			};
			var jqXHR = servicoSessao.verificarSessao();
			jqXHR.fail(erro);
			next;
		};

		_this.configurar = function configurar()
		{
			definirMascarasPadroes();
		};
	};

	app.ControladoraIndex = ControladoraIndex;
})(window, app, jQuery, toastr );