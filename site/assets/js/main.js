/**
 *  main.js
 *
 *@author Rafael Vinicius Barros Ferreira
 */

var app = { API: '/api' };

(function(app, document, $, toastr, BootstrapDialog)
	{
	'use strict';

	$(document ).ready(function()
	{
		// Opções para mensagens
		toastr.options = {
		  "closeButton": false,
		  "debug": false,
		  "newestOnTop": true,
		  "progressBar": false,
		  "positionClass": "toast-top-right",
		  "preventDuplicates": false,
		  "onclick": null,
		  "showDuration": "300",
		  "hideDuration": "1000",
		  "timeOut": "2000",
		  "extendedTimeOut": "1000",
		  "showEasing": "swing",
		  "hideEasing": "linear",
		  "showMethod": "fadeIn",
		  "hideMethod": "fadeOut"
		};

		// Opções para diálogos
		BootstrapDialog.DEFAULT_TEXTS[ BootstrapDialog.TYPE_DEFAULT ] = 'Informação';
        BootstrapDialog.DEFAULT_TEXTS[ BootstrapDialog.TYPE_INFO ] = 'Informação';
        BootstrapDialog.DEFAULT_TEXTS[ BootstrapDialog.TYPE_PRIMARY ] = 'Informação';
        BootstrapDialog.DEFAULT_TEXTS[ BootstrapDialog.TYPE_SUCCESS ] = 'Sucesso';
        BootstrapDialog.DEFAULT_TEXTS[ BootstrapDialog.TYPE_WARNING ] = 'Aviso';
        BootstrapDialog.DEFAULT_TEXTS[ BootstrapDialog.TYPE_DANGER ] = 'Erro';
        BootstrapDialog.DEFAULT_TEXTS[ 'OK' ] = 'OK';
        BootstrapDialog.DEFAULT_TEXTS[ 'CANCEL' ] = 'Cancelar';
        BootstrapDialog.DEFAULT_TEXTS[ 'CONFIRM' ] = 'Confirmação';

	} );

} )(app, document, jQuery, toastr, BootstrapDialog );