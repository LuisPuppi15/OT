require.config({
	baseUrl: document.getElementById('base-path').innerHTML + '/',
	waitSeconds: 20,
	shim: {
		bootstrap: {
			deps: ['jquery'],
		},
		bsDatepicker: {
			deps: ['jquery', 'bootstrap'],
		},
		bsSelect: {
			deps: ['jquery', 'bootstrap'],
		},
		bsValidator: {
			deps: ['jquery'],
		},
		bsValLanguage: {
			deps: ['jquery', 'bsValidator'],
		},
		bsFileinput: {
			deps: ['jquery', 'bootstrap'],
		},
		jqueryUi: {
			deps: ['jquery'],
		},
		jqueryBridget: {
			deps: ['jquery'],
		},
		jsTree: {
			deps: ['jquery'],
		},
		ckJqAdapter: {
			deps: ['ckeditor', 'jquery'],
		},
	},
	paths: {
		jquery: 'lib/jquery/jquery-2.1.1.min',
		jqueryUi: 'lib/jquery-ui/jquery-ui-1.9.2.min',
		jqueryBridget: 'lib/jquery-bridget/jquery.bridget',
		bootstrap: 'lib/bootstrap/js/bootstrap.min',
		bsDatepicker: 'lib/bootstrap-datepicker/js/bootstrap-datepicker',
		bsSelect: 'lib/bootstrap-select/js/bootstrap-select.min',
		bsValidator: 'lib/bootstrap-validator/dist/js/bootstrapValidator',
		bsValLanguage: 'lib/bootstrap-validator/dist/js/language/es_ES',
		bsFileinput: 'lib/bootstrap-fileinput/js/fileinput_es',
		jsTree: 'lib/js-tree/jstree',
		ckeditor: 'ckeditor/ckeditor',
		ckfinder: 'ckfinder/ckfinder',
		ckJqAdapter: 'ckeditor/adapters/jquery',
		portada: 'js/portada',
		mantenedor: 'js/mantenedor',
		'tabla-mantenedor': 'js/tablaMantenedor',
		'lista-mantenedor': 'js/listaMantenedor',
		modal: 'js/modal',
		ajax: 'js/ajax',
		formulario: 'js/formulario',
		helpers: 'js/helpers',
		perusuario: 'js/perusuario',
		arbol: 'js/arbol',
		'perusuario-accesos': 'js/perusuarioAccesos',
		componentes: 'js/componentes',
	},
});

require(['jquery', 'jqueryUi', 'jqueryBridget', 'bootstrap', 'bsDatepicker', 'bsSelect', 'bsValidator', 'bsValLanguage', 'bsFileinput', 'jsTree', 'ckeditor', 'ckfinder', 'ckJqAdapter', 'portada', 'mantenedor', 'tabla-mantenedor', 'lista-mantenedor', 'modal', 'ajax', 'formulario', 'helpers', 'perusuario', 'arbol', 'perusuario-accesos', 'componentes'], function() {
	$(function() {
		// Muestro la página solo cuando se hayan cargado
		$html = $('html');
		$html.css('visibility', 'visible');
		$html.animate({
			'opacity': 1,
		}, 150);

		// Cargo el componente portada
		var componentes = require('componentes');
		$portada = $html.find('.componente[data-componente="portada"]');
		if ($portada.length == 1) {
			componentes.cargarComponentes($portada);
		};

		// Jquery UI- Tooltip
		$('body').tooltip({
			'items': '.mensaje-ayuda',
		});
	});
});