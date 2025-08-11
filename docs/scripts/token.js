$(function() {
    $('#token').val(window.localStorage.getItem('token'));

    $('#submit').on('click', function(e) {
        e.preventDefault();

        let token = $('#token').val();
        if (token === '') {
            alert('You have to fill Token.');
        }

        window.localStorage.setItem('token', token);
        alert('Token saved.');
    });
});
