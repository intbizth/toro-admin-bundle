$(document).on('click', '[data-notify-bell]', function() {
    $(this).removeClass(function (index, className) {
        return (className.match (/(^|\s)notify-bell--\S+/g) || []).join(' ');
    });
});
