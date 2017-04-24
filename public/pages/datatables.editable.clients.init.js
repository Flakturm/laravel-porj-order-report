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
				processing: true,
				serverSide: true,
				ajax:{
					url : APP_URL + "clients", // json datasource
					error: function(){  // error handling code
						console.log('something is wrong');    
					}
				},
				columns: [
					{ title: 'Type', data: 'route' },
                    { title: 'Number', data: 'route_number' },
					{ title: 'Client', data: 'name' },
					{ title: 'Size', data: 'is_small', name: 'is_small', className: 'client-size', render: function(data,type,row,meta){
						return data == 1 ? 'Small' : 'Regular';
					} },
					{ title: 'Invoiced', data: 'invoiced_daily', name: 'invoiced_daily', className: 'text-right client-invoiced', render: function(data,type,row,meta){
						return data == 1 ? 'Daily' : 'Annually';
					} },
					{ title: 'Actions', data: 'action', name: 'action', className: 'text-right actions', orderable: false, searchable: false}
				],
				// order: [[ 0, 'asc' ], [ 1, 'asc' ]]
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
				'<a href="" class="on-default view-edit" data-toggle="tooltip" data-placement="bottom" title="查看編輯"><i class="fa fa-search"></i></a>',
				'<a href="" class="on-default edit-row" data-toggle="tooltip" data-placement="bottom" title="快速編輯"><i class="fa fa-pencil"></i></a>',
				'<a href="" class="on-default remove-row"><i class="fa fa-trash-o"></i></a>',
				'<input name="row_id" type="hidden">'
			].join(' ');

			data = this.datatable.row.add([ '', '', '', '', '', actions ]);
			$row = this.datatable.row( data[0] ).nodes().to$();

			$row
				.addClass( 'adding' )
				.find( 'td:last' )
				.addClass( 'actions text-right' );
			$row.find('td:nth-child(4)').addClass('client-size');
			$row.find('td:nth-child(5)').addClass('client-invoiced text-right');

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
				} else if ($this.hasClass('client-size')) {
					var selected;
					
					if ( data.is_small == 1 ) {
						selected = ' selected';
					}
					$this.html( '<select id="is_small"><option value="0"' + selected + '>普通</option><option value="1"' + selected + '>小</option></select>' );
				} else if ( $this.hasClass('client-invoiced') ) {
					var selected;
					
					if ( data.invoiced_daily == 1 ) {
						selected = ' selected';
					}
					$this.html('<select id="invoiced_daily"><option value="0"' + selected + '>年</option><option value="1"' + selected + '>日</option></select>');
				} else {
					var id, value;
					if (i == 0) {
						id = 'route';
						value = data.route;
					} else if (i == 1) {
						id = 'route_number';
						value = data.route_number;
					} else {
						id = 'name';
						value = data.name;
					}
					$this.html( '<input type="text" id="' + id + '" class="form-control input-block" value="' + value + '"/>' );
				}
			});
		},

		rowSave: function ($row) {

			var _self     = this,
				$actions,
				values = [];

			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
				}
			})

			var row_id = ($row.find('input[name="row_id"]').val()) ? $row.find('input[name="row_id"]').val() : '';

			var formData = {
				id: row_id,
				route: $('#route').val().toUpperCase(),
				route_number: $('#route_number').val(),
				name: $('#name').val(),
				is_small: $('#is_small').val(),
				invoiced_daily: $('#invoiced_daily').val()
			}

			//used to determine the http verb to use [add=POST], [update=PUT]
			var type = 'POST'; //for creating new resource
			var url = APP_URL + 'clients';

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

					if ( $row.hasClass( 'adding' ) ) {
						_self.$addButton.removeAttr( 'disabled' );
						$row.removeClass( 'adding' );
					}
					var values = {};
					$row.find('td').map(function() {
						var $this = $(this);

						if ( $this.hasClass('actions') ) {
							_self.rowSetActionsDefault( $row );
							values['action'] = _self.datatable.cell( this ).data();
							// return 
						} else if ( $this.hasClass('client-size') ) {
							var text = ( $this.find('#is_small').val() == 1 )? '小' : '普通';
							values['is_small'] = $.trim( text );
							// return 
						} else if ( $this.hasClass('client-invoiced') ) {
							var text = ( $this.find('#invoiced_daily').val() == 1 )? '日' : '年';
							values['invoiced_daily'] = $.trim( text );
							// return $.trim( text );
						} else {
							values[$this.find('input').prop('id')] = $.trim( $this.find('input').val() );
							// return $.trim( $this.find('input').val() );
						}
					});
					// console.log(values); return false;
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
						'Saved'
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
						'Messages',
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
			var url = APP_URL + 'clients' + '/' + row_id;

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
						'Delete was successful'
					);
				},
				error: function (data) {
					var response = data.responseJSON;

					$.Notification.notify(
						'error',
						'top right',
						'Messages',
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
			$row.find( '.client-invoiced' ).addClass( 'text-right' );
			$row.find( '.actions' ).addClass( 'text-right' );
			$row.find( '.on-editing' ).addClass( 'hidden' );
			$row.find( '.view-edit' ).prop('href', 'clients/' + id + '/edit');
			$row.find( '.on-default' ).removeClass( 'hidden' );
		}

	};

	$(function() {
		EditableTable.initialize();
	});

}).apply( this, [ jQuery ]);