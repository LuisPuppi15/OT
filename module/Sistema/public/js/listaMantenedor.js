define(function(require) {
	var listaMantenedor = function() {
		var my = this;
		var self = {};

		// Módulos
		var $ = require('jquery');
		var ajax = require('ajax');
		var componentes = require('componentes');

		my.id;
		my.scope = {};
		self.eventData = {
			'myId': null,
			'itemSeleccionado': null,
		};

		self.tabla = {
			'scope': null,
			'selectorItems': null,
			'camposBusqueda': null,
		};
		self.listaItems = {
			'scope': null,
			'items': null,
		}
		self.paginador = {
			'scope': null,
		}

		my.init = function(contenedor, id) {
			my.id = id;
			self.eventData.myId = id;
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
			self.tabla.camposBusqueda = self.tabla.scope.find('.opciones input[type="text"]');
			self.tabla.selectorItems = self.tabla.scope.find('select[name="selector-items"]');

			self.tabla.selectorItems.selectpicker();

			self.tabla.camposBusqueda.each(function() {
				var timer;
				var prevValue = '';

				$(this).on('keypress', function(E) {
					prevValue = $(this).val();
				});

				$(this).on('input', function(e) {
					self.listaItems.scope.sortable('disable');

					clearTimeout(timer);
					var ms = 0;
					var value = $(this).val();

					if (prevValue != '') {
						ms = 300;
					};
					if (value == '') {
						ms = 0;
					}

					timer = setTimeout(function() {
						var pagina = 1;
						var ruta = my.getUrlParametros(pagina);
						my.filtrar(ruta);

						var data = {};
						data.id = my.id;

						if (value != '') {
							data.filtrado = true;
						} else {
							data.filtrado = false;
						};

						$('body').trigger('listaMantenedor:filtrado', [data]);
					}, ms);
				});
			});

			self.tabla.selectorItems.on('change', function(e) {
				e.preventDefault();

				var pagina = 1;
				var ruta = my.getUrlParametros(pagina);
				my.filtrar(ruta);

				$('body').trigger('mantenedor:limpiarAccionesPrevias');
			});

			self.initItems();
		};

		self.initItems = function() {
			self.listaItems.items = self.tabla.scope.find('.item');
			self.listaItems.scope = my.scope.find('.lista-items');

			var startIndex;
			self.listaItems.scope.sortable({
				disabled: true,
				start: function(event, ui) {
					startIndex = ui.item.index();
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

					var ruta = self.listaItems.scope.data('ruta-orden');
					ruta = ruta.replace(':parametros', $.param(parametros));

					ajax.ajaxGet(ruta);
				},
			});

			self.listaItems.items.each(function() {
				$(this).find('.my-radio').on('click', function(e) {
					var item = $(this).parents('.item:first');
					var radio = item.find('input[name="selector"]');
					self.listaItems.items.removeClass('seleccionado');

					var data = {};
					data.id = my.id;

					if (radio.is(':checked')) {
						radio.prop('checked', false);
						data.item = null;
					} else {
						radio.prop('checked', true);
						item.addClass('seleccionado');
						data.item = item;
					};

					$('body').trigger('listaMantenedor:itemSeleccion', data);

					return false;
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

			var eventData = {};
			eventData.id = my.id;
			eventData.filtrado = false;
			$('body').trigger('listaMantenedor:filtrado', [eventData]);
		}

		my.filtrar = function(ruta) {
			self.tabla.scope.find('.item').animate({
				opacity: 0.6,
			}, 100);

			setTimeout(function() {
				var data = ajax.ajaxGet(ruta);

				self.tabla.scope.find('.item').remove();
				self.tabla.scope.find('.items td').html(data.htmlLista);
				self.eventData.itemSeleccionado = null;

				self.paginador.scope.replaceWith(data.htmlPaginador);
				self.initParcial();
			}, 100);

			var eventData = {};
			eventData.id = my.id;
			eventData.item = null;
			$('body').trigger('listaMantenedor:itemSeleccion', [eventData]);
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

				} else {};
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
			if (estado) {
				self.listaItems.scope.sortable('enable');
				self.listaItems.scope.addClass('lista-habilitada');
			} else {
				self.listaItems.scope.sortable('disable');
				self.listaItems.scope.removeClass('lista-habilitada');
			};
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

	return listaMantenedor;
});