
$(document).ready(function() {
/////Memebership Table List
if ($('.membershipList').length) {
    $('.membershipList').DataTable({
    serverSide: true,
    processing: true,
    responsive: true,
    rowReorder: {
        selector: 'td:nth-child(2)'
    },
    dom: "<'row mb-3'<'col-sm-4'l><'col-sm-8 d-flex justify-content-end align-items-center gap-2'fB>>" +
    "<'row'<'col-sm-12'tr>>" +
    "<'row mt-2'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",
    buttons: [
        {
            extend: 'collection',
            text: 'Export',
            className: 'btn btn-light dropdown-toggle',
            buttons: [
                {
                    extend: 'csv',
                    className: 'dropdown-item',
                    exportOptions: { columns: [0, 1, 2, 3] }
                },
                {
                    extend: 'excel',
                    className: 'dropdown-item',
                    exportOptions: { columns: [0, 1, 2, 3] }
                },
                {
                    extend: 'pdf',
                    className: 'dropdown-item',
                    exportOptions: { columns: [0, 1, 2, 3] }
                },
                {
                    extend: 'print',
                    className: 'dropdown-item',
                    exportOptions: { columns: [0, 1, 2, 3] }
                }
            ]
        }
    ],
    ajax: {
        url: membershipUrl
    },
    columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'title', name: 'title' },
        { data: 'desc', name: 'desc' },
        { data: 'status', name: 'status' },
        { data: 'action', name: 'action', orderable: false, searchable: false},
    ]

});
}

/////////Taxt list table
if ($('.taxList').length) {
    $('.taxList').DataTable({
    serverSide: true,
    processing: true,
    responsive: true,
    rowReorder: {
        selector: 'td:nth-child(2)'
    },
    dom: "<'row mb-3'<'col-sm-4'l><'col-sm-8 d-flex justify-content-end align-items-center gap-2'fB>>" +
    "<'row'<'col-sm-12'tr>>" +
    "<'row mt-2'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",
    buttons: [
        {
            extend: 'collection',
            text: 'Export',
            className: 'btn btn-light dropdown-toggle',
            buttons: [
                {
                    extend: 'csv',
                    className: 'dropdown-item',
                    exportOptions: { columns: [0, 1, 2, 3] }
                },
                {
                    extend: 'excel',
                    className: 'dropdown-item',
                    exportOptions: { columns: [0, 1, 2, 3] }
                },
                {
                    extend: 'pdf',
                    className: 'dropdown-item',
                    exportOptions: { columns: [0, 1, 2, 3] }
                },
                {
                    extend: 'print',
                    className: 'dropdown-item',
                    exportOptions: { columns: [0, 1, 2, 3] }
                }
            ]
        }
    ],
    ajax: {
        url: taxtUrl
    },
    columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'name', name: 'name' },
        { data: 'percent', name: 'percent' },
        { data: 'desc', name: 'desc' },
        { data: 'status', name: 'status' },
        { data: 'action', name: 'action', orderable: false, searchable: false},
    ]

});
}


