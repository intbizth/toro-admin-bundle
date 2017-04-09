$(function () {
    Dropzone.autoDiscover = false;

    var initScripting = function (scope)
    {
        $('i.readed', scope).each(function () {
            $(this).closest('tr').addClass('readed');
        });

        $('i.unreaded', scope).each(function () {
            $(this).closest('tr').addClass('unreaded');
        });

        $('.switchery', scope).each(function (i, html) {
            new (require("switchery"))(html);
        });

        $('.checkbox>input[type=checkbox]', scope).addClass('magic-checkbox');

        $('[data-toggle-change]', scope).each(function () {
            $(this).change(function () {
                $($(this).data('toggle-change')).toggle();
            });
        });

        $('[data-mask]', scope).each(function () {
            $(this).masking($(this).data('mask'));
        });

        $('[data-toggle=tooltip]', scope).tooltip();

        SelectizeSetup('select, [data-chooser]', scope);

        //$('[type=date]').datepicker();
        //$('.input-group.date').datepicker();

        $('script[type="text/x-dialog"]', scope).xdialog();

        $('.datetime, [type=datetime]', scope)
        //.attr('readonly', true)
            .wrap('<div class="input-group date"/>')
            .closest('.input-group')
            // clear bug: https://github.com/smalot/bootstrap-datetimepicker/issues/522
            // to clear, don't readonly input
            //.append('<span class="input-group-addon"><i class="fa fa-times"></i></span>')
            // need `fa-calendar` by core :(
            .append('<span class="input-group-addon"><i class="fa fa-calendar"></i></span>')

            .datetimepicker({
                format: 'YYYY-MM-DD HH:mm',
                parseInputDate: function (val) {
                    return moment(val, 'YYYY-MM-DD HH:mm');
                }
            })
        ;

        $('[type=date]', scope)
            .attr('type', 'text')
            //.attr('readonly', true)
            .wrap('<div class="input-group date"/>')
            .closest('.input-group')
            // clear bug: https://github.com/smalot/bootstrap-datetimepicker/issues/522
            // to clear, don't readonly input
            //.append('<span class="input-group-addon"><i class="fa fa-times"></i></span>')
            // need `fa-calendar` by core :(
            .append('<span class="input-group-addon"><i class="fa fa-calendar"></i></span>')

            .datetimepicker({
                format: 'YYYY-MM-DD',
                parseInputDate: function (val) {
                    return moment(val, 'YYYY-MM-DD');
                }
            })
        ;
    };

    $(document).on('dom-node-inserted', function (e, scope) {
        initScripting(scope);
    });

    if ($('[type="text/x-handlebars-template"]').length) {
        $.getScript('/assets/admin/handlebars/handlebars.min.js', function () {
            console.log('Handlebars was loaded!');
            $.getScript('/assets/admin/handlebars-intl/handlebars-intl-with-locales.min.js', function () {
                HandlebarsIntl.registerWith(Handlebars);
                console.log('Handlebars-intl was loaded!');
            });
        });
    }

    initScripting(document);
});
