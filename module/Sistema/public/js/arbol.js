define(function(require) {
	var arbol = function() {
		var my = this;
		var self = {};

		// Módulos
		var $ = require('jquery');
		require('jsTree');

		// Propiedades
		my.id;
		my.scope;

		self.tree = {
			'scope': null,
			'instance': null,
		};
		self.formControls = {
			'scope': null,
		}

		my.init = function(contenedor, id) {
			my.id = id;
			my.scope = contenedor;
			self.tree.scope = my.scope.find('.tree');
			self.formControls.scope = my.scope.find('.form-controls');

			self.initTree();
		}

		self.initTree = function() {
			self.tree.instance = $.jstree.create(self.tree.scope, {
				'plugins': ['checkbox', ],
				'checkbox': {
					'tie_selection': true,
				},
				'core': {
					'themes': {
						'name': 'proton',
						'responsive': true
					}
				}
			});
			self.tree.scope.on('changed.jstree', function(e, data) {
				my.scope.find('input[type="hidden"]').each(function() {
					$(this).val(0);
				});

				var seleccionados = data.selected;
				for (var i = 0; i < seleccionados.length; i++) {
					var nodoId = seleccionados[i];
					var nodo = self.tree.instance.get_node(nodoId);
					var value = nodo.li_attr['data-value'];
					self.formControls.scope.find('input[type="hidden"][name="' + value + '"]').val(1);
					checkPadres(nodoId);
				};

				function checkPadres(nodoId) {
					var parentId = self.tree.instance.get_parent(nodoId);
					if (parentId != '#') {
						var parent = self.tree.instance.get_node(parentId);
						var value = parent.li_attr['data-value'];
						self.formControls.scope.find('input[type="hidden"][name="' + value + '"]').val(1);
						checkPadres(parentId);
					};
				}
			});
		}
	}

	return arbol;
});