/**
 *  data.js
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */
(function(window, app, $) 
{
	'use strict';

	function Data() {

		var _this = this;
		var _cont = 0;
		var date =  new Date();

		_this.getHorarioAtual = function getHorarioAtual()
		{
			return _this.getHora() + ":"+ _this.getMinutos() + ":" + _this.getSegundos();
		}

		_this.getHora = function getHora()
		{ 
			return date.getHours();
		}

		_this.getMinutos = function getMinutos()
		{
			return date.getMinutes();
		}

		_this.getSegundos = function getSegundos()
		{
			return date.getSeconds();
		}

		_this.getMillesegundos = function getMillesegundos()
		{
			return date.getMilliseconds();
		}	

		_this.getDataAtual = function getDataAtual()
		{
			return _this.getDia() + "/"+ _this.getMes() + "/" + _this.getAno();
		}		

		_this.getDataPorExtenso = function getDataPorExtenso()
		{
			return _this.getDia() + " de "+ _this.getDiaSemanaPorExtenso() + " de " + _this.getAno();
		}

		_this.getDiaSemanaPorExtenso = function getDiaSemanaPorExtenso()
		{
			var diasDaSemana = ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado"];

			return diasDaSemana[getDia()];
		}

		_this.getMesPorExtenso = function  getMesPorExtenso()
		{
			var mesesDoAno = ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Agosto", "Outubro", "Novembro", "Dezembro"];

			return mesesDoAno[getMes()];
		}

		_this.getDia = function getDia()
		{
			return date.getDate();
		}			

		_this.getMes = function getMes()
		{
			return date.getMonth() + 1;
		}

		_this.getAno = function getAno()
		{
			return date.getFullYear();
		}		
	} // Data
	
	// Registrando
	app.Data = Data;

})(window, app, jQuery);