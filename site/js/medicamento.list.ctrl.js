/**
 *  usuario.list.ctrl.js
 *  
 *  @author	Thiago Delgado Pinto
 */
(function(window, app, $, toastr, BootstrapDialog) {
	'use strict';
	
	function ControladoraListagemMedicamento(servico, controladoraEdicao, controladoraForm) {
		
		var _this = this;
		var _cont = 0;

		var tabela = $('#medicamentos').DataTable(
		{
			language	: { url: 'vendor/datatables-i18n/i18n/pt-BR.json' },
			dom			: '<"#toolbar">ritlp', // '<"#toolbar">rfitlp'
			serverSide	: true,
			processing	: true,
			searching: true,
			ajax		: servico.rota(),
			columns		: [
				{ data : 'nomeComercial'},
				{ data : 'classeTerapeutica'}
			],
			columnDefs	: [
				{ "width": "5%", "targets": [ 0 ] }
			  ],
			select		: { style: "os", info: false, blurable: true },
			responsive : true
		});

		// Função para fazer pesquisa instantânea
		// _this.pesquisarMedicamento = function pesquisarMedicamento(campo)
		// {
		// 	// #myInput is a <input type="text"> element
		// 	$('#myInput').on( 'keyup', function () {
		// 		table.search( this.value ).draw();
		// 	} );
		// }
	
		_this.selecionados = function selecionados() {
			return _tabela.rows({ selected: true }).data();
		};
		
		_this.contagemSelecionados = function contagemSelecionados() {
			return _this.selecionados().length;
		};
		
		_this.primeiro = function primeiro() {
			var sel = _this.selecionados();
			return sel.length > 0 ? sel[ 0 ] : null;
		};
		
		
		_this.configurar = function configurar() {
			
			controladoraEdicao.adicionarEvento(function evento(b) {
				$('#areaLista').toggle(b);
				if (b && _cont > 0) {
					_this.atualizar();
				}

				// $('#busca_medicamentos').keyup( function(e){
				// 	if(e.which == 13)
				// 	_this.pesquisarMedicamento($(this))
				// });
				
				++_cont;
			});
		};
	} // ControladoraListagemMedicamento
	
	
	// Registrando
	app.ControladoraListagemMedicamento = ControladoraListagemMedicamento;

})(window, app, jQuery, toastr, BootstrapDialog);