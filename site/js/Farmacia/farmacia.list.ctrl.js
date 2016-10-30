/**
 *  farmacia.list.ctrl.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(window, app, $, toastr, BootstrapDialog) 
{
	'use strict';
	
	function ControladoraListagemFarmacia(servicoFarmacia, servicoEndereco, controladoraForm, controladoraEdicao) {
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
					data: 'telefone',
					targets: 3
				},				

				{
					render: function (data, type, row) {
						return '<button type="button" class="btn btn-secondary endereco" id="enderecoFarmacia"   data-toggle="tooltip" data-placement="bottom" title="'+row.endereco+'">'+row.endereco+'</button>'
					},
					targets: 4
				},				

				{
					className: 'none',
					data: 'dataCriacao',
					targets: 5
				},

				{
					data: 'dataAtualizacao',
					targets: 6
				},

				{
					render: function (){
						return '<a class="btn btn-primary" id="visualizar">Visualizar</a>'					
					},

					targets: 7
				}
			],

			order: [[1, 'asc']],
			responsive : true
		});

		_this.cadastrar = function cadastrar() {
			controladoraForm.desenhar( {endereco:{}} );
			controladoraForm.modoAlteracao( false );
			controladoraEdicao.modoListagem( false );
		};
		
		_this.atualizar = function atualizar(){
			_tabela.draw();
		};

		_this.visualizar = function visualizar(){
			var obj = $(this).closest('.ui-datatable').attr('id');
		};

		_this.configurar = function configurar()
		{
			controladoraEdicao.adicionarEvento( function evento( b ) {
				if ( b && _cont > 0 ) {
					_this.atualizar();
				}
				++_cont;
			} );

			$(function () {
			 	$('[data-toggle="tooltip"]').tooltip()
			});
			
			$(document).ready(function(){

				$('#enderecoFarmacia').tooltip({
					'trigger': 'manual',
					'placement': 'bottom'  
				}).tooltip('show');   
			})

			$('#cadastrar').click(_this.cadastrar);
			$('#atualizar').click(_this.atualizar);
		};	
	} // ControladoraListagemUnidade
	
	// Registrando
	app.ControladoraListagemFarmacia = ControladoraListagemFarmacia;
})(window, app, jQuery, toastr, BootstrapDialog);