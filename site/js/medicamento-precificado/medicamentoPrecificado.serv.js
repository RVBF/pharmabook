/**
 *  medicamento precificado.serv.js
 *
 *  @author	Rafael Vinicius Barros Ferreira
 */
 (function(app, $)
 {
	'use strict';

	function MedicamentoPrecificado(
		id,
		preco,
		farmacia,
		medicamento,
		imagem
	)
	{
		this.id = id;
		this.preco = preco;
		this.farmacia = farmacia;
		this.medicamento = medicamento;
		this.dataCriacao = dataCriacao;
		this.dataAtualizacao = dataAtualizacao;
		this.imagem = imagem;
	};

	function ServicoMedicamentoPrecificado()
	{ // Model
		var _this = this;
		// Rota no servidor
		_this.rota = function rota()
		{
			return app.API + '/medicamentos-precificados';
		};

		// Cria um objeto de medicamento precificado
		this.criar = function criar(
			id,
			preco,
			farmacia,
			medicamento,
			imagem
		)
		{
			console.log(arguments);
 			return {
				id : id || 0,
				preco : preco || '',
				farmacia : farmacia || null,
				medicamento : medicamento || null,
				imagem : imagem || null
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

		_this.todos = function todos() {
			return $.ajax({
				type : "GET",
				url: _this.rota()
			});
		};

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

		_this.pesquisarMedicamentoPrecificado = function pesquisarMedicamentoPrecificado(medicamentoPrecificado, farmaciaId) {
			return $.ajax({
				type: "POST",
				url: _this.rota() + "/pesquisar-medicamentoPrecificado",
				dataType: "json",
				data: {
					medicamentoPrecificado: medicamentoPrecificado || '',
					farmaciaId: farmaciaId || ''
				}
			});
		};

		_this.getMedicamentoPrecificados = function getMedicamentoPrecificados(medicamentoPrecificado, farmaciaId) {
			return $.ajax({
				type: "POST",
				url: _this.rota() + "/buscar-medicamentoPrecificado",
				dataType: "json",
				data: {
					medicamentoPrecificado: medicamentoPrecificado || '',
					farmaciaId: farmaciaId || ''
				}
			});
		};
	}; // ServicoMedicamentoPrecificado

	// Registrando
	app.MedicamentoPrecificado = MedicamentoPrecificado;
	app.ServicoMedicamentoPrecificado = ServicoMedicamentoPrecificado;
})(app, $);