//////////////User Member List
if ($('.userMemberlist').length) {
    let table = $('.userMemberlist').DataTable({
        serverSide: true,
        processing: true,
        responsive: true,
        dom: 't<"row mt-2"<"col-sm-6"i><"col-sm-6 d-flex justify-content-end"p>>',
        ajax: {
            url: usermemberUrl,
            data: function (d) {
                d.name = $('#nameFilter').val();
                d.email = $('#emailFilter').val();
                d.phone = $('#phoneFilter').val();
                d.company_name = $('#companynameFilter').val();
                d.company_type = $('#companytypeFilter').val();
            }
        },
        pageLength: 10,
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'phone', name: 'phone' },
            { data: 'company_name', name: 'company_name' },
            { data: 'category_name', name: 'category_name' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
    let debounceTimeout;
    $('#nameFilter, #emailFilter, #phoneFilter, #companynameFilter').on('keyup', function () {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => table.ajax.reload(), 300);
    });

    $('#companytypeFilter, #lengthFilter').on('change', function () {
        if (this.id === 'lengthFilter') {
            table.page.len($(this).val()).draw();
        } else {
            table.ajax.reload();
        }
    });

    $('#exportCsv').on('click', () => table.button('.buttons-csv').trigger());
    $('#exportExcel').on('click', () => table.button('.buttons-excel').trigger());
    $('#exportPdf').on('click', () => table.button('.buttons-pdf').trigger());
    $('#exportPrint').on('click', () => table.button('.buttons-print').trigger());
}


//////Company List Table
if ($('.companyList').length) {
    let table = $('.companyList').DataTable({
        serverSide: true,
        processing: true,
        responsive: true,
       dom: 't<"row mt-2"<"col-sm-6"i><"col-sm-6 d-flex justify-content-end"p>>',
       ajax: {
            url: companylistUrl,
            data: function (d) {
                d.membership_id = $('#membershiidFilter').val();
                d.company_name = $('#companynameFilter').val();
                d.category = $('#companytypeFilter').val();
            }

        },
        pageLength: 10,
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'membership_id', name: 'membership_id' },
            { data: 'company_name', name: 'company_name' },
            { data: 'category_name', name: 'category_name' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

     // Auto filter on input (with debounce to prevent rapid firing)
    let debounceTimeout1;
    $('#membershiidFilter, #companynameFilter').on('keyup', function () {
        clearTimeout(debounceTimeout1);
        debounceTimeout1 = setTimeout(() => table.ajax.reload(), 300);
    });

    $('#companytypeFilter, #lengthFilter').on('change', function () {
        if (this.id === 'lengthFilter') {
            table.page.len($(this).val()).draw();
        } else {
            table.ajax.reload();
        }
    });

    // Export buttons
    $('#exportCsv').on('click', () => table.button('.buttons-csv').trigger());
    $('#exportExcel').on('click', () => table.button('.buttons-excel').trigger());
    $('#exportPdf').on('click', () => table.button('.buttons-pdf').trigger());
    $('#exportPrint').on('click', () => table.button('.buttons-print').trigger());
}


//////Category List Table
if ($('.categoryList').length) {
    let table = $('.categoryList').DataTable({
        serverSide: true,
        processing: true,
        responsive: true,
        dom: 't<"row mt-2"<"col-sm-6"i><"col-sm-6 d-flex justify-content-end"p>>',
        ajax: {
            url: categoryUrl,
            data: function (d) {
                d.category_name = $('#categorynameFilter').val();
                d.status = $('#statusFilter').val();
            }
        },
        pageLength: 10,
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'category_name', name: 'category_name' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
    let debounceTimeout;
    $('#categorynameFilter').on('keyup', function () {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => table.ajax.reload(), 300);
    });

    $('#statusFilter, #lengthFilter').on('change', function () {
        if (this.id === 'lengthFilter') {
            table.page.len($(this).val()).draw();
        } else {
            table.ajax.reload();
        }
    });

    $('#exportCsv').on('click', () => table.button('.buttons-csv').trigger());
    $('#exportExcel').on('click', () => table.button('.buttons-excel').trigger());
    $('#exportPdf').on('click', () => table.button('.buttons-pdf').trigger());
    $('#exportPrint').on('click', () => table.button('.buttons-print').trigger());
}


//////SubCategory List
if ($('.subcategoryList').length) {
    let table = $('.subcategoryList').DataTable({
        serverSide: true,
        processing: true,
        responsive: true,
        dom: 't<"row mt-2"<"col-sm-6"i><"col-sm-6 d-flex justify-content-end"p>>',
        ajax: {
            url: subcategoryUrl,
            data: function (d) {
                d.subcategory_name = $('#subcategoryFilter').val();
                d.category = $('#parentcategoryFilter').val();
                d.status = $('#statusFilter').val();
            }
        },
        pageLength: 10,
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'subcategory_name', name: 'subcategory_name' },
            { data: 'category_name', name: 'category_name' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
    let debounceTimeout;
    $('#subcategoryFilter').on('keyup', function () {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => table.ajax.reload(), 300);
    });

    $('#parentcategoryFilter,#statusFilter, #lengthFilter').on('change', function () {
        if (this.id === 'lengthFilter') {
            table.page.len($(this).val()).draw();
        } else {
            table.ajax.reload();
        }
    });

    $('#exportCsv').on('click', () => table.button('.buttons-csv').trigger());
    $('#exportExcel').on('click', () => table.button('.buttons-excel').trigger());
    $('#exportPdf').on('click', () => table.button('.buttons-pdf').trigger());
    $('#exportPrint').on('click', () => table.button('.buttons-print').trigger());
}

//////FAQ Table List
if ($('.faqList').length) {
    let table = $('.faqList').DataTable({
        serverSide: true,
        processing: true,
        responsive: true,
        dom: 't<"row mt-2"<"col-sm-6"i><"col-sm-6 d-flex justify-content-end"p>>',
        ajax: {
            url: faqUrl,
            data: function (d) {
                d.question = $('#questionFilter').val();
                d.answer = $('#answerFilter').val();
                d.status = $('#statusFilter').val();
            }
        },
        pageLength: 10,
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            {
                data: 'question',
                name: 'question',
                render: function (data) {
                    return data.length > 50 ? data.substring(0, 50) + '...' : data;
                }
            },
            {
                data: 'answer',
                name: 'answer',
                render: function (data) {
                    const limited = data.length > 50 ? data.substring(0, 50) + '...' : data;
                    return limited;
                }
            },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]

    });
    let debounceTimeout;
    $('#questionFilter, #answerFilter').on('keyup', function () {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => table.ajax.reload(), 300);
    });

    $('#statusFilter, #lengthFilter').on('change', function () {
        if (this.id === 'lengthFilter') {
            table.page.len($(this).val()).draw();
        } else {
            table.ajax.reload();
        }
    });

    $('#exportCsv').on('click', () => table.button('.buttons-csv').trigger());
    $('#exportExcel').on('click', () => table.button('.buttons-excel').trigger());
    $('#exportPdf').on('click', () => table.button('.buttons-pdf').trigger());
    $('#exportPrint').on('click', () => table.button('.buttons-print').trigger());
}


////Event List
if ($('.eventList').length) {
    let table = $('.eventList').DataTable({
        serverSide: true,
        processing: true,
        responsive: true,
        dom: 't<"row mt-2"<"col-sm-6"i><"col-sm-6 d-flex justify-content-end"p>>',
        ajax: {
            url: eventUrl,
            data: function (d) {
                d.title = $('#eventtitleFilter').val();
                d.eventstartdatetime = $('#eventdateFilter').val();
                d.mode = $('#eventmodeFilter').val();
                d.type = $('#eventtypeFilter').val();
            }
        },
        pageLength: 10,
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'title', name: 'title' },
            { data: 'eventstartdatetime', name: 'eventstartdatetime' },
            { data: 'mode', name: 'mode' },
            { data: 'type', name: 'type' },
            { data: 'eventform', name: 'eventform', orderable: false, searchable: false },
            { data: 'visitors', name: 'visitors', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
    let debounceTimeout;
    $('#eventtitleFilter, #eventdateFilter').on('keyup', function () {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => table.ajax.reload(), 300);
    });

    $('#eventmodeFilter, #eventtypeFilter, #lengthFilter').on('change', function () {
        if (this.id === 'lengthFilter') {
            table.page.len($(this).val()).draw();
        } else {
            table.ajax.reload();
        }
    });

    $('#exportCsv').on('click', () => table.button('.buttons-csv').trigger());
    $('#exportExcel').on('click', () => table.button('.buttons-excel').trigger());
    $('#exportPdf').on('click', () => table.button('.buttons-pdf').trigger());
    $('#exportPrint').on('click', () => table.button('.buttons-print').trigger());
}
////Visitor Exhibition Registration List
if ($('.visitorList').length) {
    let table = $('.visitorList').DataTable({
        serverSide: true,
        processing: true,
        responsive: true,
        ajax: {
            url: visitorUrl,
            data: function (d) {
                d.name = $('#nameFilter').val();
                d.email = $('#emailFilter').val();
                d.phone = $('#phoneFilter').val();
                d.payment_status = $('#paymentFilter').val();
            }
        },
        pageLength: 10,
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'visitor_code', name: 'visitor_code' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'phone', name: 'phone' },
            { data: 'designation', name: 'designation' },
            { data: 'city', name: 'city' },
            { data: 'registration_fee', name: 'registration_fee', orderable: false, searchable: false },
            { data: 'payment_status', name: 'payment_status', orderable: false, searchable: false },
            { data: 'registered_at', name: 'registered_at' }
        ]
    });

    let debounceTimeout;
    $('#nameFilter, #emailFilter, #phoneFilter').on('keyup', function () {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => table.ajax.reload(), 300);
    });

    $('#paymentFilter').on('change', function () {
        table.ajax.reload();
    });
}
/////Event schedule list
if ($('#eventScheduleTable').length) {
    let table = $('#eventScheduleTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 10,
        dom: 't<"row mt-2"<"col-sm-6"i><"col-sm-6 d-flex justify-content-end"p>>',
        ajax: {
            url: eventscheduleUrl,
            data: function (d) {
                d.title = $('#titleFilter').val();
                d.session_title = $('#sessionTitleFilter').val();
                d.speaker = $('#speakerFilter').val();
                d.payment_mode = $('#paymentModeFilter').val();
                d.start_time = $('#startTimeFilter').val();
                d.end_time = $('#endTimeFilter').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'title', name: 'title' },
            { data: 'session_title', name: 'session_title' },
            { data: 'speaker', name: 'speaker' },
            { data: 'payment_mode', name: 'payment_mode' },
            { data: 'scheduleform', name: 'scheduleform', orderable: false, searchable: false },
            { data: 'start_time', name: 'start_time' },
            { data: 'end_time', name: 'end_time' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ]
    });

    let debounceTimeout;
    $('#titleFilter, #sessionTitleFilter, #speakerFilter, #startTimeFilter, #endTimeFilter').on('keyup change', function () {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => table.ajax.reload(), 300);
    });

    $('#paymentModeFilter').on('change', function () {
        table.ajax.reload();
    });
}



