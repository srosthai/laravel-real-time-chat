"use strict"

$(document).ready(function () {
    let tbl = $('#memberMeetingTable').DataTable({
        processing: true,
        serverSide: true,
        'bStateSave': true,
        'order': [[0, 'asc']],
        ajax: {
            url: route('meetings.member_index'),
            data: function (data) {
                data.filter_user = $('#filter_user').
                    find('option:selected').
                    val();
            },
        },
        columnDefs: [
            {
                'targets': [1],
                'className': 'text-center',
                'width': '20%',
            },
            {
                'targets': [2, 4, 3],
                'className': 'text-center',
                'width': '70px',
            },
            {
                'targets': [5],
                'orderable': false,
                'className': 'text-center',
                'width': '100px',
            },
        ],
        columns: [
            {
                data: 'topic',
                name: 'topic',
            },
            {
                data: function (row) {
                    return moment(row.start_time).
                        locale(currentLocale).
                        format('Do MMM, YYYY hh:mm A')
                },
                name: 'id',
            },
            {
                data: function (row) {
                    return `${row.duration} minutes`;
                },
                name: 'duration',
            },
            {
                data: 'status_text',
                name: 'status',
            },
            {
                data: 'password',
                name: 'password',
            },
            {
                data: function (row) {
                    let btn = `<a href="javascript:void(0)" class="btn btn-danger btn-sm"><i class="fa fa-video-camera"></i> Finished</a>`;
                    if (row.status == 1) {
                        btn = `<a href="${row.meta.join_url}" target="_blank" class="btn btn-primary btn-sm"><i class="fa fa-video-camera"></i> Join Meeting</a>`;
                    }
                    return btn;
                },
                name: 'duration',
                'searchable': false,
            },
        ],
        drawCallback: function () {
            this.api().state.clear();
        },
        'fnInitComplete': function () {
            $('#filter_user').change(function () {
                tbl.ajax.reload()
            })
        },
    });
});
