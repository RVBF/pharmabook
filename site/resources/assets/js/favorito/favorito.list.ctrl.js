/**
 *  favorito.list.ctrl.js
 *
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(window, app, $, toastr, BootstrapDialog)
{
	'use strict';
	function ControladoraListagemFavorito(servicoFavorito)
	{
		var _this = this;
		var _cont = 0;
		var router = window.router;
		var _tabela = null;
		_this.botaoAtualizar = $('#atualizar');
		_this.idTabela = $('#favorito');
		// Configura a tabela
		var opcoesTabela = function opcoesTabela()
		{
			var objeto = $.extend( true, {}, app.dtOptions );

			objeto.ajax = servicoFavorito.rota();

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
					data: 'nomeComercial',
					render: function (data, type, row) {
						console.log(row.medicamentoPrecificado);
						return row.medicamentoPrecificado.medicamento.nomeComercial;
					},
					responsivePriority: 1,
					targets: 2
				},

				{
					data: 'apresentacao',
					render: function (data, type, row)
					{
						console.log(row);
						return row.medicamentoPrecificado.medicamento.composicao;
					},
					responsivePriority: 1,
					targets: 3
				},

				{
					data: 'farmacia',
					render: function (data, type, row) {
						return row.medicamentoPrecificado.farmacia.nome;
					},
					responsivePriority: 1,
					targets: 4
				},

				{
					data: 'preco',
					render: function (data, type, row) {
						return 'R$' + converterEmMoeda(row.medicamentoPrecificado.preco)
					},
					responsivePriority: 3,
					targets: 5
				},

				{
					data: 'opcoes',
					render: function (){
						return '<a class="btn btn-danger opcoes_tabela" title="Remover." id="remover"><i class="glyphicon glyphicon-remove"></i></a>';
					},
					responsivePriority: 2,
					targets: 6
				},

				{
					data: 'telefone',
					render: function (data, type, row) {
						return row.medicamentoPrecificado.farmacia.telefone;
					},
					targets: 7
				},

				{
					data: 'endereco',
					render: function (data, type, row)
					{
						return _this.formataEndereco(row.medicamentoPrecificado.farmacia.endereco);
					},
					targets: 8
				}
			];

			objeto.fnDrawCallback = function(settings)
			{
				$('tbody tr').on('click', '#remover', _this.remover);
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

		_this.formataEndereco = function formataEndereco (endereco)
		{
			var html = '';
			if(endereco)
			{
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
			}

			return html + '.';
		};

		_this.atualizar = function atualizar(){
 			_tabela.ajax.reload();
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
			_tabela = _this.idTabela.DataTable(opcoesTabela());
		};
	} // ControladoraListagemFavorito

	// Registrando
	app.ControladoraListagemFavorito = ControladoraListagemFavorito;
})(window, app, jQuery, toastr, BootstrapDialog);