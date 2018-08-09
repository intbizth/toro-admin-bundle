$(function () {
    $.notifier = {
        error: function (message) {
            $.notifier.alert({type: 'error', message: message});
        },

        alert: function (config) {
            if (typeof config === 'string') {
                config = {message: config};
            }

            if (config.type && config.type == 'info') {
                config.type = 'information';
            }

            noty({
                text: config.message,
                type: config.type,
                layout: config.layout || 'topCenter',
                theme: 'relax',
                dismissQueue: true,
                modal: config.modal || false,
                timeout: config.timeout || 10000,
                animation: {
                    open: 'animated fadeInDownBig',
                    close: 'animated fadeOutUpBig',
                    speed: 500
                }
            });
        },

        confirm: function (config) {
            noty({
                layout: config.layout || 'topCenter',
                type: 'confirm',
                theme: 'relax',
                text: config.message || 'Do you want to continue?',
                modal: config.modal !== false,
                animation: {
                    open: 'animated fadeInDownBig',
                    close: 'animated fadeOutUpBig',
                    speed: 500
                },
                buttons: [
                    {
                        addClass: 'btn btn-primary', text: 'Ok', onClick: function($noty) {
                            $noty.close();

                            if (config.callback) {
                                config.callback.call(this, true, config.actionButton);
                                return;
                            }
                        }
                    },
                    {
                        addClass: 'btn btn-danger', text: 'Cancel', onClick: function($noty) {
                            $noty.close();

                            if (config.callback) {
                                config.callback.call(this, false, config.actionButton);
                            }
                        }
                    }
                ]
            });
        }
    };
});
