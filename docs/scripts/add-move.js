$(function() {
    if (window.localStorage.getItem('character')) {
        $('#character').val(window.localStorage.getItem('character'));
    }

    // Fonction utilitaire avec jQuery pour décoder les entités HTML
    function decodeHtmlEntities(str) {
        return $('<textarea/>').html(str).val();
    }

    function refreshSections() {
        let selectedOption = $('#character option:selected');
        let sectionsData = selectedOption.attr('data-sections');
        let sections = [];
        if (sectionsData) {
            // Décodage des entités HTML avant JSON.parse
            let decodedSectionsData = decodeHtmlEntities(sectionsData);
            try {
                sections = JSON.parse(decodedSectionsData);
            } catch (err) {
                sections = [];
            }
        }

        let $section = $('#section');
        $section.empty();
        sections.forEach(function(section) {
            $section.append(
                $(
                    '<option>',
                    {
                        value: section,
                        text: section
                    }
                )
            );
        });
    }

    $('#character').on('change', function(e) {
        window.localStorage.setItem('character', $(this).val());

        refreshSections();
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
            section: $('#section').val(),
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

    refreshSections();
});
