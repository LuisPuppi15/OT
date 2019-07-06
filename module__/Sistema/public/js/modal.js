define(function(require) {
	var modal = function() {
		var my = this;
		var self = {};

		// Módulos
		var $ = require('jquery');
		var componentes = require('componentes');

		my.id;
		my.scope;
		my.parentScope;

		self.cmpFormulario;
		self.btnCerrar;
		self.formularios;

		my.init = function(contenedor, id) {
			my.id = id;
			my.scope = contenedor;
			my.parentScope = contenedor.parents('.dialog:first');

			self.btnCerrar = my.scope.find('.btn-modal.aceptar, .btn-modal.cancelar').filter(function() {
				$a = $(this).parents('.dialog:first');
				$b = my.scope.parents('.dialog:first');

				return $a.is($b);
			});
			self.formularios = my.scope.find('form');

			my.parentScope.dialog({
				autoOpen: false,
				title: my.scope.data('modal-title'),
				width: 500,
				modal: true,
				resizable: false,
				show: {
					effect: "fade",
					duration: 150,
				},
				create: function(event, ui) {
					var btnClose = '<span class="btn-close" aria-hidden="true">×</span>';
					var widget = $(this).dialog('widget');
					widget.find('.ui-dialog-titlebar-close').prepend(btnClose);
				},
				close: function(event, ui) {
					componentes.limpiarComponentes($(this));

					if (!$(this).hasClass('pre')) {
						$(this).dialog('destroy');
						$(this).empty();
					};
				}
			});

			var width = my.scope.data('modal-width');
			if (width) {
				my.parentScope.dialog('option', 'width', width);
			};

			var height = my.scope.data('modal-height');
			if (height) {
				my.parentScope.dialog('option', 'height', height);
			};

			self.initComponentes();
			self.initEventListeners();
			self.initBtnCerrar();
		}

		self.initComponentes = function() {
			self.cmpFormulario = componentes.getComponenteHijo(my.id, 'formulario');
		}

		self.initEventListeners = function() {
			$('body').on('formulario:redimensionado', function(e, data) {
				var cmpHijo = componentes.getComponenteHijo(my.id, 'formulario');
				if (cmpHijo) {
					if (cmpHijo.id == data.id) {
						my.parentScope.dialog('option', 'position', 'center');
					};
				};
			});

			$('body').on('formulario:success', function(e, data) {
				var cmpHijo = componentes.getComponenteHijo(my.id, 'formulario');
				if (cmpHijo) {
					if (cmpHijo.id == data.id) {
						var eventData = {};
						eventData.id = my.id;
						eventData.keyId = data.keyId;
						eventData.valueId = data.valueId;
						eventData.cadena = data.cadena;

						$('body').trigger('modal:success', [eventData]);
					}
				};
			});
		}

		self.initBtnCerrar = function() {
			self.btnCerrar.on('click', function(e) {
				e.preventDefault();
				my.parentScope.dialog('close');
			});
		}
	}

	return modal;
});