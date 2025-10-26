$(function() {
    $('h1[data-toggle],h2[data-toggle]').each(function(index, element) {
        let $element = $(element);
        let id = $(element).data('toggle-id');

        $element.on('click', function() {
            let $div = $('div[data-toggle=' + id + ']');
            $div.toggle({
                duration: 0,
                complete: function() {
                    let $icon = $('i[data-toggle-icon=' + id + ']');
                    if ($(this).css('display') === 'none') {
                        $icon
                            .removeClass('bi-toggle-on')
                            .addClass('bi-toggle-off');
                    } else {
                        $icon
                            .removeClass('bi-toggle-off')
                            .addClass('bi-toggle-on');
                    }
                }
            });
        });
    });

    $('i[data-copy-url]').each(function(index, element) {
        let $element = $(element);

        $element.on('click', function(event) {
            copyToClipboard(
                window.location.origin + '/' + $element.data('copy-url'),
                'Link copied',
                event
            );
        });
    });

    $('i[data-copy-move-id]').each(function(index, element) {
        let $element = $(element);

        $element.on('click', function(event) {
            copyToClipboard($element.data('copy-move-id'), 'Move id copied', event);
        });
    });

    function copyToClipboard(toCopy, confirmationMessage, event) {
        navigator.clipboard.writeText(toCopy);

        let $tooltip = $("#tooltip");
        $tooltip.text(confirmationMessage);

        $tooltip.css({
            top: event.pageY + 30 - window.scrollY,
            left: event.pageX - ($tooltip.width() / 2)
        });

        $tooltip.show();
        setTimeout(
            function(){
                $tooltip.fadeOut();
            },
            1000
        );
    }
});
