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
		servicoFavorito
	)
	{
		var _this = this;
		var _cont = 0;
		var router = window.router;
		var _tabela = null;
		var botaoCadastrar = $('#cadastrar');
		var botaoRemover = $('#excluir');
		var botaoAlterar = $('#alterar');
		var botaoVisualizar = $('#visualizar');
		var botaoAtualizar = $('#atualizar');
		var idTabela = $('#medicamento_precificado');
		// Configura a tabela
		var gerarOpcoesTabela = function gerarOpcoesTabela()
		{
			var objeto = $.extend( true, {}, app.dtOptions );

			objeto.ajax = servicoMedicamentoPrecificado.rota();
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
					render: function (data, type, row) {
						return data.nomeComercial
					},
					responsivePriority: 3,
					targets: 2
				},

				{
					data: 'composicao',
					render: function (data, type, row) {
						return  row.medicamento.composicao + '.'
					},
					targets: 5
				}
			];

			objeto.fnDrawCallback = function(settings){
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

		// Encaminha o usuário para o Formulário de Cadastro
		_this.cadastrar = function cadastrar()
		{
			router.navigate( '/medicamentos-precificados/cadastrar' );
		}


		_this.atualizar = function atualizar()
		{
 			_tabela.ajax.reload();
		};

		_this.visualizar = function visualizar()
		{
			var objeto = _tabela.row($(this).closest('tr')).data();
			router.navigate('/medicamentos-precificados/visualizar/' + objeto.id + '/');
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
				var mensagem = jqXHR.responseText;
				toastr.error(mensagem);
			};

			jqXHR.done(sucesso).fail(erro);
		};

		_this.configurar = function configurar()
		{
			_tabela = idTabela.DataTable(gerarOpcoesTabela());
			$('#cadastrar').click(_this.cadastrar);
			$('#atualizar').click(_this.atualizar);
		};
	} // ControladoraListagemMedicamentoPrecificado

	// Registrando
	app.ControladoraListagemMedicamentoPrecificado = ControladoraListagemMedicamentoPrecificado;
})(window, app, jQuery, toastr, BootstrapDialog);