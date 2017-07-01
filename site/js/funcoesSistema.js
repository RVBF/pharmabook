(function(window ,app, document, $) {
	'use strict';
	$(document).ready(function()
	{
		window.converterEmFloat = function converterEmFloat(moeda)
		{
			moeda = moeda.replace(".","");

			moeda = moeda.replace(",",".");

			return parseFloat(moeda);
		};

		window.converterEmMoeda = function converterEmMoeda(numero, casasDecimais)
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

		window.desabilitarFormulario = function desabilitarFormulario(status = true)
		{
			$('form input,select,textarea,checkbox').each(function(){
				$(this).prop('disabled', status);
			});
		};

		window.desabilitarBotoesDeFormulario = function desabilitarBotoesDeFormulario(status = true)
		{
			$('button').each(function(){
				$(this).prop('disabled', status);
			});
		};

		window.retornarInteiroEmStrings = function retornarInteiroEmStrings(string)
		{
			var numero = string.replace(/[^0-9]/g,'');
			return parseInt(numero);
		};

		window.definirMascarasPadroes = function definirMascarasPadroes()
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
				var possivelInputaAddon = element.parent('div .input-group').nextAll('div .menu_input_addon_erro:first');
				if(possivelSelect2.length)
				{
					element = possivelSelect2;
				}
				else
				{
					if(possivelInputaAddon.length)
					{
						element = possivelInputaAddon;
					}
				}

				element.after(error);
			}
		});

		// Opções padrão para o DataTables ----------------------------------------
		app.dtOptions = {
			language	: { url: 'vendor/datatables-i18n/i18n/pt-BR.json' },
			bFilter : true,
			serverSide: true,
			processing : true,
			searching : true,
			responsive : true,
			autoWidth : false,
			order: [[1, 'asc']]
		};

		window.key_array = function key_array(array, valor)
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

		var bodyEvento = {target: 'body'};
		iniciarFuncoesPadroesSistema(bodyEvento);

		$('body').on('DOMNodeInserted', '.panel',function(evento)
		{
			iniciarFuncoesPadroesSistema(evento);
		});

		$('.inicio').on('click', function()
		{
			router.navigate( '/');
		});		

		$('.medicamentos').on('click', function()
		{
			router.navigate( '/medicamentos-precificados/');
		});

		$('.registrar_compra').on('click', function()
		{
			router.navigate( '/medicamentos-pessoais/cadastrar/');
		});

		$('.estoque').on('click', function()
		{
			router.navigate( '/medicamentos-pessoais');
		});
	});

	function iniciarFuncoesPadroesSistema(event)
	{
		var evento = event;
		if(typeof(evento) != 'undefined')
		{
			$(evento.target).find('.estabelecimento_google').each(function(i)
			{
				var autoCompleteEstabelecimentos =  new iniciarAutoCompleteEstabelecimentos($(evento.target).find('.estabelecimento_google')[i]);
			});			

			$(evento.target).find('.cidade_google').each(function(i)
			{
				var autoCompleteCidades =  new iniciarAutoCompleteCidades($(evento.target).find('.cidade_google')[i]);
			});

			$(evento.target).find('.regions_google').each(function(i)
			{
				var autoCompleteEstabelecimentos =  new iniciarPesquisaRegioes($(evento.target).find('.regions_google')[i]);
			});
		}
	}

	function iniciarAutoCompleteEstabelecimentos(elemento)
	{
		var _this = this;
		_this.cordernadasPadroes = new google.maps.LatLngBounds(new google.maps.LatLng(-33.8902, 151.1759), new google.maps.LatLng(-33.8474, 151.2631));
		var campoLogradouro = elemento;
		var opcoesAutoComplete = {
			bounds: _this.cordernadasPadroes,
			types: ['establishment'],
			componentRestrictions: {country: 'BR'}
		};

		var autocomplete = new google.maps.places.Autocomplete(campoLogradouro, opcoesAutoComplete);
		getLocalizacaoAtual(autocomplete);
	};

	function iniciarAutoCompleteCidades(elemento)
	{
		var _this = this;
		_this.cordernadasPadroes = new google.maps.LatLngBounds(new google.maps.LatLng(-33.8902, 151.1759), new google.maps.LatLng(-33.8474, 151.2631));
		var campo = elemento;
		var opcoesAutoComplete = {
			bounds: _this.cordernadasPadroes,
			types: ['(cities)'],
			componentRestrictions: {country: 'BR'}
		};

		var autocomplete = new google.maps.places.Autocomplete(campo, opcoesAutoComplete);
		getLocalizacaoAtual(autocomplete);
	};

	function iniciarPesquisaRegioes(elemento)
	{
		var _this = this;
		_this.cordernadasPadroes = new google.maps.LatLngBounds(new google.maps.LatLng(-33.8902, 151.1759), new google.maps.LatLng(-33.8474, 151.2631));
		var campo = elemento;
		var autoCompleteOpcoes = {
			bounds: _this.cordernadasPadroes,
			types: ['address'],
			componentRestrictions: {country: 'BR'}
		}
		var autocomplete = new google.maps.places.Autocomplete(campo,autoCompleteOpcoes);
		getLocalizacaoAtual(autocomplete);

		return autocomplete;
	};

	function getLocalizacaoAtual(autoCompleteGoogle = '')
	{
		var localizacao;
		if (navigator.geolocation) 
		{
			navigator.geolocation.getCurrentPosition(function(position) 
			{
				localizacao = position;
				var geolocation = {
					lat: position.coords.latitude,
					lng: position.coords.longitude
				};
				var circle = new google.maps.Circle({
					center: geolocation,
					radius: position.coords.accuracy
				});
				autoCompleteGoogle.setBounds(circle.getBounds());
			});
		}

		return localizacao;
	}
})(window , app, document, jQuery);