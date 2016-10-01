/**
 *  medicamento.serv.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
 (function(app, $)
 {
	'use strict';

	function Medicamento(id, nome, email, login, senha, telefone, criacao, atualizacao) 
	{
		this.id = $id || 0; 
		this.ean = $ean || ''; 
		this.cnpj = $cnpj || ''; 
		this.ggrem = $ggrem || ''; 
		this.registro = $registro || ''; 
		this.nomeComercial = $nomeComercial || ''; 
		this.classeTerapeutica = $classeTerapeutica || ''; 
		this.laboratorio = $laboratorio || ''; 
	};
	
	function ServicoMedicamento()
	{ // Model
		var _this = this;
		// Rota no servidor
		_this.rota = function rota()
		{
			return app.API + '/medicamentos';
		};

		// Cria um objeto de usuario
		this.criar = function criar(id, ean, cnpj, ggrem, registro, nomeComercial, classeTerapeutica, laboratorio)
		{
 			return {
				id : id || 0,
				ean : ean || '',
				cnpj : cnpj || '',
				ggrem : ggrem || '',
				registro : registro || '',
				nomeComercial : nomeComercial || '',
				classeTerapeutica : classeTerapeutica || '',
				laboratorio : laboratorio || ''
			};
		};
		
		_this.adicionar = function adicionar(obj)
		{
			return $.ajax({
				type: "POST",
				url: _this.rota(),
				data: obj
			});
		};

		_this.pesquisarMedicamentos = function pesquisarMedicamentos(term)
		{
			console.log(_this.rota() + '/' + term);
			return $.ajax({
				type: "POST",
				url: _this.rota() + '/' + term,
				data: term
			});
		}
		
		_this.atualizar = function atualizar(obj)
		{
			return $.ajax({
				type: "PUT",
				url: _this.rota() + '/' + obj.id,
				data: obj
			});
		};
		
		_this.remover = function remover(id)
		{
			return $.ajax({
				type: "DELETE",
				url: _this.rota() + '/' + id
			});
		};
		
		_this.comId = function comId(id)
		{
			return $.ajax({
				type: "GET",
				url: _this.rota() + '/' + id
			});
		};	
	}; // ServicoMedicamento
	
	// Registrando
	app.Medicamento = Medicamento;
	app.ServicoMedicamento = ServicoMedicamento;

})(app, $);