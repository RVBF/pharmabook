/**
 *  index.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(window, app, $, toastr){
	'use strict';	
	function ControladoraIndex(servico, servicoSessao){
		var _this = this;
		var usuarioSessao =  window.sessionStorage.getItem('usuario');;

		_this.verificar = function()
		{
			var erro = function erro( jqXHR, textStatus, errorThrown ) {
				var mensagem = jqXHR.responseText || 'Erro ao acessar página.';
				toastr.error( mensagem );
				servicoSessao.redirecionarParalogin();
				
				if(servicoSessao.getSessao() == null || servicoSessao.getSessao() == '')
				{
					servicoSessao.limparSessionStorage();
				}

				return false;
			};

			var jqXHR = servicoSessao.verificarSessao();
			jqXHR.fail(erro);

			return true;
		};
	}; 

	app.ControladoraIndex = ControladoraIndex;
})(window, app, jQuery, toastr );