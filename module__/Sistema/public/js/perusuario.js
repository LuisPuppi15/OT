define(function(require) {
	var perusuario = function() {
		var my = this;
		var self = {};

		// Módulos
		var $ = require('jquery');
		var ajax = require('ajax');
		var componentes = require('componentes');

		my.id;
		my.scope;

		self.cmpModal;
		self.dialogPersona = {
			'scope': null,
		};
		self.dialogAlerta = {
			'scope': null,
		};
		self.persona = {
			'scope': null,
			'acciones': null,
		};
		self.formulario = {
			'scope': null,
			'selectPersonas': null,
		}
		self.itemSeleccionado;

		my.init = function(contenedor, id) {
			my.id = id;
			my.scope = contenedor;
			self.persona.scope = my.scope.find('.marco-personas');
			self.dialogAlerta.scope = my.scope.find('.dialog.alerta');
			self.formulario.scope = my.scope.find('form');
			self.formulario.selectPersonas = self.formulario.scope.find('[name="persona"]');
			self.formulario.selectPersonas.parents('.form-group:first').css({
				'display': 'none'
			});
			self.itemSeleccionado = null;

			self.initEventListeners();
			self.initComponentes();
			self.initFormulario();
			self.initAcciones();
			self.initModal();
		}

		self.initComponentes = function() {
			self.cmpTablaMantenedor = componentes.getComponenteHijo(my.id, 'tabla-mantenedor');
			self.cmpFormulario = componentes.getComponenteHijo(my.id, 'formulario');
		}

		self.initAcciones = function() {
			self.persona.acciones = self.persona.scope.find('.btn-accion');

			self.persona.acciones.each(function() {
				$(this).on('click', function() {
					var accion = $(this).attr('data-accion');
					self[accion]();
				});
			});
		}

		self.initEventListeners = function() {
			$('body').on('tablaMantenedor:itemSeleccion', function(e, data) {
				var cmpHijo = componentes.getComponenteHijo(my.id, 'tabla-mantenedor');
				if (cmpHijo) {
					if (cmpHijo.id == data.id) {
						self.itemSeleccionado = data.item;

						if (self.itemSeleccionado) {
							var id = self.itemSeleccionado.data('id');
							self.formulario.selectPersonas.val(id);
						} else {
							self.formulario.selectPersonas.prop('selectedIndex', -1);
						};

						self.formulario.selectPersonas.selectpicker('refresh');
					}
				};
			});

			$('body').on('modal:success', function(e, data) {
				var cmpHijo = componentes.getComponenteHijo(my.id, 'modal-post');
				if (cmpHijo) {
					if (cmpHijo.id == data.id) {
						self.dialogPersona.scope.dialog('close');

						var pagina = 1;
						var items = null;

						var keyId = data.keyId;
						var valueId = data.valueId;
						var cadena = data.cadena;
						var campos = {};
						campos[keyId] = valueId;

						var ruta = self.cmpTablaMantenedor.getUrlParametros(pagina, items, campos);
						self.cmpTablaMantenedor.filtrar(ruta);
						self.cmpTablaMantenedor.clickItem(valueId);

						setTimeout(function() {
							self.itemSeleccionado = self.persona.scope.find('tr[data-id="' + valueId + '"]');
						}, 200);

						$option = $('<option value="' + valueId + '">' + cadena + '</option>');
						$option.prop('selected', 'selected');
						self.formulario.selectPersonas.append($option);

						self.formulario.selectPersonas.selectpicker('refresh');
					}
				};
			});
		}

		self.initFormulario = function() {
			self.formulario.scope.find(':submit').on('click', function(e) {
				if (self.cmpTablaMantenedor) {
					if (!self.itemSeleccionado) {
						e.preventDefault();

						var mensaje = 'Seleccione un elemento';
						self.dialogAlerta.scope.find('.mensaje').html(mensaje);
						self.dialogAlerta.scope.dialog('open');
					};
				};
			});
		}

		self.initModal = function() {
			self.dialogPersona.scope = my.scope.find('.dialog.persona');
		};

		self.agregar = function() {
			var boton = self.persona.acciones.parent().find('[data-accion="agregar"]');
			var ruta = boton.data('ruta');
			var data = ajax.ajaxGet(ruta);

			componentes.htmlLoad(data, self.dialogPersona.scope);
			self.cmpModal = componentes.getComponenteHijo(my.id, 'modal-post');

			var selector = '.componente[data-componente="modal"]';
			var size = self.dialogPersona.scope.find(selector).data('modal-size');
			self.dialogPersona.scope.find('.modal-dialog').removeClass('modal-lg modal-sm');
			self.dialogPersona.scope.find('.modal-dialog').addClass(size);

			self.dialogPersona.scope.dialog('open');
		}
	}

	return perusuario;
});