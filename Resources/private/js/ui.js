(function($) {
    // BUGFIX: conflict with .mask plugin
    $.fn.masking = $.fn.mask;
    $.fn.mask = function () {
        return this.each(function () {
            $(this).prepend('<div class="wg-mask wg-mask--over"><div class="wg-loading"></div></div>');
        });
    };

    $.fn.unmask = function () {
        return this.each(function () {
            $(this).find('.wg-mask').remove();
        });
    };

    // TODO: move to after ajax content support
    $.fn.extend({
        toggleElement: function() {
            return this.each(function() {
                $(this).on('change', function(event) {
                    event.preventDefault();

                    var toggleElement = $(this);
                    var targetElement = $('#' + toggleElement.data('toggles'));

                    if (toggleElement.is(':checked')) {
                        targetElement.show();
                    } else {
                        targetElement.hide();
                    }
                });

                return $(this).trigger('change');
            });
        }
    });

    $.fn.extend({
        // quick form create dialog
        xdialog: function () {
            return this.each(function () {
                var $el = $(this),
                    btnHandle = $el.data('btn-handle'),
                    title = $el.data('title'),
                    btnLabel = $el.data('btn-label')
                ;

                $(btnHandle).click(function (e) {
                    e.preventDefault();
                    var $content = $($el.html());
                    $(document).trigger('dom-node-inserted', [$content]);

                    bootbox.dialog({
                        title: title || "Dialog",
                        message: $content,
                        buttons: {
                            success: {
                                label: btnLabel || "Save",
                                className: "btn-success",
                                callback: function () {
                                    var $form = $('.bootbox-body form'),
                                        valid = true
                                    ;

                                    $form.find('input[required]').each(function () {
                                        var $input = $(this);

                                        if (!$input.val().replace(/\s/g, '-')) {
                                            $input.closest('.form-group').addClass('has-error');

                                            // focus first input
                                            if (valid) {
                                                $input.focus();
                                            }

                                            valid = false;
                                        }
                                    });

                                    if (valid) {
                                        $form.submit();
                                        $(this).attr('disabled', true);
                                    }

                                    return valid;
                                }
                            }
                        }
                    });
                });
            });
        }
    });

    $.UI = {
        avatar: function (n) {
            n = n || Math.floor((Math.random() * 10) + 1);
            return '/assets/admin/images/profile/' + n + '.png';
        }
    };

    /// support after ajax content ///

    $(document).on('click', '[data-remote]', function (e) {
        e.preventDefault();

        var $el = $(this),
            url = $el.attr('href'),
            $target = $($el.data('remote'))
        ;

        $.ajax({url: url})
            .fail(function () { alert("Error")})
            .done(function (content) {
                $target.html(content);
            })
        ;
    });

    $(document).on('click', '[data-transition] [data-ts-action]', function (e) {
        e.preventDefault();

        $(this).data('callback', function (isOk, $btn) {
            if (isOk) {
                var url, medthod = 'PATCH';

                if ($btn.is('a')) {
                    url = $btn.attr('href');
                    medthod = $btn.data('method');
                }

                if ($btn.is('button')) {
                    var $form = $btn.closest('form');
                    url = $form.attr('action');
                    medthod = $form.find('input[name=_method]').val();
                }

                if (!url) {
                    console.error("Not found URL");
                }

                var urlsep = '?';

                if (/\?/.test(url)) {
                    urlsep = '&';
                }

                $.ajax({
                    url: url + urlsep + '_format=json',
                    type: medthod,
                    error: function () {
                        $.notifier.alert({type: 'error', message: 'Something gone wrong! Can not apply transition.'})
                    },
                    success: function () {
                        var $container = $btn.closest('[data-transition]');
                        var uuid = $container.data('ts-uuid');
                        var $allTsContainer = $('[data-ts-uuid=' + uuid + ']');

                        $.ajax({
                            url: $container.data('transition'),
                            type: 'GET',
                            success: function (response) {
                                $allTsContainer.find('.dropdown').fadeOut('fast', function () {
                                    $allTsContainer.html(response);
                                    $(document).trigger('dom-node-inserted', [$allTsContainer]);
                                });
                            }
                        })
                    }
                })
            }
        });
    });

    $(document).on('click', '[data-requires-confirmation]', function(e) {
        e.preventDefault();

        var actionButton = $(this),
            title = actionButton.data('title') || "Confirmation",
            message = actionButton.data('message') || "Are you sure?",
            callback = actionButton.data('callback')
        ;

        $.notifier.confirm({
            message: message,
            callback: callback,
            actionButton: actionButton
        });
    });

})(jQuery);
