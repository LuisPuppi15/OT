define(function(require) {
	var tablaMantenedor = function() {
		var my = this;
		var self = {};

		// Módulos
		var $ = require('jquery');
		var ajax = require('ajax');
		var componentes = require('componentes');

		my.id;
		my.scope = {};

		self.tabla = {
			'scope': null,
			'botones': null,
			'selectorItems': null,
			'itemsDefault': null,
			'camposBusqueda': null,
		};
		self.tablaItems = {
			'scope': null,
			'sortable': null,
			'items': null,
		}
		self.paginador = {
			'scope': null,
		}

		my.init = function(contenedor, id) {
			my.id = id;
			my.scope = contenedor;

			self.initPaginador();
			self.initTabla();
			self.initEventListeners();
		}

		self.initParcial = function() {
			self.initItems();
			self.initPaginador();
		}

		self.initPaginador = function() {
			self.paginador.scope = my.scope.find('.pagination');

			self.paginador.scope.find('li > a').on('click', function(e) {
				e.preventDefault();

				if (!$(this).parent().hasClass('disabled')) {
					self.paginador.scope.find('li').removeClass('active');
					$(this).parent().addClass('active');

					var ruta = my.getUrlParametros();
					my.filtrar(ruta);
				};
			});
		};

		self.initTabla = function() {
			self.tabla.scope = my.scope.find('.table');
			self.tabla.camposBusqueda = self.tabla.scope.find('.campos-busqueda input[type="text"]');
			self.tabla.selectorItems = self.tabla.scope.find('select[name="selector-items"]');
			self.tabla.itemsDefault = self.tabla.scope.find('.campos-busqueda').data('items-default');

			self.tabla.selectorItems.selectpicker();

			self.tabla.camposBusqueda.each(function() {
				var timer;
				var prevValue = '';

				$(this).on('keypress', function(E) {
					prevValue = $(this).val();
				});

				$(this).on('input', function(e) {
					clearTimeout(timer);
					var ms = 0;
					var value = $(this).val();

					if (prevValue != '') {
						ms = 300;
					};
					if (value == '') {
						ms = 0;
					};

					timer = setTimeout(function() {
						var pagina = 1;
						var ruta = my.getUrlParametros(pagina);
						my.filtrar(ruta);

						var eventData = {};
						eventData.id = my.id;

						if (value != '') {
							eventData.filtrado = true;
						} else {
							eventData.filtrado = false;
						};

						$('body').trigger('tablaMantenedor:filtrado', [eventData]);
					}, ms);
				});
			});

			self.tabla.selectorItems.on('change', function(e) {
				e.preventDefault();

				var pagina = 1;
				var ruta = my.getUrlParametros(pagina);
				my.filtrar(ruta);
			});

			self.initItems();
		};

		self.initItems = function() {
			self.tablaItems.items = self.tabla.scope.find('.item');
			self.tablaItems.scope = my.scope.find('.tabla-items');
			self.tablaItems.sortable = my.scope.find('.tabla-items tbody');

			var startIndex;
			self.tablaItems.sortable.sortable({
				disabled: true,
				items: '.item',
				start: function(event, ui) {
					startIndex = ui.item.index();
				},
				helper: function(e, tr) {
					var $originals = tr.children();
					var $helper = tr.clone();
					$helper.children().each(function(index) {
						// Set helper cell sizes to match the original sizes
						$(this).width($originals.eq(index).width());
					});
					return $helper;
				},
				update: function(event, ui) {
					var item = ui.item;
					var updateIndex = item.index();

					if (startIndex < updateIndex) {
						var tipo = 'next';
						var friendItem = item.prev();
					} else {
						var tipo = 'prev';
						var friendItem = item.next();
					};

					var id = item.data('id');
					var friendId = friendItem.data('id');

					var parametros = {
						'id': id,
						'friendId': friendId,
						'tipo': tipo,
					};

					var ruta = self.tablaItems.scope.data('ruta-orden');
					ruta = ruta.replace(':parametros', $.param(parametros));

					ajax.ajaxGet(ruta);
				},
			});

			self.tablaItems.items.each(function() {
				$(this).on('click', function(e) {
					var item = $(this);
					var radio = item.find('input[name="selector"]');
					self.tablaItems.items.removeClass('seleccionado');

					var eventData = {};
					eventData.id = my.id;

					if (radio.is(':checked')) {
						radio.prop('checked', false);
						eventData.item = null;
					} else {
						radio.prop('checked', true);
						item.addClass('seleccionado');
						eventData.item = item;
					};

					$('body').trigger('tablaMantenedor:itemSeleccion', [eventData]);
				});
			});
		};

		my.listar = function() {
			self.tabla.camposBusqueda.each(function() {
				$(this).val('');
			});

			var items = self.tabla.selectorItems.val();
			var pagina = 1;
			var campos = {};
			var ruta = my.getUrlParametros(pagina, items, campos);

			my.filtrar(ruta);
		}

		my.filtrar = function(ruta) {
			self.tabla.scope.find('.item').animate({
				opacity: 0.6,
			}, 100);

			setTimeout(function() {
				var data = ajax.ajaxGet(ruta);

				self.tabla.scope.find('.item').remove();
				self.tabla.scope.find('.cabecera').after(data.htmlLista);

				self.paginador.scope.replaceWith(data.htmlPaginador);
				self.initParcial();
			}, 100);

			self.tablaItems.scope.removeClass('tabla-habilitada');

			var eventData = {};
			eventData.id = my.id;
			eventData.item = null;
			$('body').trigger('tablaMantenedor:itemSeleccion', [eventData]);
		};

		my.getUrlParametros = function(pagina, items, campos, id) {
			var rutaInicial = my.scope.data('ruta');

			var parametros = {
				'filtro': 'default',
			}

			if (pagina) {
				parametros.pagina = pagina;
			} else {
				parametros.pagina = self.paginador.scope.find('li.active > a').attr('data-pagina');
			};

			if (items) {
				parametros.items = items;
			} else {
				if (self.tabla.selectorItems.length > 0) {
					parametros.items = self.tabla.selectorItems.val();

				} else {
					parametros.items = self.tabla.itemsDefault;
				};
			};

			if (campos) {
				parametros.campos = campos;
			} else {
				parametros.campos = {};

				self.tabla.camposBusqueda.each(function() {
					var campo = $(this).attr('name');
					var valor = $(this).val();
					parametros.campos[campo] = valor;
				});
			};

			if (id) {
				parametros.id = id;
			};

			if (my.scope.data('filtro')) {
				parametros.filtro = my.scope.data('filtro');
			};

			var cadena = rutaInicial.replace(':parametros', $.param(parametros));
			return cadena;
		}

		my.ordenar = function(estado) {
			self.limpiarSeleccion();

			if (estado) {
				self.tablaItems.sortable.sortable('enable');
				self.tablaItems.scope.addClass('tabla-habilitada');
			} else {
				self.tablaItems.sortable.sortable('disable');
				self.tablaItems.scope.removeClass('tabla-habilitada');
			};
		}

		self.limpiarSeleccion = function() {
			self.tablaItems.items.find('.my-radio input[type="radio"]').prop('checked', false);

			var eventData = {};
			eventData.id = my.id;
			eventData.item = null;
			$('body').trigger('tablaMantenedor:itemSeleccion', [eventData]);
		}

		my.clickItem = function(id) {
			setTimeout(function() {
				self.tabla.scope.find('.item[data-id="' + id + '"]').trigger('click');
			}, 200);
		}

		self.initEventListeners = function() {
			$('body').on('tabla-mantenedor:listar', function() {
				my.listar();
			});
		}
	}

	return tablaMantenedor;
});