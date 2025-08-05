$("#w_meta_qsync").click( function () {

        console.log("write meta_qsync");
         $.ajax({
            url      : "php/ajax/write_table_from_skeleton.js.php",
            type     : "POST",
            data     : {table_name: $("#w_meta_qsync").attr('id').split('w_')[1]},
            async    : false,
            dataType : "text",
            error    : function(request, error) { console.log("not ajax success ");},
            success  : function(data) {
                if (data)
                {   
                    $('#output').html(data);
                }
                else
                {
                    alert('Connexion impossible... Vérifiez votre identifiant et votre mot de passe');
                }
            }
        }); 

});





