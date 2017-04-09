$(document).on('click', '[data-ajax-form] button[type=submit],button[data-ajax-form]', function (e) {
    e.stopPropagation();
    e.preventDefault();

    var $btn = $(this);
    var $form = $btn.closest('form');
    var data = new FormData();
    var url = $form.data('ajax-form') || $form.attr('action');

    $form.find('input[type=file]').each(function (i, f) {
        data.append(f.name, f.files[0] || "");
    });

    $.each($form.serializeArray(), function (i, f) {
        data.append(f.name, f.value);
    });

    $btn.attr('disabled', true);
    $form
        .addClass('loading')
        .append('<div class="' + ($form.data('loading') || 'toro-loading-pulse') + '"/>')
    ;

    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        cache: false,
        processData: false, // Don't process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        success: function (res, textStatus, jqXHR) {
            var callback = $form.data('ajax-callback');

            if (callback) {
                window[callback].call(res, $btn, $form, textStatus, jqXHR);
                return;
            }

            var $res = $(res), hasError = $res.find($form.data('error') || '.has-error').length;

            var reload = $form.data('reload');

            if (true === reload && !hasError) {
                window.location.reload();
                return;
            }

            if (reload && !hasError) {
                window.location.href = reload;
                return;
            }

            if ($form.data('ajax-reload') && !hasError) {
                $.ajax({
                    url: $form.data('ajax-reload'),
                    success: function (res) {
                        var $res = $(res);
                        var $target = $form.data('ajax-reload-target');

                        if (typeof $target === 'string') {
                            $target = $($target);
                        }

                        $('.modal').modal('hide');

                        $target.replaceWith($res);
                        $(document).trigger('dom-node-inserted', [$res]);
                    }
                });

                return;
            }

            if ($form.data('redirect') && !hasError) {
                window.location.href = $form.data('redirect');
                return;
            }

            $btn.attr('disabled', false);
            $form.replaceWith($res);
            $(document).trigger('dom-node-inserted', [$res]);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $btn.attr('disabled', false);
            $form.removeClass('loading');
        }
    });
});
