
$(document).ready(function() {


    $('#token').on('click', function () {
        const client_id = $('#client_id').val();
        const client_secret = $('#client_secret').val();
        //const client_id = 'sh-05b192bf-0bee-4941-b8a0-a49bbce72ab4';
        //const client_secret = '13EAbEUifvlj3QKWQOdit13qg0s7P8dH';

    $.ajax({
        url: 'copernicus/filtre/generate_token.php',
        method: 'POST',
        data: {
        client_id: client_id ,//$('#client_id').val(),
        client_secret: client_secret //$('#client_secret').val()
        },
        success: function(token) {
            console.log('Token reçu : ' + token);
            $('#access_token').html(token);
        },
        error: function(xhr) {
        console.error('Erreur : ' + xhr.responseText);
        }
    });


    });



});




