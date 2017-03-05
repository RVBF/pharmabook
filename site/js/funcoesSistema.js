(function(app) {
	'use strict';
	$(document).ready(function()
	{
		app.converterEmFloat = function converterEmFloat(moeda)
		{
			moeda = moeda.replace(".","");

			moeda = moeda.replace(",",".");

			return parseFloat(moeda);
		};

		app.converterEmMoeda = function converterEmMoeda(numero, casasDecimais)
		{
			if(casasDecimais == undefined)
			{
				var casasDecimais = 2;
			}

			var number = parseFloat(numero).toFixed(casasDecimais);

			numero += '';

			numero = number.replace(".", ",");
			return numero;
		};

		app.desabilitarFormulario = function desabilitarFormulario(status = true)
		{
			$('form input,select,textarea,checkbox').each(function(){
				$(this).prop('disabled', status);
			});
		};

		app.retornarInteiroEmStrings = function retornarInteiroEmStrings(string)
		{
			var numero = string.replace(/[^0-9]/g,'');
			return parseInt(numero);
		};

		app.definirMascarasPadroes = function definirMascarasPadroes()
		{
			var mascara = new Inputmask("decimal", { radixPoint: ".", digits:2, autoGroup: true, groupSeparator: ",", groupSize: 3, rightAlign: false, negative: false });
			mascara.mask($('.decimal'));

			var optionSelct2 = {
				width: 'copy',
				theme: 'bootstrap',
				minimumResultsForSearch: 1
			};

			$(".select2").select2(optionSelct2);

			var optionsDatePicker = {
				format: "dd/mm/yyyy",
				language: 'pt-BR',
				startView: 0,
				startDate: "today",
				autoclose: true,
				todayHighlight: true,
				todayBtn: true
			};

			$('.datepicker').datepicker(optionsDatePicker);

			$('.datepicker').mask('99/99/9999');

			$(".preco").maskMoney({
				symbol:'R$ ',
				showSymbol:true,
				thousands:'.',
				decimal:',',
				symbolStay: true
			});
		};

		$.ui.autocomplete.prototype._resizeMenu = function ()
		{
			var ul = this.menu.element;
			ul.outerWidth(this.element.outerWidth());
		}

		$.validator.setDefaults({
			ignore: [],
			highlight: function(element)
			{
				$(element).closest('.row').addClass('has-error');
			},
			unhighlight: function(element)
			{
				$(element).closest('.row').removeClass('has-error');
			},
			errorElement: 'span',
			errorClass: 'help-block',
			errorPlacement: function (error, element)
			{
				var possivelSelect2 = element.nextAll('span .select2:first');
				if(possivelSelect2.length)
				{
					element = possivelSelect2;
				}

				element.after(error);
			}
		});

		app.key_array = function key_array(array, valor)
		{
			var chave;

			$.each(array, function(i , item){
				if(valor == item)
				{
					chave = i;
				}
			});

			return chave;
		};
	});
})(app);