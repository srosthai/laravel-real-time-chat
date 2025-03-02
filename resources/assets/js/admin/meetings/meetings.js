"use strict";

$('.start-time').datetimepicker({
    format:'YYYY-MM-DD h:mm A',
    useCurrent: false,
    icons: {
        previous: 'icon-arrow-left icons',
        next: 'icon-arrow-right icons'
    },
    sideBySide: true,
    minDate: moment().subtract(1, 'days'),
    widgetPositioning: {
        horizontal: 'left',
        vertical: 'bottom'
    }
});

$('.members').select2({
    minimumResultsForSearch: -1,
    placeholder: Lang.get('messages.placeholder.select_member'),
});

$('.time-zone').select2({
    placeholder: Lang.get('messages.placeholder.select_time_zone'),
});

$('#meetingForm').on('submit', function (event) {
    event.preventDefault();
    let loadingButton = jQuery(this).find('#btnSave');
    loadingButton.button('loading');

    $('#meetingForm')[0].submit();

    return true;
});
