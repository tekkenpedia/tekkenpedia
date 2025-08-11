$(function() {
    if (window.localStorage.getItem('character')) {
        $('#character').val(window.localStorage.getItem('character'));
    }

    $('#character').on('change', function(e) {
        window.localStorage.setItem('character', e.target.val());
    });

    $('#submit').on('click', function(e) {
        e.preventDefault();

        let addMove = true;

        let character = $('#character').val();
        if (character === '') {
            alert('You have to fill Character.');
            addMove = false;
        }

        let inputs = $('#inputs').val();
        if (inputs === '') {
            alert('You have to fill Inputs.');
            addMove = false;
        }

        let property = $('#property').val();
        if (property === '') {
            alert('You have to fill Property.');
            addMove = false;
        }

        let blockFramesMin = $('#block-frames-min').val();
        if (blockFramesMin === '') {
            alert('You have to fill Block frames min.');
            addMove = false;
        }

        if (addMove === false) {
            return;
        }

        const workflowInputs = {
            character: character,
            inputs: inputs,
            property: property,
            blockFramesMin: blockFramesMin,
            blockFramesMax: $('#block-frames-max').val()
        };

        $.ajax({
            url: `https://api.github.com/repos/tekkenpedia/tekkenpedia/actions/workflows/add-move.yml/dispatches`,
            method: "POST",
            headers: {
                "Authorization": "token " + window.localStorage.getItem('token'),
                "Accept": "application/vnd.github+json"
            },
            contentType: "application/json",
            data: JSON.stringify({
                ref: "master",
                inputs: workflowInputs
            }),
            success: function() {
                alert('Move added. Thanks!');
                window.location.reload();
            },
            error: function(xhr) {
                alert('Error: ' + (xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : xhr.statusText));
            }
        });
    });
});
