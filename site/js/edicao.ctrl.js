/**
 *  edicao.ctrl.js
 *  
 *  @author	Thiago Delgado Pinto
 */
(function(app)
	{
	'use strict';
	function ControladoraEdicao()
	{
		var _this = this;
		var _modoListagem = true;
		var _eventos = []; // eventos disparados ao mudar de modo
		
		// getter/setter
		_this.modoListagem = function modoListagem(b) { 
			if (b !== undefined)
			{
				_modoListagem = b;
				// Dispara os eventos, passando o novo modo
				for (var i in _eventos)
				{
					var evento = _eventos[ i ];
					if (typeof evento === 'function')
					{
						evento(b );
					}
				}
			}
			return _modoListagem;
		};
		
		// Adiciona um evento que será executado ao mudar o modo de listagem
		_this.adicionarEvento = function adicionarEvento(funcao)
		{
			_eventos.push(funcao );
		};
	}
	
	// Registrando
	app.ControladoraEdicao = ControladoraEdicao;
	
} )(app);