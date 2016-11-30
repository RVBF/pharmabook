/**
 *  posologia.list.ctrl.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(window, app, $, toastr, BootstrapDialog) 
{
	'use strict';
	
	function ControladoraListagemPosologia(servicoPosologia, servicoMedicamentoPessoal, controladoraForm, controladoraEdicao) {
		var _this = this;
		var _cont = 0;

		//Configura a tabela
		var _tabela = $('#posologias').DataTable( 
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
					data: 'medicamentoPrecificado',
					render: function (data, type, row) {
						return data.nomeComercial
					},
					responsivePriority: 3,
					targets: 2
				},	
		

				{
					data: 'dose',
					responsivePriority: 4,
					targets: 3
				},			

				{
					data: 'periodicidade',
					targets: 4
				},				

				{
					data: 'administracaoTipo',
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
			],

			fnDrawCallback: function(settings){
				$(" td #enderecoPosologia").each(function(i, value) {
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

		_this.cadastrar = function cadastrar() {
			controladoraForm.desenhar( {endereco:{}});
			controladoraForm.modoAlteracao( false );
			controladoraEdicao.modoListagem( false );
		};
		
		_this.atualizar = function atualizar(){
 			_tabela.ajax.reload();		
		};

		_this.visualizar = function visualizar(){
			var objeto = _tabela.row($(this).parent(' td').parent('tr')).data();
			controladoraForm.desenhar(objeto);
			controladoraForm.modoAlteracao( true );
			controladoraEdicao.modoListagem( false );			 
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
			controladoraEdicao.adicionarEvento( function evento( b ) {
				if ( b && _cont > 0 ) {
					_this.atualizar();
				}
				++_cont;
			} );

			$('#cadastrar').click(_this.cadastrar);
			$('#atualizar').click(_this.atualizar);
		};	
	} // ControladoraListagemUnidade
	
	// Registrando
	app.ControladoraListagemPosologia = ControladoraListagemPosologia;
})(window, app, jQuery, toastr, BootstrapDialog);