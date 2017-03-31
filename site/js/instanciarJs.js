/**
 *  sistema-cfg.js
 *
 *  @author	Rafael
 */
(function(app)
{
	'use strict';

	var servicoFarmacia = new app.ServicoFarmacia();

	var servicoPrincipioAtivo = new app.ServicoPrincipioAtivo();
	var servicoClasseTerapeutica = new app.ServicoClasseTerapeutica();
	var servicoLaboratorio = new app.ServicoLaboratorio();
	var servicoMedicamento = new app.ServicoMedicamento();
	var servicoMedicamentoPrecificado = new app.ServicoMedicamentoPrecificado();
	var servicoMedicamentoPessoal = new app.ServicoMedicamentoPessoal();

	var servicoUsuario = new app.ServicoUsuario();
	var servicoPosologia = new app.ServicoPosologia();
	var servicoFavorito = new app.ServicoFavorito();
	var servicoFavorito = new app.ServicoFavorito();

	function iniciarFavoritos()
	{
		var controladoraListagemFavorito = new app.ControladoraListagemFavorito(
			servicoFavorito,
			servicoMedicamentoPrecificado
		); controladoraListagemFavorito.configurar();
	}

	function iniciarPosologias()
	{
		var controladoraFormPosologia = new app.ControladoraFormPosologia(
			servicoPosologia,
			servicoMedicamentoPessoal
		); controladoraFormPosologia.configurar();

		var ControladoraListagemPosologia = new app.ControladoraListagemPosologia(
			servicoPosologia,
			servicoMedicamentoPessoal,
			controladoraFormPosologia
		); ControladoraListagemPosologia.configurar();
	}

	function iniciarUsuarios()
	{
		var controladoraFormUsuario = new app.ControladoraFormUsuario(
			servicoUsuario
		); controladoraFormUsuario.configurar();
	}

	function iniciarMedicamentosPrecificados()
	{
		var controladoraFormMedicamentoPrecificado = new app.ControladoraFormMedicamentoPrecificado(
			servicoMedicamentoPrecificado,
			servicoUsuario,
			servicoMedicamento,
			servicoLaboratorio,
			servicoFarmacia
		); controladoraFormMedicamentoPrecificado.configurar();


		var ControladoraListagemMedicamentoPrecificado = new app.ControladoraListagemMedicamentoPrecificado(
			servicoMedicamentoPrecificado,
			servicoUsuario,
			servicoMedicamento,
			servicoLaboratorio,
			servicoFarmacia,
			servicoFavorito,
			controladoraFormMedicamentoPrecificado
		);
	}

	$(document).ready(function()
	{
		iniciarUsuarios();
		iniciarPosologias();
		iniciarMedicamentosPrecificados();
		iniciarFavoritos();
	}); // ready

})(app);