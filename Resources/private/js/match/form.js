$(function () {
    $(document).on('change', '.match-personal-embed .personals', function () {
        var $el = $(this).closest('.match-personal-embed');
        var $personal = $el.find('.personal');
        var $shirtNumber = $el.find('.shirt-number');
        var $avatar = $el.find('.avatar');
        var position = $el.find('.position')[0];
        var clubKey = $el.data('club');
        var personals = window["personalData"][clubKey];

        var id = $(this).val();
        var player = personals['id-' + id];

        if ($shirtNumber.length) {
            $shirtNumber.val(player["no"]);
        }

        $personal.val(player["id"]);

        $avatar.fadeOut('fast', function () {
            $(this).attr('src', player["avatar"]);
        }).fadeIn('fast');

        position.selectize.setValue(player["position"]);
    });

    $(document).on('change', '#toro_match_event_embed_type', function () {
        var $this = $(this);
        var $relatedPlayersContainer = $this.closest('.adder-embed').find('.related-players');
        var selectizeRelated = $relatedPlayersContainer.find('select').data('selectize');

        $this.data('selectize').revertSettings.$children.each(function () {
            if ($this.val() !== $(this).val()) {
                return;
            }

            var labelRelated = $(this).data('require-related-player');

            if (labelRelated) {
                $relatedPlayersContainer.show();
                $relatedPlayersContainer.find('label').html(labelRelated);
            } else {
                $relatedPlayersContainer.hide();
                selectizeRelated.setValue("");
            }
        });
    });
});

window['onClubSelectionChange'] = function (options, value, panel) {
    var item = options[value],
        $panel = $(panel),
        logo = $.UI.avatar(),
        img = new Image()
    ;

    if (item['item']['_links'] && item['item']['_links']['logo_100x100']) {
        logo = item['item']['_links']['logo_100x100']['href'];
    }

    $panel.find('.club-text').text(item.text);

    img.src = logo;
    img.onload = function () {
        $panel.find('.club-logo').fadeIn('fast', function () {
            this.src = logo;
        });
    };
};

window['onHomeClubChange'] = function (value) {
    window['onClubSelectionChange'](this.options, value, '#home-club-panel');
};

window['onAwayClubChange'] = function (value) {
    window['onClubSelectionChange'](this.options, value, '#away-club-panel');
};
