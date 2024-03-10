$(function() {
    $('h1[data-toggle]').each(function(index, element) {
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
});
