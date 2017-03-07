/**
 *  medicamentoPessoal.list.ctrl.js
 *
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(window, app, $, toastr, BootstrapDialog)
{
	'use strict';
	function ControladoraListagemMedicamentoPessoal(
		servicoMedicamentoPessoal,
		controladoraForm,
		controladoraEdicao
	)
	{
		var _this = this;
		var _cont = 0;

		// Configura a tabela
		var _tabela = $('#medicamento_pessoal').DataTable(
		{
			language	: { url: 'vendor/datatables-i18n/i18n/pt-BR.json' },
			bFilter : true,
			serverSide : false,
			processing : true,
			searching : true,
			responsive : true,
			autoWidth : false,
			ajax : servicoMedicamentoPessoal.rota(),
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
					render: function (data, type, row)
					{
						return data.nomeComercial;
					},
					responsivePriority: 2,
					targets: 2
				},

				{
					data: 'medicamento',
					render: function (data, type, row)
					{
						return data.composicao;
					},
					responsivePriority: 3,
					targets: 3
				},

				{
					data: 'validade',
					targets: 4
				},

				{
					data: 'administracao',
					targets: 5
				},

				{
					data: 'medicamentoForma',
					targets: 6
				},

				{
					data: 'quantidade',
					targets: 7
				},

				{
					data: 'capacidadeRecipiente',
					render: function (data, type, row)
					{
						return data  + ' ' + row.tipoUnidade;
					},
					targets: 8
				},

				{
					data: 'medicamento',
					render: function (data, type, row)
					{
						return data.classeTerapeutica.nome
					},
					targets: 9
				},

				{
					data: 'medicamento',
					render: function (data, type, row)
					{
						return data.principioAtivo.nome
					},
					targets: 10
				},

				{
					data: 'medicamento',
					render: function (data, type, row)
					{
						return data.laboratorio.nome
					},
					targets: 11
				},

				{
					data: 'dataCriacao',
					targets: 12
				},

				{
					data: 'dataAtualizacao',
					targets: 13
				},

				{
					render: function ()
					{
						var html = '<div class="btn-group">';
  						html += '<a href="#" data-toggle="dropdown" class="btn btn-mini btn-success btn-demo-space">Opções <span class="caret"></span></a>';
  						html += '<ul class="dropdown-menu dropdown-menu-right">';
  						html += '<li><a class="dropdown-item" id="visualizar">Visualizar Medicamento Pessoal</a></li>';
    					html += '<li class="dropdown-divider"></li>';
  						html += '<li><a class="dropdown-item" id="cadastrar_posologia">Cadastrar Posologia</a></li>';
						html += '</ul>'
						html += '</div>'
						return html;
					},
					targets: 14
				}
			],

			fnDrawCallback: function(settings)
			{
				$('tbody tr').on('click', '#visualizar', _this.visualizar);
				$('tbody tr').on('click', 'td.details-control', _this.definirEventosParaChildDaTabela);
				$('tbody tr').on('click', '#cadastrar_posologia', _this.cadastrarPosologia);
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

		_this.cadastrarPosologia = function cadastrarPosologia()
		{
			var servicoPosologia = undefined;
			var controladoraFormPosologia = undefined;

			var medicamentoPessoal = _tabela.row($(this).closest('tr')).data();

			var definirCadastroPosologia = function definirCadastroPosologia()
			{
				servicoPosologia = new app.ServicoPosologia();
				controladoraFormPosologia = new app.ControladoraFormPosologia(servicoPosologia, servicoMedicamentoPessoal, controladoraEdicao);

				controladoraFormPosologia.configurar();

				controladoraFormPosologia.desenhar({
					id : 0,
					dose : '',
					descricao: '',
					periodicidade: '',
					tipoPeriodicidade : '',
					medicamentoPessoal
				});

				controladoraFormPosologia.modoAlteracao( false );
				controladoraEdicao.modoListagem( false );
			};

			$('#areaPosologia').empty().load('posologia.html', '', definirCadastroPosologia);
		};

		_this.carregarPosologias = function carregarPosologias(event)
		{
			event.preventDefault();
			var controladoraFormPosologia = undefined;
			var controladoraListagem = undefined;
			var servicoPosologia = undefined;

			var definirListagemPosologias = function definirListagemPosologias()
			{
				$('#areaLista').addClass('hide');
				$('#areaListaPosologia').removeClass('hide');

				servicoPosologia = new app.ServicoPosologia();

				controladoraFormPosologia = new app.ControladoraFormPosologia(servicoPosologia, servicoMedicamentoPessoal, controladoraEdicao);
				controladoraFormPosologia.configurar();

				controladoraListagem =  new app.ControladoraListagemPosologia(servicoPosologia, servicoMedicamentoPessoal, _this, controladoraFormPosologia, controladoraEdicao);
				controladoraListagem.configurar();
				controladoraEdicao.modoListagem(true);
			};

			$('#areaPosologia').empty().load('posologia.html', '', definirListagemPosologias);
		};

		_this.cadastrar = function cadastrar()
		{
			controladoraForm.desenhar({
				id : 0,
				tipoUnidade : null,
				medicamento:{
					classeTerapeutica: {},
					principioAtivo : {},
					laboratorio : {}
				}
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
			var objeto = _tabela.row($(this).closest('tr')).data();
			controladoraForm.desenhar(objeto);
			controladoraForm.modoAlteracao( true );
			controladoraEdicao.modoListagem( false );
		};

		_this.configurar = function configurar()
		{
			controladoraEdicao.adicionarEvento( function evento( b )
			{
				if ( b && _cont > 0 )
				{
					_this.atualizar();
				}
				++_cont;
			} );

			$('#cadastrar').click(_this.cadastrar);
			$('#atualizar').click(_this.atualizar);
			$('#posologias').on('click', _this.carregarPosologias);
		};
	} // ControladoraListagemMedicamentoPessoal

	// Registrando
	app.ControladoraListagemMedicamentoPessoal = ControladoraListagemMedicamentoPessoal;
})(window, app, jQuery, toastr, BootstrapDialog);