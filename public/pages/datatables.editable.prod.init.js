/**
* Theme: Ubold Admin Template
* Author: Coderthemes
* Component: Editable
* 
*/

(function( $ ) {

	'use strict';

	var EditableTable = {

		options: {
			addButton: '#addToTable',
			table: '#datatable-editable'
		},

		initialize: function() {
			this
				.setVars()
				.build()
				.events();
		},

		setVars: function() {
			this.$table				= $( this.options.table );
			this.$addButton			= $( this.options.addButton );

			return this;
		},

		build: function() {
			this.datatable = this.$table.DataTable({
				"columns": [
					{ },
					{},
					{ },
					{ },
					{ "orderable": false },
				],
				"order": [ [ 0, 'asc' ] ]
			});

			window.dt = this.datatable;

			return this;
		},

		events: function() {
			var _self = this;

			this.$table
				.on('click', 'a.save-row', function( e ) {
					e.preventDefault();

					_self.rowSave( $(this).closest( 'tr' ) );
				})
				.on('click', 'a.cancel-row', function( e ) {
					e.preventDefault();

					_self.rowCancel( $(this).closest( 'tr' ) );
				})
				.on('click', 'a.edit-row', function( e ) {
					e.preventDefault();

					_self.rowEdit( $(this).closest( 'tr' ) );
				})
				.on( 'click', 'a.remove-row', function( e ) {
					e.preventDefault();

					var $row = $(this).closest('tr');
					var $modal = $('#confirm-delete');

					$modal.modal('show');

					$modal.on('click', '.btn-ok', function(e) {
						_self.rowRemove( $row );
						$modal.modal('hide');
					});
				});

			this.$addButton.on( 'click', function(e) {
				e.preventDefault();

				_self.rowAdd();
			});

			return this;
		},

		// ==========================================================================================
		// ROW FUNCTIONS
		// ==========================================================================================
		rowAdd: function() {
			this.$addButton.attr({ 'disabled': 'disabled' });

			var actions,
				data,
				$row;

			actions = [
				'<a href="" class="hidden on-editing save-row"><i class="fa fa-save"></i></a>',
				'<a href="" class="hidden on-editing cancel-row"><i class="fa fa-times"></i></a>',
				'<a href="" class="on-default edit-row"><i class="fa fa-pencil"></i></a>',
				// '<a href="" class="on-default remove-row"><i class="fa fa-trash-o"></i></a>',
				'<input name="row_id" type="hidden">'
			].join(' ');

			data = this.datatable.row.add([ '', '', '', '', actions ]);
			$row = this.datatable.row( data[0] ).nodes().to$();

			$row
				.addClass( 'adding' )
				.find( 'td:last' )
				.addClass( 'actions text-right' );

			this.rowEdit( $row );

			this.datatable.order([0,'asc']).draw(); // always show fields
		},

		rowCancel: function( $row ) {
			var _self = this,
				$actions,
				i,
				data;

			if ( $row.hasClass('adding') ) {
				if ( $row.hasClass('adding') ) {
					this.$addButton.removeAttr( 'disabled' );
				}
				this.datatable.row( $row.get(0) ).remove().draw();
			} else {

				data = this.datatable.row( $row.get(0) ).data();
				this.datatable.row( $row.get(0) ).data( data );

				$actions = $row.find('td.actions');
				if ( $actions.get(0) ) {
					this.rowSetActionsDefault( $row );
				}

				this.datatable.draw();
			}
		},

		rowEdit: function( $row ) {
			var _self = this,
				data;

			data = this.datatable.row( $row.get(0) ).data();

			$row.children( 'td' ).each(function( i ) {
				var $this = $( this );
				if ($this.hasClass('actions')) {
					_self.rowSetActionsEditing($row);
				} else {
					var id;
					if (i == 0) {
						id = 'name';
					} else if (i == 1) {
						id = 'price';
					} else if (i == 2) {
						id = 'price2';
					} else {
						id = 'unit';
					}
					$this.html( '<input type="text" id="' + id + '" class="form-control input-block" value="' + data[i] + '"/>' );
				}
			});
		},

		rowSave: function ($row) {

			var _self     = this,
				$actions,
				values = [];

			if ( $row.hasClass( 'adding' ) ) {
				this.$addButton.removeAttr( 'disabled' );
				$row.removeClass( 'adding' );
			}

			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
				}
			})

			var row_id = ($row.find('input[name="row_id"]').val()) ? $row.find('input[name="row_id"]').val() : '';

			var formData = {
				id: row_id,
				name: $('#name').val(),
				price: $('#price').val(),
				price2: $('#price2').val(),
				unit: $('#unit').val()
			}

			//used to determine the http verb to use [add=POST], [update=PUT]
			var type = 'POST'; //for creating new resource
			var url = '/products';

			if ( row_id ) {
				type = 'PUT'; //for updating existing resource
				url += '/' + row_id;
			}

			$.ajax({
				type: type,
				url: url,
				data: formData,
				dataType: 'json',
				success: function (data) {

					values = $row.find('td').map(function() {
						var $this = $(this);

						if ( $this.hasClass('actions') ) {
							_self.rowSetActionsDefault( $row );
							return _self.datatable.cell( this ).data();
						} else {
							return $.trim( $this.find('input').val() );
						}
					});

					_self.datatable.row( $row.get(0) ).data( values );
					
					$actions = $row.find('td.actions');
					if ($actions.get(0)) {
						if ( data.record_id ) {
							_self.rowSetActionsDefault( $row, data.record_id );
						} else {
							_self.rowSetActionsDefault( $row );
						}
					}

					_self.datatable.draw();

					$.Notification.notify(
						'success',
						'top right',
						'儲存成功'
					);
				},
				error: function (data) {
					var response = data.responseJSON;
					var error_msg = [];

					$.each( response.errors, function( key, value ) {
						error_msg += value + '<br>';
					});
					$.Notification.notify(
						'error',
						'top right',
						'提醒',
						error_msg
					);
				}
			});
		},

		rowRemove: function ($row) {

			var _self = this;
			
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
				}
			})

			var row_id = $row.find('input[name="row_id"]').val();

			var type = 'DELETE'; //for deleting the resource
			var url = '/products' + '/' + row_id;

			$.ajax({
				type: type,
				url: url,
				data: { id: row_id },
				dataType: 'json',
				success: function (data) {

					if ( $row.hasClass('adding') ) {
						_self.$addButton.removeAttr( 'disabled' );
					}

					_self.datatable.row( $row.get(0) ).remove().draw();

					$.Notification.notify(
						'success',
						'top right',
						'刪除成功'
					);
				},
				error: function (data) {
					var response = data.responseJSON;

					$.Notification.notify(
						'error',
						'top right',
						'提醒',
						response
					);
				}
			});
			
		},

		rowSetActionsEditing: function ($row) {
			$row.find( '.on-editing' ).removeClass( 'hidden' );
			$row.find( '.on-default' ).addClass( 'hidden' );
		},

		rowSetActionsDefault: function ($row, id = null) {
			if ( id ) {
				$row.find( 'input[name="row_id"]' ).val( id );
			}
			$row.find( '.on-editing' ).addClass( 'hidden' );
			$row.find( '.on-default' ).removeClass( 'hidden' );
		}

	};

	$(function() {
		EditableTable.initialize();
	});

}).apply( this, [ jQuery ]);