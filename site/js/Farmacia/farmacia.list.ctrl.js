/**
 *  assunto.list.ctrl.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
( function( window, app, $, toastr, BootstrapDialog ) 
{
	'use strict';
	
	function ControladoraListagemFarmacia(servicoFarmacia, servicoEndereco, controladoraForm) {
		var _this = this;
		var _cont = 0;

		// Configura a tabela
		var _tabela = $( '#farmacia' ).DataTable( {
			language	: { url: 'vendor/datatables-i18n/i18n/pt-BR.json' },
			dom			: '<"#toolbar">ritlp', // '<"#toolbar">rfitlp'
			serverSide	: true,
			processing	: true,
			ajax		: servicoFarmacia.todos(),
			columns		: [
				{ data: 'id' },
				{ data: 'nome' }
				],
			columnDefs	: [
				{ "width": "10%", "targets": [ 0 ] }
			  ],
			select		: { style: "os", info: false, blurable: true }
		} );
		
		_this.novo = function novo() {
			controladoraForm.desenhar( {} );
		};
		
		_this.alterar = function alterar() {
			var obj = _this.primeiro();
			if ( ! obj ) { return; }
			controladoraForm.desenhar( obj );
		};
		
		_this.remover = function remover() {
			
			var obj = _this.primeiro();
			if ( ! obj ) { return; }
			
			var sucesso = function sucesso( data, textStatus, jqXHR ) {
				// Atualiza a lista
				_tabela.row( { selected: true } ).remove().draw( false );
				// Mostra mensagem de sucesso
				toastr.success( 'Removido' );
			};
			
			var erro = function erro( jqXHR, textStatus, errorThrown ) {
				var mensagem = jqXHR.responseText || 'Ocorreu um erro ao tentar remover.';
				toastr.error( mensagem );
			};
			
			var solicitarRemocao = function solicitarRemocao() {
				servico.remover( obj.id )
					.done( sucesso )
					.fail( erro )
					;
			};
			
			BootstrapDialog.show( {
				type	: BootstrapDialog.TYPE_DANGER,
				title	: 'Remover?',
				message	: obj.nome,
				size	: BootstrapDialog.SIZE_LARGE,
				buttons	: [
					{
						label	: '<u>S</u>im',
						hotkey	: 'S'.charCodeAt( 0 ),
						action	: function( dialog ){
							dialog.close();
							solicitarRemocao();
						}
					},
					{
						label	: '<u>N</u>ão',
						hotkey	: 'N'.charCodeAt( 0 ),
						action	: function( dialog ){
							dialog.close();
						}
					}					
				]
			} );			

		}; // remover
		
		
		_this.atualizar = function atualizar() {
			_tabela.draw();
		};
		
		_this.selecionados = function selecionados() {
			return _tabela.rows( { selected: true } ).data();
		};
		
		_this.contagemSelecionados = function contagemSelecionados() {
			return _this.selecionados().length;
		};
		
		_this.primeiro = function primeiro() {
			var sel = _this.selecionados();
			return sel.length > 0 ? sel[ 0 ] : null;
		};
		
		_this.iniciarFormularioFarmacia = function iniciarFormularioFarmacia()
		{

			var opcoes = {
				show : true,
				keyboard : false,
				backdrop : true
			};

			$('#farmacia_modal').modal(opcoes);

			$('#nome').focus();
		};

		
		_this.configurar = function configurar()
		{
			$( '#cadastrar' ).click( _this.iniciarFormularioFarmacia );
			$( '#alterar' ).click( _this.alterar );
			$( '#remover' ).click( _this.remover );
			$( '#atualizar' ).click( _this.atualizar );
		};	
	} // ControladoraListagemUnidade
	
	// Registrando
	app.ControladoraListagemFarmacia = ControladoraListagemFarmacia;
} )( window, app, jQuery, toastr, BootstrapDialog );