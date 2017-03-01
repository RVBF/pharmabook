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
			servicoFavorito,
			controladoraForm,
			controladoraEdicao
	)
	{
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
					data: '',
					responsivePriority: 1,
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
						return data.nomeComercial
					},
					responsivePriority: 3,
					targets: 2
				},

				{
					data: 'preco',
					render: function (data, type, row) {
						return 'R$' + app.converterEmMoeda(data)
					},
					responsivePriority: 4,
					targets: 3
				},

				{
					data: 'farmacia',
					render: function (data, type, row) {
						return data.nome
					},
					targets: 4
				},

				{
					data: 'composicao',
					render: function (data, type, row) {
						return  row.medicamento.composicao + '.'
					},
					targets: 5
				},

				{
					data: 'classeTerapeutica',
					render: function (data, type, row) {
						return  row.medicamento.classeTerapeutica.nome + '.'
					},
					targets: 6
				},

				{
					data: 'principioAtivo',
					render: function (data, type, row) {
						return  row.medicamento.principioAtivo.nome + '.'
					},
					targets: 7
				},

				{
					data: 'laboratorio',
					render: function (data, type, row) {
						return  row.medicamento.laboratorio.nome + '.'
					},
					targets: 8
				},

				{
					data: 'criador',
					render: function (data, type, row) {
						console.log(data);
						return  data.nome + ' ' + data.sobrenome + '.';
					},
					targets: 9
				},

				{
					data: 'atualizador',
					render: function (data, type, row) {
						return  data.nome + ' ' + data.sobrenome + '.';
					},
					targets: 10
				},

				{
					data: 'dataCriacao',
					targets: 11
				},

				{
					data: 'dataAtualizacao',
					targets: 12,
					responsivePriority: 5
				},

				{
					render: function (){
						var btn = '<div class="btn-group botoes">';
						btn += '<a class="btn btn-default opcoes_tabela" title="Adicionar medicamento aos favoritos."  id="adicionar_favoritos"><i class="glyphicon glyphicon-star-empty"></i></a>';
						btn += '<a class="btn btn-info opcoes_tabela" title="Visualizar medicamento." id="visualizar"><i class="glyphicon glyphicon-search"></i></a>';
						btn += '</div>';
						return btn
					},
					responsivePriority: 2,

					targets: 13
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

				$("td #adicionar_favoritos").each(function(i, value)
				{
					var objeto = _tabela.row($(this).parent().parent().parent('tr')).data();
					var jqXHR =  servicoFavorito.estaNosFavoritos(objeto.id);

					var elemento = $(this);

					var sucesso = function sucesso(data, textStatus, jqXHR)
					{
						if(elemento.hasClass('glyphicon-star-empty'))
						{
							elemento.removeClass('glyphicon-star-empty');
							elemento.addClass('glyphicon-star');
						}
					};

					var erro = function erro(jqXHR, textStatus, errorThrown)
					{
						if(elemento.hasClass('glyphicon-star'))
						{
							elemento.removeClass('glyphicon-star');
							elemento.addClass('glyphicon-star-empty');
						}
					};

					jqXHR.done(sucesso).fail(erro);
				});

				$('tbody tr').on('click', '#visualizar', _this.visualizar);
				$('tbody tr').on('click', '#adicionar_favoritos', _this.adicionarAosFavoritos);
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

		_this.cadastrar = function cadastrar()
		{
			controladoraForm.desenhar({
				id : 0,
				medicamento:{
					classeTerapeutica: {},
					principioAtivo : {},
					laboratorio : {}
				},
				farmacia : {}
			});

			controladoraForm.modoAlteracao( false );
			controladoraEdicao.modoListagem( false );
		};

		_this.atualizar = function atualizar()
		{
 			_tabela.ajax.reload();
		};

		_this.visualizar = function visualizar()
		{
			var objeto = _tabela.row($(this).parent().parent().parent('tr')).data();
			controladoraForm.desenhar(objeto);
			controladoraForm.modoAlteracao( true );
			controladoraEdicao.modoListagem( false );
		};

		_this.adicionarAosFavoritos = function adicionarAosFavoritos()
		{
			var objeto = _tabela.row($(this).parent().parent().parent('tr')).data();
			var medicamento =  servicoFavorito.criar(
				0,
				objeto
			);

			var jqXHR = servicoFavorito.adicionar(medicamento);

			var sucesso = function sucesso(data, textStatus, jqXHR)
			{
				toastr.success('Medicamento adicionado aos favoritos.');
			};

			var erro = function erro(jqXHR, textStatus, errorThrown)
			{
				console.log(jqXHR.responseText);
				var mensagem = jqXHR.responseText;
				toastr.error(mensagem);
			};

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
	} // ControladoraListagemMedicamentoPrecificado

	// Registrando
	app.ControladoraListagemMedicamentoPrecificado = ControladoraListagemMedicamentoPrecificado;
})(window, app, jQuery, toastr, BootstrapDialog);