/* $("#w_meta_qsync").click( function () {

        console.log("write meta_qsync");
         $.ajax({
            url      : "php/ajax/write_table_from_skeleton.js.php",
            type     : "POST",
            data     : {table_name: $(this).attr('id').split('w_')[1]},
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

}); */
$('button[id^="w_"]').click( function () {
var r=confirm("Êtes-vous sûr de sûr ?");
if (r==true)
  {
     change_load();
     console.log("write " + $(this).attr('id').split('w_')[1]);
     $.ajax({
         url      : "php/ajax/write_table_from_skeleton.js.php",
         type     : "POST",
         data     : {table_name: $(this).attr('id').split('w_')[1]},
         async    : false,
         dataType : "text",
         error    : function(request, error) { console.log("not ajax success ");},
         success  : function(data) {
             if (data)
             {   
                 $('#output').html(data);
                 change_load();
             }
             else
             {
                 alert('Connexion impossible... Vérifiez votre identifiant et votre mot de passe');
             }
         }
     });
  }
else
  {

  }
});

$("#gpkg").click( function () {

        console.log("write gpkg");
         $.ajax({
            url      : "php/ajax/write_gpkg.js.php",
            type     : "POST",
            data     : {},
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
                change_load();
            }
        }); 

});





