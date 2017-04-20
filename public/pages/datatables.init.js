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
                url :"/orders", // json datasource
                error: function(){  // error handling code
                    console.log('something is wrong');    
                }
            },
            dom: "Bfrtip",
            columns: [
                    { title: 'ID', data: 'id' },
                    { title: '客戶編號', data: 'clients.route' },
					{ title: '客戶', data: 'clients.name' },
					{ title: '訂購日期', data: 'ordered_date' },
					{ title: '總計', data: 'total', name: 'total', render: $.fn.dataTable.render.number( ',', '.', 0, '$' ), className: 'text-right' },
					{ title: '動作', data: 'action', name: 'action', className: 'text-right', orderable: false, searchable: false}
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