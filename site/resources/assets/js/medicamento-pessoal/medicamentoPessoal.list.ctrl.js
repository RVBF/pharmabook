/**
 *  medicamentoPessoal.list.ctrl.js
 *
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(window, app, $, toastr, BootstrapDialog)
{
	'use strict';
	function ControladoraListagemMedicamentoPessoal(servicoMedicamentoPessoal)
	{
		var _this = this;
		var _cont = 0;
		var router = window.router;
		var _tabela = null;
		_this.botaoCadastrar = $('#cadastrar');
		_this.botaoAtualizar = $('#atualizar');
		_this.botaoPosologias = $('#posologias');
		_this.idTabela = $('#medicamento_pessoal');

		// Configura a tabela
		var opcoesTabela = function opcoesTabela()
		{
			var objeto = $.extend( true, {}, app.dtOptions );

			objeto.ajax = servicoMedicamentoPessoal.rota();

			objeto.columnDefs = [
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
  						html += '<a href="#" data-toggle="dropdown" class="btn btn-mini btn-primary btn-demo-space">Opções <span class="caret"></span></a>';
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
			];

			objeto.fnDrawCallback = function(settings)
			{
				$('tbody tr').on('click', '#visualizar', _this.visualizar);
				$('tbody tr').on('click', 'td.details-control', _this.definirEventosParaChildDaTabela);
				$('tbody tr').on('click', '#cadastrar_posologia', _this.cadastrarPosologia);
			};

			return objeto;
		};

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
			router.navigate('/medicamentos-pessoais/cadastrar/');
		}

		// Encaminha o usuário para o Formulário de Cadastro
		_this.cadastrarPosologia = function cadastrarPosologia()
		{
			var objeto = _tabela.row($(this).closest('tr')).data();
			router.navigate('/posologias/cadastrar/' + objeto.id + '/');
		}

		_this.listarPosologias = function listarPosologias()
		{
			router.navigate('/posologias/');
		};

		_this.atualizar = function atualizar()
		{
 			_tabela.ajax.reload();
		};

		_this.visualizar = function visualizar()
		{
			var objeto = _tabela.row($(this).closest('tr')).data();
			router.navigate('/medicamentos-pessoais/visualizar/' + objeto.id + '/');
		};

		_this.configurar = function configurar()
		{
			_tabela = _this.idTabela.DataTable(opcoesTabela());
			_this.botaoCadastrar.on('click', _this.cadastrar);
			_this.botaoAtualizar.on('click', _this.atualizar);
			_this.botaoPosologias.on('click', _this.listarPosologias);
		};
	} // ControladoraListagemMedicamentoPessoal

	// Registrando
	app.ControladoraListagemMedicamentoPessoal = ControladoraListagemMedicamentoPessoal;
})(window, app, jQuery, toastr, BootstrapDialog);