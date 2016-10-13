var ajax_form_errors_parser = function (response, form) {
    var title = response["statusText"];
    var json = response["responseJSON"];
    var status = response["status"];
    var messages = [];

    if (400 >= status && status < 500) {
        title = json.message;

        $.each(json.errors.errors, function (i, error) {
            messages.push(error);
        });

        $.each(json.errors.children, function (i, children) {
            if (children.errors) {
                if (form) {
                    $('#' + form + '_' + i).closest('.form-group').addClass('has-error');
                }

                $.each(children.errors, function (i, error) {
                    messages.push(error);
                });
            }
        });

        var source = $("#form-errors").html();
        var template = Handlebars.compile(source);

        return template({title: title, errors: messages});
    } else {
        $.notifier.alert({type: 'error', message: json.message || "Unknown error."});

        return '';
    }
};