/////////Greeting list
if ($('.greetingList').length) {
    let table = $('.greetingList').DataTable({
        serverSide: true,
        processing: true,
        responsive: true,
        dom: 't<"row mt-2"<"col-sm-6"i><"col-sm-6 d-flex justify-content-end"p>>',
        ajax: {
            url: greetingImgUrl,
            data: function (d) {
                d.type = $('#fileTypeFilter').val();
                d.status = $('#statusFilter').val();
            }
        },
        pageLength: 10,
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            {
                data: 'type',
                name: 'type',
                render: function (data) {
                    return data.length > 50 ? data.substring(0, 50) + '...' : data;
                }
            },
            {
                data: 'image',
                name: 'image',
                orderable: false,
                searchable: false
            },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]

    });
    let debounceTimeout;
    $('#fileTypeFilter').on('keyup', function () {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => table.ajax.reload(), 300);
    });

    $('#statusFilter, #lengthFilter').on('change', function () {
        if (this.id === 'lengthFilter') {
            table.page.len($(this).val()).draw();
        } else {
            table.ajax.reload();
        }
    });

    $('#exportCsv').on('click', () => table.button('.buttons-csv').trigger());
    $('#exportExcel').on('click', () => table.button('.buttons-excel').trigger());
    $('#exportPdf').on('click', () => table.button('.buttons-pdf').trigger());
    $('#exportPrint').on('click', () => table.button('.buttons-print').trigger());
}

