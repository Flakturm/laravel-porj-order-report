/**
 * Theme: Ubold Admin Template
 * Author: Coderthemes
 * Component: Datatable
 * 
 */
var ordersButtons = function() {
        "use strict";
        0 !== $("#datatable-buttons").length && $("#datatable-buttons").DataTable({
            processing: false,
            serverSide: true,
            ajax:{
                url : APP_URL + "orders", // json datasource
                error: function(){  // error handling code
                    console.log('something is wrong');    
                }
            },
            dom: "Bfrtip",
            columns: [
                    { title: 'ID', data: 'id' },
                    { title: 'Client type', data: 'clients.route' },
					{ title: 'Client', data: 'clients.name' },
					{ title: 'Ordered date', data: 'ordered_date' },
					{ title: 'Total', data: 'total', name: 'total', render: $.fn.dataTable.render.number( ',', '.', 0, '$' ), className: 'text-right' },
					{ title: 'Actions', data: 'action', name: 'action', className: 'text-right', orderable: false, searchable: false}
            ],
            order: [[ 0, 'desc' ]],
            buttons: [{
                extend: "copy",
                className: "btn-sm"
            }, {
                extend: "csv",
                className: "btn-sm"
            }, {
                extend: "excel",
                className: "btn-sm"
            }, {
                extend: "pdf",
                className: "btn-sm"
            }, {
                extend: "print",
                className: "btn-sm"
            }],
            responsive: !0
        })
    },
    clientOrdersButtons = function() {
        "use strict";
        0 !== $("#datatable-buttons").length && $("#datatable-buttons").DataTable({
            dom: "Bfrtip",
            "columns": [
                    { "className":  'details-control', "orderable": false },
                    { },
					{ },
					{ },
					{ "orderable": false },
            ],
            "order": [[ 1, 'desc' ]],
            buttons: [{
                extend: "copy",
                className: "btn-sm"
            }, {
                extend: "csv",
                className: "btn-sm"
            }, {
                extend: "excel",
                className: "btn-sm"
            }, {
                extend: "pdf",
                className: "btn-sm"
            }, {
                extend: "print",
                className: "btn-sm"
            }],
            responsive: !0
        })
    },
    TableManageButtons = function() {
        "use strict";
        return {
            orders: function() {
                ordersButtons()
            },
            clientOrders: function() {
                clientOrdersButtons()
            }
        }
    }();