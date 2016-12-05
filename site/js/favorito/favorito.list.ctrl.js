/**
 *  favorito.list.ctrl.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(window, app, $, toastr, BootstrapDialog) 
{
	'use strict';
	function ControladoraListagemFavorito(
		servicoFavorito,
		servicoMedicamentoPrecificado,
		controladoraEdicao
	)
	{
		var _this = this;
		var _cont = 0;
		console.log(servicoFavorito);
		// Configura a tabela
		var _tabela = $('#favorito').DataTable(
		{
			language	: { url: 'vendor/datatables-i18n/i18n/pt-BR.json' },
			bFilter     : true,
			serverSide	: false,
			processing	: true,
			searching: true,
			responsive : true,
			autoWidth: false,
			ajax		: servicoFavorito.rota(),
			columnDefs: [
				{
					className: 'details-control',
					targets: 0,
					data: '',
					responsivePriority: 1,
					defaultContent: '<i class=" glyphicon glyphicon-plus-sign"></i>'
				},

				{
					data: 'id',
					targets: 1,
					visible : false
				},

				// {
				// 	data: 'medicamentoPrecificado',
				// 	render: function (data, type, row) {
				// 		return data.medicamento.nomeComercial
				// 	},
				// 	responsivePriority: 3,
				// 	targets: 2
				// },	

				// {
				// 	data: 'medicamentoPrecificado',
				// 	render: function (data, type, row) {
				// 		return 'R$' + app.converterEmMoeda(data.preco)
				// 	},
				// 	responsivePriority: 4,
				// 	targets: 3
				// },

				{
					render: function (){
						return '<a class="btn btn-primary" id="visualizar">Visualizar</a>'					
					},
					responsivePriority: 2,
					targets: 4
				}
			],
		
			fnDrawCallback: function(settings){
				$('tbody tr').on('click', '#visualizar', _this.visualizar);
				$('tbody tr').on('click', 'td.details-control', _this.definirEventosParaChildDaTabela);
			},

			order: [[1, 'asc']]
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
		
		_this.atualizar = function atualizar(){
 			_tabela.ajax.reload();		
		};

		_this.visualizar = function visualizar(){
			var objeto = _tabela.row($(this).parent().parent('tr')).data();
			controladoraForm.desenhar(objeto);
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
	} // ControladoraListagemFavorito
	
	// Registrando
	app.ControladoraListagemFavorito = ControladoraListagemFavorito;
})(window, app, jQuery, toastr, BootstrapDialog);