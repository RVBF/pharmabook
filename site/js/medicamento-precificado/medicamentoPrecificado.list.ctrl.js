/**
 *  medicamentoPrecificado.list.ctrl.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(window, app, $, toastr, BootstrapDialog) 
{
	'use strict';
	function ControladoraListagemMedicamentoPrecificado(
			servicoMedicamentoPrecificado,
			servicoUsuario,
			servicoMedicamento,
			servicoLaboratorio,
			servicoFarmacia,
			controladoraForm,
			controladoraEdicao
		) {
		var _this = this;
		var _cont = 0;

		// Configura a tabela

		var _tabela = $('#medicamento_precificado').DataTable(
		{
			language	: { url: 'vendor/datatables-i18n/i18n/pt-BR.json' },
			bFilter     : true,
			serverSide	: false,
			processing	: true,
			searching: true,
			responsive : true,
			autoWidth: false,
			ajax		: servicoMedicamentoPrecificado.rota(),
			columnDefs: [
				{
					className: 'details-control',
					targets: 0,
					responsivePriority: 1,
					data: null,
					defaultContent: '<i class=" expandir_linha_datatable glyphicon glyphicon-plus-sign"></i>'
				},

				{
					data: 'id',
					targets: 1,
					visible : false

				},

				{
					data: 'medicamento',
					render: function (data, type, row) {
						return data.nomeComercial + '|' + data.composicao
					},
					responsivePriority: 3,
					targets: 2
				},	

				{
					data: 'preco',
					responsivePriority: 4,
					targets: 3
				},

				{
					data: 'usuario',
					render: function (data, type, row) {
						return 'Postado por:'+ data.nome
					},
					targets: 4
				},				

				{
					data: 'dataCriacao',
					targets: 5
				},					

				{
					data: 'dataAtualizacao',
					responsivePriority: 5,
					targets: 5
				},	

				{
					render: function (){
						return '<a class="btn btn-primary" id="visualizar">Visualizar</a>'					
					},
					responsivePriority: 2,

					targets: 6
				}
			],
		
			fnDrawCallback: function(settings){

				$('tbody tr').on('click', '#visualizar', _this.visualizar);

				$('tbody tr').on('click', 'td.details-control', _this.definirEventosParaChildDaTabela);
			},

			order: [[1, 'asc']]
		});

		_this.cadastrar = function cadastrar() {
			controladoraForm.desenhar( {medicamento:{}, farmacia:{}, laboratorio:{}}, 'cadastrar');
			controladoraForm.modoAlteracao( false );
			controladoraEdicao.modoListagem( false );
		};
		
		_this.atualizar = function atualizar(){
 			_tabela.ajax.reload();		
		};

		_this.visualizar = function visualizar(){
			var objeto = _tabela.row($(this).parent(' td').parent('tr')).data();
	
			controladoraForm.desenhar(objeto, 'visualizar');
			controladoraForm.modoAlteracao( true );
			controladoraEdicao.modoListagem( false );			 
		};

		_this.configurar = function configurar()
		{
			// controladoraEdicao.adicionarEvento( function evento( b ) {
			// 	if ( b && _cont > 0 ) {
			// 		_this.atualizar();
			// 	}
			// 	++_cont;
			// } );

			$('#cadastrar').click(_this.cadastrar);
			$('#atualizar').click(_this.atualizar);
		};	
	} // ControladoraListagemMedicamentoPrecificado
	
	// Registrando
	app.ControladoraListagemMedicamentoPrecificado = ControladoraListagemMedicamentoPrecificado;
})(window, app, jQuery, toastr, BootstrapDialog);