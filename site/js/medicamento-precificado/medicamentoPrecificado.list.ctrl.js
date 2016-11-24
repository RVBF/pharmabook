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
			controladoraForm,
			controladoraEdicao
	)
	{
		var _this = this;
		var _cont = 0;

		var  converterFloatReal = function converterFloatReal(valor)
		{
			var inteiro = null, decimal = null, c = null, j = null;
			var aux = new Array();

			valor = ""+valor;
			c = valor.indexOf(".",0);
			//encontrou o ponto na string
			if(c > 0)
			{
				//separa as partes em inteiro e decimal
				inteiro = valor.substring(0,c);
				decimal = valor.substring(c+1,valor.length);
			}
			else
			{
				inteiro = valor;
			}

			//pega a parte inteiro de 3 em 3 partes
			for (j = inteiro.length, c = 0; j > 0; j-=3, c++)
			{
				aux[c]=inteiro.substring(j-3,j);
			}

			//percorre a string acrescentando os pontos
			inteiro = "";
			for(c = aux.length-1; c >= 0; c--)
			{
				inteiro += aux[c]+'.';
			}
			//retirando o ultimo ponto e finalizando a parte inteiro

			inteiro = inteiro.substring(0,inteiro.length-1);

			decimal = parseInt(decimal);
			if(isNaN(decimal))
			{
				decimal = "00";
			}
			else
			{
				decimal = ""+decimal;

				if(decimal.length === 1)
				{
					decimal = "0"+decimal;
				}
			}
			valor = inteiro+","+decimal;
			
			return valor;
		}

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
						return 'R$' + converterFloatReal(data)
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
					data: 'medicamento',
					render: function (data, type, row) {
						return  data.composicao + '.'
					},
					targets: 5
				},				

				{
					data: 'dataCriacao',
					targets: 6,
				},

				{
					data: 'usuario',
					render: function (data, type, row) {
						return  data.nome + '.'
					},
					targets: 7
				},				
				
				{
					data: 'dataAtualizacao',
					targets: 8,
					responsivePriority: 5
				},	

				{
					render: function (){
						var btn = '<div class="btn-group">';
						btn += '<a class="btn btn-primary" id="adicionar_estoque"><i class="glyphicon glyphicon-plus"></i></a>';
						btn += '<a class="btn btn-default" id="adicionar_favoritos"><i class="glyphicon glyphicon-star-empty"></i></a>';
						btn += '<a class="btn btn-info" id="visualizar"><i class="glyphicon glyphicon-search"></i></a>';
						btn += '</div>';
						return btn					
					},
					responsivePriority: 2,

					targets: 9
				}
			],
		
			fnDrawCallback: function(settings){

				$('tbody tr').on('click', '#visualizar', _this.visualizar);

				$('tbody tr').on('click', 'td.details-control', _this.definirEventosParaChildDaTabela);
			},

			order: [[1, 'asc']]
		});

		_this.cadastrar = function cadastrar() {
			controladoraForm.desenhar( {medicamento:{}, farmacia:{}, laboratorio:{}}, 'cadastrar');
			controladoraForm.modoAlteracao( false );
			controladoraEdicao.modoListagem( false );
		};
		
		_this.atualizar = function atualizar(){
 			_tabela.ajax.reload();		
		};

		_this.visualizar = function visualizar(){
			var objeto = _tabela.row($(this).parent(' td').parent('tr')).data();
	
			controladoraForm.desenhar(objeto, 'visualizar');
			controladoraForm.modoAlteracao( true );
			controladoraEdicao.modoListagem( false );			 
		};

		_this.configurar = function configurar()
		{
			// controladoraEdicao.adicionarEvento( function evento( b ) {
			// 	if ( b && _cont > 0 ) {
			// 		_this.atualizar();
			// 	}
			// 	++_cont;
			// } );

			$('#cadastrar').click(_this.cadastrar);
			$('#atualizar').click(_this.atualizar);
		};	
	} // ControladoraListagemMedicamentoPrecificado
	
	// Registrando
	app.ControladoraListagemMedicamentoPrecificado = ControladoraListagemMedicamentoPrecificado;
})(window, app, jQuery, toastr, BootstrapDialog);