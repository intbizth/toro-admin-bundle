$(document).on('click', 'a[data-readed-link]', function (e) {
    $(this).closest('.unreaded')
        .removeClass('unreaded')
        .addClass('readed')
        .find('.fa-envelope-o')
        .removeClass('fa-envelope-o')
        .addClass('fa-envelope-open-o')
    ;
});
