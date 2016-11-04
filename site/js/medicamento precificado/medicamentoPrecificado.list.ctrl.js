/**
 *  medicamentoPrecificado.list.ctrl.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(window, app, $, toastr, BootstrapDialog) 
{
	'use strict';
	
	function ControladoraListagemMedicamentoPrecificado(servicoMedicamentoPrecificado, servicoUsuario, servicoMedicamento, servicoFarmacia, controladoraEdicao) {
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
			ajax		: servicoFarmacia,rota(),
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
					data: 'endereco',
					render: function (data, type, row) {
						return '<span id="enderecoFarmacia"  title="'+_this.retornaTituloTolTipEndereco(row.endereco)+'">'+row.endereco.logradouro+'...</span>'
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

			initComplete: function () {
				this.api().columns('.input-filter').every(function () {
					var column = this;
					var input = document.createElement("input");

					// start - this is the code inserted by me
					$(input).attr( 'style', 'text-align: center;width: 100%');
					// end  - this is the code inserted by me

					$(input).appendTo($(column.footer()).empty()).on('keyup', function () {
						var val = $.fn.dataTable.util.escapeRegex($(this).val());
						column.search(val ? val : '', true, true).draw();
					});
				});
			},

			fnDrawCallback: function(settings){
				$(" td #enderecoFarmacia").each(function(i, value) {
					var title = $(value).parent().attr('title');
					
					$(value).tooltip({
						"delay": 0,
						"track": true,
						"fade": 250,
						placement : 'right',
						content : title,
						offset : '200 100'
					});
				}),

				$('tbody tr').on('click', '#visualizar', _this.visualizar);

				$('tbody tr').on('click', 'td.details-control', _this.definirEventosParaChildDaTabela);
			},

			order: [[1, 'asc']],
			// select		: { style: "os", info: false, blurable: true },
			responsive : true
		});

		_this.cadastrar = function cadastrar() {
			controladoraForm.desenhar( {endereco:{}}, 'cadastrar');
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
			controladoraEdicao.adicionarEvento( function evento( b ) {
				if ( b && _cont > 0 ) {
					_this.atualizar();
				}
				++_cont;
			} );

			$('#cadastrar').click(_this.cadastrar);
			$('#atualizar').click(_this.atualizar);
		};	
	} // ControladoraListagemMedicamentoPrecificado
	
	// Registrando
	app.ControladoraListagemMedicamentoPrecificado = ControladoraListagemMedicamentoPrecificado;
})(window, app, jQuery, toastr, BootstrapDialog);