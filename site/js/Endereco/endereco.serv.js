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
		latitude,
		longitude
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
		this.latitude = latitude || '';
		this.longitude = longitude || '';
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
			latitude,
			longitude
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
				latitude : latitude || '',
				longitude : longitude || ''
			};
		};

		_this.consultarCepPostmon = function consultarCepPostmon(cep)
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

		_this.rota = function rota()
		{
			return app.API + '/endereco';
		};

		_this.comCep = function comCep(cep)
		{
			return $.ajax({
				type: "POST",
				url: _this.rota()+"/endereco-cep",
				dataType: "json",
				data: {
					cep: cep || ''
				}
			});
		};

		_this.todosEstados = function()
		{
			return $.ajax({
				type : "GET",
				url: _this.rota() + '/estados'
			});
		}
		_this.comUf = function comUf(uf)
		{
			return $.ajax({
				type: "POST",
				url: _this.rota()+"/endereco-uf",
				dataType: "json",
				data: {
					uf: uf || ''
				}
			});
		};

		_this.comGeolocalizacao = function comGeolocalizacao (latitude, longitude)
		{
			console.log(latitude);
			console.log(longitude);
			return $.ajax({
				type: "POST",
				url: _this.rota()+"/endereco-geolocalizacao",
				dataType: "json",
				data: {
					latitude: latitude || '',
					longitude: longitude || ''
				}
			});
		};
	}; // ServicoEndereco

	// Registrando
	app.Endereco = Endereco;
	app.ServicoEndereco = ServicoEndereco;

})(app, $);