/**
 *  posologia.list.ctrl.js
 *
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(window, app, $, toastr, BootstrapDialog)
{
	'use strict';

	function ControladoraListagemPosologia(
		servicoPosologia,
		servicoMedicamentoPessoal,
		controladoraListagemMedicamentoPessoal,
		controladoraForm
	) {
		var _this = this;
		var _cont = 0;

		//Configura a tabela
		var _tabela = $('#areaListaPosologia #posologias').DataTable(
		{
			language	: { url: 'vendor/datatables-i18n/i18n/pt-BR.json' },
			bFilter     : true,
			serverSide	: false,
			processing	: true,
			searching: true,
			responsive : true,
			autoWidth: false,
			ajax		: servicoPosologia.rota(),
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
					data: 'medicamentoPessoal',
					render: function (data, type, row) {
						return data.medicamento.nomeComercial;
					},
					responsivePriority: 3,
					targets: 2
				},

				{
					data: 'descricao',
					targets: 3
				},

				{
					data: 'dose',
					render: function (data, type, row) {
						return data + ' ' +row.medicamentoPessoal.tipoUnidade.toLowerCase() + ' a cada ' + row.periodicidade + ' ' + row.tipoPeriodicidade.toLowerCase() + '.';
					},
					responsivePriority: 2,
					targets: 4
				},

				{
					data: 'administracao',
					render: function (data, type, row) {
						return row.medicamentoPessoal.administracao +'.';
					},
					responsivePriority: 5,
					targets: 5
				},

				{
					render: function ()
					{
						return '<a class="btn btn-primary" id="visualizar">Visualizar</a>';
					},
					responsivePriority: 4,

					targets: 6
				}
			],

			fnDrawCallback: function(settings)
			{
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

		_this.atualizar = function atualizar()
		{
 			_tabela.ajax.reload();
		};

		_this.visualizar = function visualizar()
		{
			var objeto = _tabela.row($(this).closest('tr')).data();
			controladoraForm.desenhar(objeto);
			controladoraForm.modoAlteracao( true );
			controladoraForm.modoVisualizacao( true );
		};

		_this.configurar = function configurar()
		{
			$('#atualizar_posologias').click(_this.atualizar);
			$('span #areaListaPosologia').on('click', '#voltar_estoque', function()
			{
				$('#areaListaPosologia').addClass('hide');
				$('#areaLista').removeClass('hide');
				controladoraListagemMedicamentoPessoal.atualizar();
			});
		};
	} // ControladoraListagemUnidade

	// Registrando
	app.ControladoraListagemPosologia = ControladoraListagemPosologia;
})(window, app, jQuery, toastr, BootstrapDialog);