/////////////Navigation List
if ($('.navigationmenuList').length) {
        $('.navigationmenuList').DataTable({
            serverSide: true,
            processing: true,
            responsive: true,
            rowReorder: {
                selector: 'td:nth-child(2)'
            },
            dom: "<'row mb-3'<'col-sm-4'l><'col-sm-8 d-flex justify-content-end align-items-center gap-2'fB>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row mt-2'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",
            language: {
                lengthMenu: '<select class="form-select">'+
                                '<option value="10">10</option>'+
                                '<option value="25">25</option>'+
                                '<option value="50">50</option>'+
                                '<option value="100">100</option>'+
                            '</select>'
            },
            buttons: [
                {
                    extend: 'collection',
                    text: '<i class="bi bi-download"></i>',
                    className: 'btn btn-light dropdown-toggle',
                    buttons: [
                        {
                            extend: 'csv',
                            className: 'dropdown-item',
                            exportOptions: { columns: [0, 1, 2, 3] }
                        },
                        {
                            extend: 'excel',
                            className: 'dropdown-item',
                            exportOptions: { columns: [0, 1, 2, 3] }
                        },
                        {
                            extend: 'pdf',
                            className: 'dropdown-item',
                            exportOptions: { columns: [0, 1, 2, 3] }
                        },
                        {
                            extend: 'print',
                            className: 'dropdown-item',
                            exportOptions: { columns: [0, 1, 2, 3] }
                        }
                    ]
                }
            ],
            ajax: {
                url: navigationmenuUrl
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'title', name: 'title' },
                { data: 'route', name: 'route'},
                { data: 'is_active', name: 'is_active' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]

        });
    }

    ///////////Committee List
    if ($('.committeeList').length) {
        $('.committeeList').DataTable({
            serverSide: true,
            processing: true,
            responsive: true,
            rowReorder: {
                selector: 'td:nth-child(2)'
            },
            dom: "<'row mb-3'<'col-sm-4'l><'col-sm-8 d-flex justify-content-end align-items-center gap-2'fB>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row mt-2'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",
            language: {
                lengthMenu: '<select class="form-select">'+
                                '<option value="10">10</option>'+
                                '<option value="25">25</option>'+
                                '<option value="50">50</option>'+
                                '<option value="100">100</option>'+
                            '</select>'
            },
            buttons: [
                {
                    extend: 'collection',
                    text: '<i class="bi bi-download"></i>',
                    className: 'btn btn-light dropdown-toggle',
                    buttons: [
                        {
                            extend: 'csv',
                            className: 'dropdown-item',
                            exportOptions: { columns: [0, 1, 2, 3] }
                        },
                        {
                            extend: 'excel',
                            className: 'dropdown-item',
                            exportOptions: { columns: [0, 1, 2, 3] }
                        },
                        {
                            extend: 'pdf',
                            className: 'dropdown-item',
                            exportOptions: { columns: [0, 1, 2, 3] }
                        },
                        {
                            extend: 'print',
                            className: 'dropdown-item',
                            exportOptions: { columns: [0, 1, 2, 3] }
                        }
                    ]
                }
            ],
            ajax: {
                url: committeeUrl
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'member_name', name: 'member_name' },
                { data: 'position', name: 'position'},
                { data: 'education', name: 'education' },
                { data: 'committee_category', name: 'committee_category', orderable: false, searchable: false },
                { data: 'profile', name: 'profile', orderable: false, searchable: false },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    }

    /////Adv Img Size List
    if ($('.imgsizeList').length) {
        $('.imgsizeList').DataTable({
            serverSide: true,
            processing: true,
            responsive: true,
            rowReorder: {
                selector: 'td:nth-child(2)'
            },
            dom: "<'row mb-3'<'col-sm-4'l><'col-sm-8 d-flex justify-content-end align-items-center gap-2'fB>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row mt-2'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",
            language: {
                lengthMenu: '<select class="form-select">'+
                                '<option value="10">10</option>'+
                                '<option value="25">25</option>'+
                                '<option value="50">50</option>'+
                                '<option value="100">100</option>'+
                            '</select>'
            },
            buttons: [
                {
                    extend: 'collection',
                    text: '<i class="bi bi-download"></i>',
                    className: 'btn btn-light dropdown-toggle',
                    buttons: [
                        {
                            extend: 'csv',
                            className: 'dropdown-item',
                            exportOptions: { columns: [0, 1, 2, 3] }
                        },
                        {
                            extend: 'excel',
                            className: 'dropdown-item',
                            exportOptions: { columns: [0, 1, 2, 3] }
                        },
                        {
                            extend: 'pdf',
                            className: 'dropdown-item',
                            exportOptions: { columns: [0, 1, 2, 3] }
                        },
                        {
                            extend: 'print',
                            className: 'dropdown-item',
                            exportOptions: { columns: [0, 1, 2, 3] }
                        }
                    ]
                }
            ],
            ajax: {
                url: advimgsizeUrl
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'width', name: 'width' },
                { data: 'height', name: 'height'},
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    }
    /////Inquiry contact list
    if ($('.inquirycontactList').length) {
        $('.inquirycontactList').DataTable({
            serverSide: true,
            processing: true,
            responsive: true,
            rowReorder: {
                selector: 'td:nth-child(2)'
            },
            dom: "<'row mb-3'<'col-sm-4'l><'col-sm-8 d-flex justify-content-end align-items-center gap-2'fB>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row mt-2'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",
            language: {
                lengthMenu: '<select class="form-select">'+
                                '<option value="10">10</option>'+
                                '<option value="25">25</option>'+
                                '<option value="50">50</option>'+
                                '<option value="100">100</option>'+
                            '</select>'
            },
            buttons: [
                {
                    extend: 'collection',
                    text: '<i class="bi bi-download"></i>',
                    className: 'btn btn-light dropdown-toggle',
                    buttons: [
                        {
                            extend: 'csv',
                            className: 'dropdown-item',
                            exportOptions: { columns: [0, 1, 2, 3] }
                        },
                        {
                            extend: 'excel',
                            className: 'dropdown-item',
                            exportOptions: { columns: [0, 1, 2, 3] }
                        },
                        {
                            extend: 'pdf',
                            className: 'dropdown-item',
                            exportOptions: { columns: [0, 1, 2, 3] }
                        },
                        {
                            extend: 'print',
                            className: 'dropdown-item',
                            exportOptions: { columns: [0, 1, 2, 3] }
                        }
                    ]
                }
            ],
            ajax: {
                url: contactlistUrl
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'to', name: 'to'},
                { data: 'phone', name: 'phone' },
                { data: 'subject', name: 'subject' },
                { data: 'message', name: 'message' },
                { data: 'created_at', name: 'created_at' }
            ]
        });
    }


const table = $('.emailList').DataTable({
        serverSide: true,
        processing: true,
        responsive: true,
        ajax: {
            url: emailUrl,
            data: function (d) {
                d.status = $('#statusFilter').val(); // Send filter to server
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'phone', name: 'phone' },
            { data: 'email', name: 'email' },
            { data: 'email_verified_at', name: 'email_verified_at' },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    // Refresh DataTable on status filter change
    $('#statusFilter').on('change', function () {
        table.ajax.reload();
    });




});


    // Resend verification email with SweetAlert


////delete button
// $(document).on('click', '.delete-confirm', function (e) {
//     e.preventDefault();
//     const url = $(this).attr('href');

//     Swal.fire({
//         title: 'Are you sure?',
//         text: "This data will be permanently deleted!",
//         icon: 'warning',
//         showCancelButton: true,
//         confirmButtonColor: '#3085d6',
//         cancelButtonColor: '#d33',
//         confirmButtonText: 'Yes, delete it!'
//     }).then((result) => {
//         if (result.isConfirmed) {
//             $.ajax({
//                 url: url,
//                 type: 'POST',
//                 data: {
//                     _method: 'DELETE',
//                     _token: $('meta[name="csrf-token"]').attr('content'),
//                 },
//                 success: function(response) {
//                     $('.membershipList').DataTable().ajax.reload();
//                     Swal.fire('Deleted!', 'The data has been deleted.', 'success');
//                 },
//                 error: function() {
//                     Swal.fire('Error!', 'Something went wrong.', 'error');
//                 }
//             });
//         }
//     });
// });

$(document).on('click', '.delete-confirm', function (e) {
    e.preventDefault();

    const url = $(this).attr('href'); // URL for DELETE route
    const row = $(this).closest('tr'); // Optional: for removing row without DataTables

    Swal.fire({
        title: 'Are you sure?',
        text: 'This action cannot be undone!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                success: function (response) {
                    Swal.fire('Deleted!', 'Your data has been deleted.', 'success');

                    // If DataTable is present
                    if ($.fn.DataTable && $('.membershipList').length) {
                        $('.membershipList').DataTable().ajax.reload();
                    } else {
                        // Fallback: remove row manually
                        row.remove();
                    }
                },
                error: function () {
                    Swal.fire('Error!', 'Something went wrong.', 'error');
                }
            });
        }
    });
});



