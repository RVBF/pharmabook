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
		this.numero = numero || '';
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
				id : id  || undefined,
				cep : cep || '',
				logradouro : logradouro || '',
				numero : numero || '',
				complemento : complemento || '',
				referencia : referencia || '',
				bairro : bairro || '',
				cidade : cidade || '',
				estado : estado || '',
				pais : pais || ''
			};
		};

		_this.consultarCepOnline = function consultarCepOnline(cep)
		{
			return $.ajax({
				url:'http://api.postmon.com.br/v1/cep/'+cep,
				type:'get',
				dataType:'json',
				crossDomain: true,
				data:{
					cep: cep, //pega valor do campo
					formato:'json'
				}
			});
		};
	}; // ServicoEndereco

	// Registrando
	app.Endereco = Endereco;
	app.ServicoEndereco = ServicoEndereco;

})(app, $);