/**
 *  assunto.list.ctrl.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(window, app, $, toastr, BootstrapDialog) 
{
	'use strict';
	
	function ControladoraListagemFarmacia(servicoFarmacia, servicoEndereco, controladoraForm) {
		var _this = this;
		var _cont = 0;

		// Configura a tabela

		var _tabela = $('#farmacia').DataTable(
		{
			language	: { url: 'vendor/datatables-i18n/i18n/pt-BR.json' },
			dom			: '<"#toolbar">ritlp', // '<"#toolbar">rfitlp'
			serverSide	: true,
			processing	: true,
			searching: true,
			ajax		: servicoFarmacia.rota(),
			columnDefs: [

				{
					className: 'details-control',
					targets: 0,
					data: null,
					defaultContent: '<i class=" expandir_linha_datatable glyphicon glyphicon-plus-sign"></i>'
				},

				{
					data: 'id',
					targets: 1,
					visible : false

				},

				{
					data: 'nome',
					targets: 2
				},			

				{
					className: 'none',
					data: 'telefone',
					targets: 3
				},

				{
					render: function (){
						return '<a class="btn btn-primary" id="visualizar">Visualizar</a>'					
					},

					targets: 4
				}
			],

			order: [[1, 'asc']],
			responsive : true
		});

		_this.atualizar = function atualizar(){
			_tabela.draw();
		};

		_this.visualizar = function visualizar(){

			var obj = $(this).closest('.ui-datatable').attr('id');
			console.log(obj);
		};
		
		_this.iniciarFormularioFarmacia = function iniciarFormularioFarmacia()
		{
			var opcoes = {
				show : true,
				keyboard : false,
				backdrop : true
			};

			$('#farmacia_modal').modal(opcoes);

			$('#nome').focus();
		};

		
		_this.configurar = function configurar()
		{
			$('#cadastrar').click(_this.iniciarFormularioFarmacia);
			$('#alterar').click(_this.alterar);
			$('#remover').click(_this.remover);
			$('#atualizar').click(_this.atualizar);
		};	
	} // ControladoraListagemUnidade
	
	// Registrando
	app.ControladoraListagemFarmacia = ControladoraListagemFarmacia;
})(window, app, jQuery, toastr, BootstrapDialog);