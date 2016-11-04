/**
 *  medicamentoPrecificado.list.ctrl.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(window, app, $, toastr, BootstrapDialog) 
{
	'use strict';
	console.log(app);
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
			ajax		: servicoMedicamentoPrecificado.rota(),
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
					className: 'none',
					data: 'dataCriacao',
					targets: 4
				},

				{
					data: 'dataAtualizacao',
					targets: 5
				},

				{
					render: function (){
						return '<a class="btn btn-primary" id="visualizar">Visualizar</a>'					
					},

					targets: 6
				}
			],
		
			fnDrawCallback: function(settings){
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