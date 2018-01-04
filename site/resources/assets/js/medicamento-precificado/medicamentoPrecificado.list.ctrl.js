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
		var botaoCobolaborar = $('#colaborar');
		var botaoRemover = $('#excluir');
		var botaoAlterar = $('#alterar');
		var botaoVisualizar = $('#visualizar');
		var botaoAtualizar = $('#atualizar');
		var idTabela = $('#medicamento_precificado');
		// Configura a tabela
		var gerarOpcoesTabela = function gerarOpcoesTabela()
		{
			var objeto = $.extend( true, {}, app.dtOptions );
			console.log(objeto);
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
					render: function (data, type, row)
					{
						return data.nomeComercial;
					},
					responsivePriority: 3,
					targets: 2
				},

				{
					data: 'laboratorio',
					render: function (data, type, row) {
						return  row.medicamento.laboratorio.nome;
					},
					targets: 3
				},

				{
					data: 'composicao',
					render: function (data, type, row) {
						return  row.medicamento.composicao;
					},
					targets: 4
				}
			];

			objeto.fnDrawCallback = function(settings){
				$('tbody tr').on('click', '#visualizar', _this.visualizar);
				$('tbody tr').on('click', '#adicionar_favoritos', _this.adicionarAosFavoritos);
				$('tbody tr').on('click', 'td.details-control', _this.definirEventosParaChildDaTabela);
			};

			return objeto;
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
			botaoCobolaborar.click(_this.cadastrar);
			botaoAtualizar.click(_this.atualizar);
		};
	} // ControladoraListagemMedicamentoPrecificado

	// Registrando
	app.ControladoraListagemMedicamentoPrecificado = ControladoraListagemMedicamentoPrecificado;
})(window, app, jQuery, toastr, BootstrapDialog);