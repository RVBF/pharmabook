/**
 *  farmacia.serv.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
 (function(app, $)
 {
	'use strict';

	function Endereco(
		id,
		cep,
		logradouro,
		numero,
		complemento,
		referencia,
		bairro,
		cidade,
		estado,
		pais
	) 
	{
		this.id = id || 0; 
		this.cep = cep || ''; 
		this.logradouro = logradouro || ''; 
		this.numero = numero || 0; 
		this.complemento = complemento || ''; 
		this.referencia = referencia || ''; 
		this.bairro = bairro || ''; 
		this.cidade = cidade || ''; 
		this.estado = estado || ''; 
		this.pais = pais || ''; 
	};
	
	function ServicoEndereco()
	{ // Model
		var _this = this;
		// Rota no servidor
		_this.rota = function rota()
		{
			return app.API + '/enderecos';
		};

		// Cria um objeto de Endereco
		this.criar = function criar(
			id,
			cep,
			logradouro,
			numero,
			complemento,
			referencia,
			bairro,
			cidade,
			estado,
			pais
		)
		{
 			return {
				id : id || 0,
				cep : cep || '',
				logradouro : logradouro || '',
				numero : numero || 0,
				complemento : complemento || '',
				referencia : referencia || '',
				bairro : bairro || '',
				cidade : cidade || '',
				estado : estado || '',
				pais : pais || ''
			};
		};
	}; // ServicoEndereco
	
	// Registrando
	app.Endereco = Endereco;
	app.ServicoEndereco = ServicoEndereco;

})(app, $);