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
		bairro,
		complemento,
		referencia,
		cidade,
		estado,
		pais,
		dataCriacao,
		dataAtualizacao
	) 
	{
		this.id = id;
		this.cep = cep;
		this.logradouro = logradouro;
		this.numero = numero;
		this.bairro = bairro;
		this.complemento = complemento;
		this.referencia = referencia;
		this.cidade = cidade;
		this.estado = estado;
		this.pais = pais;
		this.dataCriacao = dataCriacao;
		this.dataAtualizacao = dataAtualizacao;	
	};
	
	function ServicoEndereco(data)
	{ // Model
		var _this = this;
		
		// Cria um objeto de Endereco
		this.criar = function criar(
			id,
			cep,
			logradouro,
			numero,
			bairro,
			complemento,
			referencia,
			cidade,
			estado,
			pais,
			dataCriacao,
			dataAtualizacao
		)
		{
 			return {
				id : id || 0,
				cep : cep || '',
				logradouro : logradouro || '',
				numero : numero || 0,
				bairro : bairro || '',
				complemento : complemento || '',
				referencia : referencia || '',
				cidade : cidade || '',
				estado : estado || '',
				pais : pais || '',
				dataAtualizacao : data.getDataAtual() || '',
				dataCriacao : (id == 0) ? data.getDataAtual() : '' || ''	
			};
		};

		_this.consultarCep = function consultarCep(cep)
		{
			return $.ajax({
				url:'http://cep.republicavirtual.com.br/web_cep.php',
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