$(function () {
    $(document).on('click',  '.adder-open', function (e) {
        var $btnOpen = $(this);
        var $panel = $btnOpen.closest('.adder-panel');
        var $form = $panel.find('.adder-form');

        if ($form.is(':hidden')) {
            $form.slideDown();
        } else {
            $form.slideUp();
        }
    });

    $(document).on('click',  '.adder-close', function (e) {
        var $panel = $(this).closest('.adder-form,.adder-update-form');

        $panel.slideUp(function () {
            if ($(this).hasClass('adder-update-form')) {
                $(this).remove();
            }
        });
    });

    $(document).on('click',  '.adder-submit', function (e) {
        e.preventDefault();

        var $el = $(this);
        var $adderPanel = $el.closest('.adder-panel');
        var $list = $adderPanel.find('.adder-entry-group');
        var $item = $el.closest('.adder-entry-item');
        var $embed = $el.closest('.adder-embed');
        var $errors = $embed.find('.form-errors');
        var action = $embed.data('remote-action');
        var method = $embed.data('remote-method');
        var form = $embed.data('remote-form');
        var data = $embed.find('input, select, textarea').serializeJSON();
        var ladda = Ladda.create(this);
        var $template = $("#" + $adderPanel.data('item-template'));
        var callback = $template.data('callback');
        var updateId = $embed.data('remote-id');
        var showAction = $adderPanel.data('show-action');

        //ladda.start();
        $el.attr('disabled', true);
        $errors.html('');

        $.ajax({
            url: action,
            data: data[form],
            type: method || 'POST',
            dataType: 'json',
            error: function (response) {
                //ladda.stop();
                $el.attr('disabled', false);
                console.log(response);
                $errors.html(ajax_form_errors_parser(response, form));
            },

            /**
             * @param {Object} response
             */
            success: function (response) {
                //ladda.stop();
                $el.attr('disabled', false);

                if (callback) {
                    response = window[callback](response);
                }

                var responseCallback = function (response) {
                    var $element = $(response);

                    if (updateId) {
                        $item.replaceWith($element.fadeIn('fast'));
                    } else {
                        $list.prepend($element.fadeIn('fast'));
                    }

                    $(document).trigger('dom-node-inserted', [$element]);
                };

                if (showAction) {
                    $.ajax({
                        url: showAction.replace(/:id/, updateId || response.id),
                        success: responseCallback
                    });
                } else {
                    var template = Handlebars.compile($template.html());
                    responseCallback(template(response));
                }
            }
        });
    });

    $(document).on('click', '.adder-remove', function (e) {
        e.preventDefault();

        var $el = $(this),
            $item = $el.closest('.adder-entry-item'),
            message = $el.data('message') || "Are you sure to remove this person?",
            callback = $el.data('callback'),
            id = $item.data('id'),
            link = $el.closest('.adder-panel').data('remove-link')
        ;

        $.notifier.confirm({
            message: message,
            callback: function (result) {
                if (!result) {
                    return;
                }

                $.ajax({
                    url: link.replace(/:id/, id),
                    // todo: check browser support DELETE method
                    type: 'DELETE',
                    dataType: 'json',
                    error: function () {
                        alert('Cannot delete.');
                    },
                    success: function () {
                        $item.fadeOut('fast', function () {
                            $(this).remove();
                        });
                    }
                });
            }
        });
    });

    $(document).on('click', '.adder-edit', function (e) {
        e.preventDefault();

        var $el = $(this),
            $item = $el.closest('.adder-entry-item'),
            id = $item.data('id'),
            action = $el.closest('.adder-panel').data('update-action').replace(/:id/, id),
            $element = $('<div class="adder-update-form"/>').appendTo($item)
        ;

        $element.html('Loading ..');

        $.ajax({
            url: action,
            success: function (response) {
                $item.append($element.html(response).fadeIn('fast'));

                $(document).trigger('dom-node-inserted', [$element]);
            }
        })
    });
});
