/**
 *  medicamento.list.ctrl.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(window, app, $, toastr, BootstrapDialog) 
{
	'use strict';
	
	function ControladoraListagemMedicamento(
		servicoMedicamento,
		servicoLaboratorio,
		servicoClasseTerapeutica,
		servicoPrincipioAtivo,
		controladoraForm,
		controladoraEdicao
	) {
		var _this = this;
		var _cont = 0;

		// Configura a tabela

		var _tabela = $('#medicamento').DataTable(
		{
			language	: { url: 'vendor/datatables-i18n/i18n/pt-BR.json' },
			bFilter     : true,
			serverSide	: false,
			processing	: true,
			searching: true,
			ajax		: servicoMedicamento.rota(),
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
					className: 'none',
					data: 'ean',
					targets: 2
				},			

				{
					className: 'none',
					data: 'cnpj',
					targets: 3
				},	

				{
					className: 'none',
					data: 'ggrem',
					targets: 4
				},	

				{
					className: 'none',
					data: 'registro',
					targets: 5
				},						

				{
					data: 'nomeComercial',
					targets: 6
				},

				{
					data: 'composicao',
					targets: 7
				},					

				{
					data: 'laboratorio',
					targets: 8
				},					

				{
					data: 'classeTerapeutica'
					targets: 9
				},

				{
					data: 'principioAtivo',
					targets: 10
				},	
			],

			fnDrawCallback: function(settings){

				$('tbody tr').on('click', '#visualizar', _this.visualizar);

				$('tbody tr').on('click', 'td.details-control', _this.definirEventosParaChildDaTabela);
			},

			order: [[1, 'asc']],
			// select		: { style: "os", info: false, blurable: true },
			responsive : true
		});


		_this.definirEventosParaChildDaTabela = function definirEventosParaChildDaTabela()
		{
			var elemento = $(this).find('i');

			if(elemento.hasClass('glyphicon-plus-sign'))
			{
				elemento.removeClass('glyphicon-plus-sign');
				elemento.addClass('glyphicon-minus-sign');
			}
			else
			{
				elemento.addClass('glyphicon-plus-sign');
				elemento.removeClass('glyphicon-minus-sign');
			}
		};

		// _this.cadastrar = function cadastrar() {
		// 	controladoraForm.desenhar( {endereco:{}}, 'cadastrar');
		// 	controladoraForm.modoAlteracao( false );
		// 	controladoraEdicao.modoListagem( false );
		// };
		
		_this.atualizar = function atualizar(){
 			_tabela.ajax.reload();		
		};

		// _this.visualizar = function visualizar(){
		// 	var objeto = _tabela.row($(this).parent(' td').parent('tr')).data();
		// 	controladoraForm.desenhar(objeto, 'visualizar');
		// 	controladoraForm.modoAlteracao( true );
		// 	controladoraEdicao.modoListagem( false );			 
		// };

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
	} // ControladoraListagemUnidade
	
	// Registrando
	app.ControladoraListagemMedicamento = ControladoraListagemMedicamento;
})(window, app, jQuery, toastr, BootstrapDialog);