function conformation(ev) {
    ev.preventDefault();
    var urlToRedirect = ev.currentTarget.getAttribute('href');

    Swal.fire({
        title: "Are you sure to delete this?",
        text: "This delete will be permanent!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = urlToRedirect;
        } else {
            Swal.fire("Cancelled", "Your data is safe 😊", "info");
        }
    });
}

//////////Email send in user
$(document).on('click', '.send-email', function () {
    var userId = $(this).data('id');

    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to send credentials email to this user?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, send it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/member/send-email/' + userId,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                success: function (response) {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        timer: 3000,
                        showConfirmButton: false
                    });
                },
                error: function () {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to send email.',
                        icon: 'error',
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
            });
        }
    });
});

/////Select 2
$(document).ready(function () {
    $('.custom-select2').select2({
        templateResult: formatDropdownOption,
        templateSelection: formatDropdownSelection,
        // placeholder: "--Select--",
        allowClear: true
    });

    function formatDropdownOption(option) {
        if (!option.id) {
            return option.text;
        }
        const text = $(option.element).text();
        return $('<span>' + text + '</span>');
    }

    function formatDropdownSelection(option) {
        return option.text || option.id;
    }
});

/////Event schedule clone
document.addEventListener("DOMContentLoaded", function () {
    const wrapper = document.getElementById('schedule-wrapper');
    const addBtn = document.getElementById('add-schedule');

    function updateScheduleHeadings() {
        const scheduleItems = wrapper.querySelectorAll('.schedule-item');
        scheduleItems.forEach((item, index) => {
            const heading = item.querySelector('.schedule-heading');
            heading.textContent = `Schedule ${index + 1}`;
        });
    }

    //  Show/hide amount field based on payment mode
    function toggleAmountField(container) {
        const paymentSelect = container.querySelector('select[name="payment_mode[]"]');
        const amountField = container.querySelector('input[name="amount"]');

        if (paymentSelect.value === 'paid') {
            amountField.closest('.col-md-6').style.display = 'block';
        } else {
            amountField.closest('.col-md-6').style.display = 'none';
            amountField.value = ''; // clear the amount if switching back to Free
        }

        // Attach change event listener (only once per dropdown)
        paymentSelect.addEventListener('change', function () {
            toggleAmountField(container);
        });
    }

    // 👇 Initialize amount field logic on all schedule items
    function initializeAmountVisibility() {
        const scheduleItems = wrapper.querySelectorAll('.schedule-item');
        scheduleItems.forEach((item) => {
            toggleAmountField(item);
        });
    }

    addBtn.addEventListener('click', function () {
        const scheduleItems = wrapper.querySelectorAll('.schedule-item');
        const newItem = scheduleItems[0].cloneNode(true);

        newItem.querySelectorAll('input, textarea, select').forEach(input => {
            if (input.type === 'file') {
                input.value = '';
            } else {
                input.value = '';
            }
        });

        wrapper.appendChild(newItem);
        updateScheduleHeadings();
        initializeAmountVisibility(); // reinitialize after adding
    });

    wrapper.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-schedule')) {
            const allItems = wrapper.querySelectorAll('.schedule-item');
            if (allItems.length > 1) {
                e.target.closest('.schedule-item').remove();
                updateScheduleHeadings();
            } else {
                alert('At least one schedule is required.');
            }
        }
    });

    updateScheduleHeadings();
    initializeAmountVisibility(); // initial call
});
