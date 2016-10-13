window.Select2ChainSetup = function (selector, scope) {
    $(selector, scope).each(function () {
        var $target, chains, loader, options, id, remote;

        $target = $(this);

        id = $target.attr('id');

        // read select2 option
        options = $target.data('chooser') || {};

        // default option
        options = $.extend({
            //minimumResultsForSearch: Infinity
        }, options);

        // read chain option then remove from standard select option
        if (options.chains) {
            chains = options.chains;
            delete options.chains;
        }

        $target.data('__chains__', typeof chains === 'string' ? [chains] : chains);
        $target.data('__depend__', options.depend);
        $target.data('__no_filter__', options.no_filter);
        $target.data('__autoload__', options.autoload);

        if (options.remote) {
            // short hand config remote only with url (no other options)
            if (typeof options.remote === 'string') {
                options.remote = {
                    url: options.remote
                };
            }

            // remove remote key from standard option
            remote = options.remote;
            delete options.remote;

            // when remote call
            loader = function (me, opt, url) {
                if (!opt.value) {
                    opt.value = 'id';
                }

                if (!opt.text) {
                    opt.text = 'name';
                }

                // sometime we need to append data
                if (typeof opt.clearOnLoad === 'undefined') {
                    opt.clearOnLoad = true;
                }

                if (opt.clearOnLoad) {
                    //me.remove('option[value]');
                }

                // i'm depend on other
                var depend = me.data('__depend__');

                if (depend) {
                    var $depend = $('#' + depend);

                    if (!$depend.val()) {
                        return false;
                    } else {
                        // search and replace @value
                    }
                }

                //me.select2('enable', false);

                return {
                    type: 'GET',
                    url: url || opt.uri,
                    complete: function () {
                        //me.select2('enable', true);
                    },
                    error: function (res) {},
                    dataType: 'json',
                    delay: 250,
                    cache: true,

                    // allow the chaining to remote filter?
                    //minimumResultsForSearch: url && me.data('__no_filter__') ? Infinity : 20,

                    // select2 has no programing api (lack-off)
                    // use this to trig ajax load when user select the dropdown
                    // note that `url` mean currently is chaining
                    //minimumInputLength: url && me.data('__autoload__') ? 1 : 3,

                    data: function (param) {
                        return $.extend(opt.data || {}, {
                            keyword: param.term,
                            page: param.page
                        })
                    },
                    processResults: function (res/*, param*/) {
                        var data, items = [];

                        if (res._embedded) {
                            data = res._embedded.items;
                        } else {
                            data = res;
                        }

                        $.each(data, function (i, it) {
                            return items.push({
                                text: opt['label_parse'] != undefined ? window[opt['label_parse']](it) : it[opt.text],
                                value: it[opt.value]
                            });
                        });

                        return {
                            results: items,
                            /*pagination: {
                                more: res.page < res.pages
                            }*/
                        };
                    }
                };
            };

            var ajax = loader($target, remote);

            if (ajax) {
                options.ajax = ajax;
            } else {
                // default option if no ajax
                options = $.extend({
                    //minimumResultsForSearch: Infinity,
                    //minimumInputLength: undefined
                }, options);
            }
        }

        $target.data('__remote__', remote);
        $target.data('__loader__', loader);
        $target.data('__options__', options);

        // first init
        $target.select2(options);

        delete options.no_filter;
        delete options.autoload;
        delete options.depend;

        if (chains) {
            $target.on('change', function (e) {
                var $chaining, j, len, ref1, $me = $(this), remote, options, value;

                ref1 = $me.data('__chains__');

                // other selects
                for (j = 0, len = ref1.length; j < len; j++) {
                    $chaining = $('#' + ref1[j]);
                    options = $chaining.data('__options__');
                    remote = $chaining.data('__remote__');
                    value = $me.val();

                    if (remote) {
                        // todo: allow to accept complex parameters with query_builder like sf
                        var url = remote.url.replace(/(@|%40)value/g, value);
                        options.ajax = $chaining.data('__loader__')($chaining, remote, url);

                        if (value) {
                            $chaining.select2(options);
                        } else {
                            $chaining.remove('option[value]');
                        }
                    } else {
                        console.warn('no remote configured for chain');
                    }
                }
            });
        }
    });
};
