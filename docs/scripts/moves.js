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

    $('[data-filter-used]').on('click', function() {
        let $btn = $(this);
        let filter = $btn.data('filter-used');

        $('[data-filter-used]').removeClass('active');
        $btn.addClass('active');

        $('[data-used]').each(function() {
            let $move = $(this);
            if (filter === 'all' || $move.data('used') === filter) {
                $move.show();
            } else {
                $move.hide();
            }
        });

        // Show all sections first to reset state
        $('h1[data-toggle-id], h2[data-toggle-id]').show();
        $('h1[data-toggle-id], h2[data-toggle-id]').each(function() {
            let id = $(this).data('toggle-id');
            $('div[data-toggle=' + id + ']').show();
        });

        // Hide sections (h2 then h1) that have no visible moves
        $('h2[data-toggle-id]').each(function() {
            let $header = $(this);
            let id = $header.data('toggle-id');
            let $content = $('div[data-toggle=' + id + ']');
            if ($content.find('[data-used]:visible').length === 0) {
                $header.hide();
                $content.hide();
            }
        });

        $('h1[data-toggle-id]').each(function() {
            let $header = $(this);
            let id = $header.data('toggle-id');
            let $content = $('div[data-toggle=' + id + ']');
            if ($content.find('[data-used]:visible').length === 0) {
                $header.hide();
                $content.hide();
            }
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
