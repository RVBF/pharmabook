/**
 *  usuario.list.ctrl.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(window, app, $, toastr, BootstrapDialog) {
	'use strict';
	
	function ControladoraListagemUsuario(servico, controladoraEdicao, controladoraForm) {
		
		var _this = this;
		var _cont = 0;
		
		// Configura a tabela
		var _tabela = $('#usuarios').DataTable({
			language	: { url: 'vendor/datatables-i18n/i18n/pt-BR.json' },
			dom			: '<"#toolbar">ritlp', // '<"#toolbar">rfitlp'
			serverSide	: true,
			processing	: true,
			ajax		: servico.rota(),
			columns		: [
				{ data: 'id' },
				{ data: 'nome' },
				{ data: 'email' },
				{ data: 'login' },
				{ data: 'dataCriacao' },
				{ data: 'dataAtualizacao' }
			],
			columnDefs	: [
				{ "width": "5%", "targets": [ 0 ] }
			  ],
			select		: { style: "os", info: false, blurable: true },
			responsive : true
		});
		
		_this.novo = function novo()
		{
			controladoraForm.desenhar({});
			controladoraForm.modoAlteracao(false);
			controladoraEdicao.modoListagem(false);
		};
		
		_this.alterar = function alterar()
		{
			var obj = _this.primeiro();
			
			if (! obj)
			{
				return; 
			}

			controladoraForm.desenhar(obj);
			controladoraForm.modoAlteracao(true);
			controladoraEdicao.modoListagem(false);
		};
		
		_this.remover = function remover()
		{
				
			var obj = _this.primeiro();
			
			if (! obj)
			{
				return; 
			}
			
			var sucesso = function sucesso(data, textStatus, jqXHR)
			{
				// Atualiza a lista
				_tabela.row({ selected: true }).remove().draw(false);
				// Mostra mensagem de sucesso
				toastr.success('Removido');
			};
			
			var erro = function erro(jqXHR, textStatus, errorThrown)
			{
				var mensagem = jqXHR.responseText || 'Ocorreu um erro ao tentar remover.';
				toastr.error(mensagem);
			};
			
			var solicitarRemocao = function solicitarRemocao()
			{
				servico.remover(obj.id).done(sucesso).fail(erro);
			};
			
			BootstrapDialog.show({
				type	: BootstrapDialog.TYPE_DANGER,
				title	: 'Remover?',
				message	: obj.nome,
				size	: BootstrapDialog.SIZE_LARGE,
				buttons	: [
					{
						label	: '<u>S</u>im',
						hotkey	: 'S'.charCodeAt(0),
						action	: function(dialog){
							dialog.close();
							solicitarRemocao();
						}
					},
					{
						label	: '<u>N</u>ão',
						hotkey	: 'N'.charCodeAt(0),
						action	: function(dialog){
							dialog.close();
						}
					}					
				]
			});			
		}; // remover
		
		
		_this.atualizar = function atualizar()
		{
			_tabela.draw();
		};
		
		_this.selecionados = function selecionados()
		{
			return _tabela.rows({ selected: true }).data();
		};
		
		_this.contagemSelecionados = function contagemSelecionados()
		{
			return _this.selecionados().length;
		};
		
		_this.primeiro = function primeiro()
		{
			var sel = _this.selecionados();
			return sel.length > 0 ? sel[ 0 ] : null;
		};
		
		_this.configurar = function configurar()
		{
			
			controladoraEdicao.adicionarEvento(function evento(b)
			{
				$('#areaLista').toggle(b);
				
				if (b && _cont > 0)
				{
					_this.atualizar();
				}
				++_cont;
			});
			
			$('#novo').click(_this.novo);
			$('#alterar').click(_this.alterar);
			$('#remover').click(_this.remover);
			$('#atualizar').click(_this.atualizar);
		};
	} // ControladoraListagemUsuario

	// Registrando
	app.ControladoraListagemUsuario = ControladoraListagemUsuario;

})(window, app, jQuery, toastr, BootstrapDialog);