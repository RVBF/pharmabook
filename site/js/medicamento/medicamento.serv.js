/**
 *  medicamento.serv.js
 *
 *  @author	Rafael Vinicius Barros Ferreira
 */
 (function(app, $)
 {
	'use strict';

	function Medicamento(
		id,
		ean,
		cnpj,
		ggrem,
		registro,
		nomeComercial,
		composicao,
		laboratorio,
		classeTerapeutica,
		principioAtivo
	)
	{
		this.id = id || 0;
		this.ean = ean || '';
		this.cnpj = cnpj || '';
		this.ggrem = ggrem || '';
		this.registro = registro || '';
		this.nomeComercial = nomeComercial || '';
		this.composicao = composicao || '';
		this.laboratorio = laboratorio || '';
		this.classeTerapeutica = classeTerapeutica || '';
		this.principioAtivo = principioAtivo || '';
	};

	function ServicoMedicamento()
	{ // Model
		var _this = this;
		// Rota no servidor
		_this.rota = function rota()
		{
			return app.API + '/medicamentos';
		};

		// Cria um objeto de farmacia
		this.criar = function criar(
			id,
			ean,
			cnpj,
			ggrem,
			registro,
			nomeComercial,
			composicao,
			laboratorio,
			classeTerapeutica,
			principioAtivo
		)
		{
 			return {
				id : id || 0,
				ean : ean || '',
				cnpj : cnpj || '',
				ggrem : ggrem || '',
				registro : registro || '',
				nomeComercial : nomeComercial || '',
				composicao : composicao || '',
				laboratorio : laboratorio || '',
				classeTerapeutica : classeTerapeutica || '',
				principioAtivo : principioAtivo || ''
			};
		}

		_this.todos = function todos() {
			return $.ajax({
				type : "GET",
				url: _this.rota()
			});
		};

		_this.pesquisarMedicamentoParaAutoComplete = function pesquisarMedicamentoParaAutoComplete(medicamento) {
			return $.ajax({
				type: "POST",
				url: _this.rota()+"/pesquisar-medicamento",
				dataType: "json",
				data: {
					medicamento: medicamento || ''
				}
			});
		}

		_this.comId = function comId(id){
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