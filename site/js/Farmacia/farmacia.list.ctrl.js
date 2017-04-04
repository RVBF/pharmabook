/**
 *  farmacia.list.ctrl.js
 *
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(window, app, $, toastr, BootstrapDialog)
{
	'use strict';

	function ControladoraListagemFarmacia(servicoFarmacia, servicoEndereco)
	{
		var _this = this;
		var _cont = 0;
		var _tabela = null;
		_this.botaoCadastrar = $('#cadastrar');
		_this.botaoAtualizar = $('#atualizar');
		_this.idTabela = $('#farmacia');

		//Configura a tabela
		_this.opcoesDaTabela = function opcoesDaTabela()
		{
			var objeto = $.extend(true, {}, app.dtOptions);
			objeto.ajax =servicoFarmacia.rota();

			objeto.columnDefs = [
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
					data: 'nome',
					responsivePriority: 3,
					targets: 2
				},

				{
					data: 'telefone',
					responsivePriority: 4,
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
					responsivePriority: 2,

					targets: 7
				}
			];

			objeto.fnDrawCallback = function(settings)
			{
				$(" td #enderecoFarmacia").each(function(i, value)
				{
					var title = $(value).parent().attr('title');

					$(value).tooltip({
						"delay": 0,
						"track": true,
						"fade": 250,
						placement : 'bottom',
						content : title,
						offset : '200 100'
					});
				}),

				$('tbody tr').on('click', '#visualizar', _this.visualizar);

				$('tbody tr').on('click', 'td.details-control', _this.definirEventosParaChildDaTabela);
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
			router.navigate('/farmacias/cadastrar/');
		}

		_this.atualizar = function atualizar(){
 			_tabela.ajax.reload();
		};

		_this.visualizar = function visualizar(){
			var objeto = _tabela.row($(this).parent(' td').parent('tr')).data();
			router.navigate('/farmacias/visualizar/' +  objeto.id + '/');

		};

		_this.retornaTituloTolTipEndereco = function retornaTituloTolTipEndereco (endereco)
		{
			var html = '';
			if(endereco.logradouro != '')
			{
				html += endereco.logradouro + ', ';
			}

			if(endereco.numero != null)
			{
				html += endereco.numero + ', ';
			}

			if(endereco.bairro != '')
			{
				html += endereco.bairro + ', ';
			}

			if(endereco.complemento != '')
			{
				html += endereco.complemento + ', ';
			}

			if(endereco.referencia != '')
			{
				html += endereco.referencia + ', ';
			}


			if(endereco.cidade != '')
			{
				html += endereco.cidade + ', ';
			}

			if(endereco.estado != '')
			{
				html += endereco.estado + ', ';
			}

			if(endereco.pais != '')
			{
				html += endereco.pais + ', ';
			}

			if(endereco.cep != '')
			{
				html += 'cep: ' + endereco.cep;
			}

			return html + '.';
		};

		_this.configurar = function configurar()
		{
			_tabela = _this.idTabela.DataTable(_this.opcoesDaTabela());
			_this.botaoCadastrar.click(_this.cadastrar);
			_this.botaoAtualizar.click(_this.atualizar);
		};
	} // ControladoraListagemUnidade

	// Registrando
	app.ControladoraListagemFarmacia = ControladoraListagemFarmacia;
})(window, app, jQuery, toastr, BootstrapDialog);