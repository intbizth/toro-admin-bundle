var ajax_form_errors_parser = function (response, form) {
    var title = response["statusText"];
    var json = response["responseJSON"];
    var status = response["status"];
    var messages = [];
    var flattenObject = function(ob) {
        var toReturn = {};

        for (var i in ob) {
            if (!ob.hasOwnProperty(i)) continue;

            if ((typeof ob[i]) == 'object') {
                var flatObject = flattenObject(ob[i]);
                for (var x in flatObject) {
                    if (!flatObject.hasOwnProperty(x)) continue;

                    toReturn[i + '.' + x] = flatObject[x];
                }
            } else {
                toReturn[i] = ob[i];
            }
        }
        return toReturn;
    };

    if (400 >= status && status < 500) {
        title = json.message;

        $.each(json.errors.errors, function (i, error) {
            messages.push(error);
        });

        $.each(flattenObject(json.errors), function (k, v) {
            k = k.replace('.errors.0', '');
            k = k.replace(/\.children\./g, '_');
            k = k.replace('children.', form + '_');

            var $childEl = $('#' + k);
            var $container = $childEl.closest('.form-group').addClass('has-error');

            // addon
            $childEl.next('.selectize-control').addClass('has-error');

            var childName = $container.find('.control-label').text();

            if (!childName.trim()) {
                var ks = k.split('_');
                // TODO: humanize
                childName = ks[ks.length-1];
            }

            messages.push(childName + ' - ' + v);
        });

        var source = $("#form-errors").html();
        var template = Handlebars.compile(source);

        return template({title: title, errors: messages});
    } else {
        $.notifier.alert({type: 'error', message: json.message || "Unknown error."});

        return '';
    }
};
