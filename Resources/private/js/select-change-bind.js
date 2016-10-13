$(function () {
    $(document).on('change', '[data-bind-to]', function (e) {
        var $this = $(this);
        var $scope = document;

        if ($this.data('bind-scope')) {
            $scope = $this.closest($this.data('bind-scope'));
        }

        var selectize = $this.data('selectize');
        var value = selectize.getValue();
        var bindTo = $this.data('bind-to');
        var bindCheck = $this.data('bind-check');

        var binding = function ($option) {
            if (value === $option.val()) {
                var bindValue = $option.data('bind-value');

                if (bindCheck) {
                    var $checkValue = $(bindCheck, $scope);

                    if (bindValue && $checkValue.val() === bindValue.toString()) {
                        alert("Can't select this option.");
                        selectize.setValue("");
                        return;
                    }
                }

                $(bindTo, $scope).val(bindValue);
            }
        };

        selectize.revertSettings.$children.each(function () {
            var $this = $(this);

            if ($this.is('optgroup')) {
                $this.find('option').each(function () {
                    binding($(this));
                });
            } else {
                binding($this);
            }
        })
    });
});
