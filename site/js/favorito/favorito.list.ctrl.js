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
					data: 'id',
					targets: 0,
					visible : false
				},

				{
					data: 'medicamentoPrecificado',
					render: function (data, type, row) {
						return data.medicamento.nomeComercial
					},
					responsivePriority: 1,
					targets: 1
				},

				{
					data: 'medicamentoPrecificado',
					render: function (data, type, row) {
						return 'R$' + app.converterEmMoeda(data.preco)
					},
					responsivePriority: 3,
					targets: 2
				},

				{
					data: 'opcoes',
					render: function (){
						return '<a class="btn btn-danger opcoes_tabela" title="Remover." id="remover"><i class="glyphicon glyphicon-remove"></i></a>';
					},
					responsivePriority: 2,
					targets: 3
				}
			],

			fnDrawCallback: function(settings){
				$(" td .opcoes_tabela").each(function(i, value) {
					var title = $(value).parent().attr('title');

					$(value).tooltip({
						"delay": 0,
						"track": true,
						"fade": 250,
						placement : 'bottom',
						content : title,
						offset : '200 100'
					});
				});

				$('tbody tr').on('click', '#visualizar', _this.visualizar);
				$('tbody tr').on('click', '#remover', _this.remover);
			},

			order: [[1, 'asc']]
		});

		_this.atualizar = function atualizar(){
 			_tabela.ajax.reload();
		};

		_this.visualizar = function visualizar(){
			var objeto = _tabela.row($(this).parent().parent('tr')).data();
			controladoraForm.desenhar(objeto);
			controladoraForm.modoAlteracao( true );
			controladoraEdicao.modoListagem( false );
		};

		_this.remover = function remover()
		{
			var objeto = _tabela.row($(this).parent().parent('tr')).data();

			var sucesso = function sucesso(data, textStatus, jqXHR)
			{
				toastr.success('Medicamento removido com sucesso.');
				_this.atualizar();
			};

			var erro = function erro(jqXHR, textStatus, errorThrown)
			{
				var mensagem = jqXHR.responseText;
				toastr.error(mensagem);
			};
			var jqXHR = servicoFavorito.remover(objeto.id);

			jqXHR.done(sucesso).fail(erro);
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