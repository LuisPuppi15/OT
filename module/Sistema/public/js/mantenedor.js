define(function(require) {
	var mantenedor = function() {
		var my = this;
		var self = {};

		// Módulos
		var $ = require('jquery');
		var ajax = require('ajax');
		var componentes = require('componentes');
		require('jqueryUi');

		my.scope;

		my.id;
		self.cmpElmMantenedor;
		self.cmpModalPost;
		self.cmpModalPre;
		self.itemSeleccionado;
		self.filtrado = false;

		self.botonera = {
			'scope': null,
			'botones': null,
		};
		self.dialogoModalPost = {
			'scope': null,
		};
		self.dialogoModalPre = {
			'scope': null,
		};

		my.init = function(contenedor, id) {
			my.id = id;
			my.scope = contenedor;
			self.dialogoModalPre.scope = my.scope.find('.dialog.pre');

			self.cssContenido();
			self.initComponentes();
			self.initEventListeners();
			self.initBotonera();
			self.initModal();
		}

		self.cssContenido = function() {
			setTimeout(function() {
				var altVentana = $(document).height();
				var marcoData = my.scope.find('.marco-data')
				var offset = marcoData.offset();
				var padding = my.scope.css('padding-bottom');
				var altura = altVentana - offset.top - padding.replace('px', '');

				my.scope.find('.marco-data').css('min-height', altura);
			}, 10);
		}

		self.initComponentes = function() {
			var elmMantenedor = my.scope.find('.componente[data-componente$="-mantenedor"]');
			var tipo = elmMantenedor.data('componente');
			self.cmpElmMantenedor = componentes.getComponenteHijo(my.id, tipo);

			self.cmpModalPre = componentes.getComponenteHijo(my.id, 'modal-pre');
			self.cmpModalPost = componentes.getComponenteHijo(my.id, 'modal-post');
		}

		self.initEventListeners = function() {
			$('body').on('tablaMantenedor:itemSeleccion', function(e, data) {
				var cmpHijo = componentes.getComponenteHijo(my.id, 'tabla-mantenedor');
				if (cmpHijo) {
					if (cmpHijo.id == data.id) {
						self.itemSeleccionado = data.item;
					}
				};
			});

			$('body').on('listaMantenedor:itemSeleccion', function(e, data) {
				var cmpHijo = componentes.getComponenteHijo(my.id, 'lista-mantenedor');
				if (cmpHijo) {
					if (cmpHijo.id == data.id) {
						self.itemSeleccionado = data.item;
					}
				};
			});

			$('body').on('tablaMantenedor:filtrado', function(e, data) {
				var cmpHijo = componentes.getComponenteHijo(my.id, 'tabla-mantenedor');
				if (cmpHijo) {
					if (cmpHijo.id == data.id) {
						self.filtrado = data.filtrado;
						self.itemSeleccionado = null;
						self.botonera.botones.removeClass('activo');
					}
				};
			});

			$('body').on('listaMantenedor:filtrado', function(e, data) {
				var cmpHijo = componentes.getComponenteHijo(my.id, 'lista-mantenedor');
				if (cmpHijo) {
					if (cmpHijo.id == data.id) {
						self.filtrado = data.filtrado;
						self.itemSeleccionado = null;
						self.botonera.botones.removeClass('activo');
					}
				};
			});

			$('body').on('modal:success', function(e, data) {
				var cmpHijo = componentes.getComponenteHijo(my.id, 'modal-post');
				if (cmpHijo) {
					if (cmpHijo.id == data.id) {
						self.dialogoModalPost.scope.dialog('close');
						self.cmpElmMantenedor.listar();
					}
				};
			});
		}

		self.initBotonera = function() {
			self.botonera.scope = my.scope.find('.botonera');
			self.botonera.botones = self.botonera.scope.find('button');

			self.botonera.botones.each(function() {
				$(this).on('click', function() {
					var $item = self.itemSeleccionado;
					var $boton = $(this);
					$boton.blur();

					var accion = $boton.data('accion');

					switch (accion) {
						case 'listar':
							self.cmpElmMantenedor.listar();
							self.botonera.botones.removeClass('activo');
							break;

						case 'agregar':
							var ruta = $boton.data('ruta');
							self.cargarModal(ruta);
							break;

						case 'ordenar':
							if (self.filtrado) {
								var mensaje = 'Limpie la búsqueda para poder ordenar los elementos';
								self.mostrarAlerta(mensaje);
							} else {
								var estado = false;

								if ($boton.hasClass('activo')) {
									$boton.removeClass('activo');
								} else {
									$boton.addClass('activo');
									estado = true;
								};

								self.cmpElmMantenedor.ordenar(estado);
							};
							break;

						default:
							if ($item) {
								var id = $item.data('id');
								var ruta = $boton.data('ruta').replace(':parametros', id);
								self.cargarModal(ruta);
							} else {
								var mensaje = 'Seleccione un elemento';
								self.mostrarAlerta(mensaje);
							};
							break;
					}
				});
			});
		};

		self.initModal = function() {
			self.dialogoModalPost.scope = my.scope.find('.dialog.post');

			self.dialogoModalPost.scope.on('submit', function(e) {
				if (self.cmpModalPost.success) {
					self.dialogoModalPost.scope.dialog('close');
					self.cmpElmMantenedor.listar();
				};
			});
		};

		self.cargarModal = function(ruta) {
			var data = ajax.ajaxGet(ruta);

			componentes.htmlLoad(data, self.dialogoModalPost.scope);
			self.cmpModalPost = componentes.getComponenteHijo(my.id, 'modal-post');

			self.dialogoModalPost.scope.dialog('open');
		}

		self.mostrarAlerta = function(mensaje) {
			self.dialogoModalPre.scope.find('.mensaje').html(mensaje);
			self.dialogoModalPre.scope.dialog('open');
		}
	}

	return mantenedor;
});