const REPO_OWNER = "tekkenpedia";
const REPO_NAME = "tekkenpedia";

$(function() {
    $('#token').val(window.localStorage.getItem('token'));

    $('#submit').on('click', function(e) {
        e.preventDefault();

        let addMove = true;

        let token = $('#token').val();
        if (token === '') {
            alert('You have to fill Token.');
            addMove = false;
        }
        window.localStorage.setItem('token', token);

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

        const body = JSON.stringify(
            {
                'inputs': inputs,
                'visibility': {
                    'punish': true
                },
                'property': property,
                'frames': {
                    'startup': {
                        'min': 7777
                    },
                    'block': {
                        'min': blockFramesMin,
                        'max': $('#block-frames-max').val(),
                    },
                    'normal-hit': 7777,
                    'counter-hit': 7777
                }
            },
            null,
            4
        );

        $.ajax({
            url: `https://api.github.com/repos/${REPO_OWNER}/${REPO_NAME}/issues`,
            method: "POST",
            headers: {
                "Authorization": "token " + token,
                "Accept": "application/vnd.github+json"
            },
            contentType: "application/json",
            data: JSON.stringify({
                title: "Add move for " + character,
                body: '```json' + "\n" + body + "\n" + '```'
            }),
            success: function(response) {
                alert('Move added. Thanks!');
                window.location.reload();
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseJSON.message);
            }
        });
    });
});
