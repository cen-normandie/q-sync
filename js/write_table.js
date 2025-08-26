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
$('button[id^="wobs_"]').click( function () {
var r=confirm("Êtes-vous sûr de sûr ?");
if (r==true)
  {
     change_load();
     console.log("write " + $(this).attr('id').split('wobs_')[1]);
     $.ajax({
         url      : "php/ajax/write_table_from_skeleton.js.php",
         type     : "POST",
         data     : {table_name: $(this).attr('id').split('wobs_')[1]},
         async    : false,
         dataType : "text",
         error    : function(request, error) { console.log("not ajax success ");},
         success  : function(data) {
             if (data)
             {   
                 $('#output_observations').html(data);
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

$("#observations_gpkg").click( function () {
var r=confirm("Êtes-vous sûr de sûr | Ceci effacera toutes les données dans les geopackages ?");
if (r==true)
  {
        console.log("write gpkg");
         $.ajax({
            url      : "php/ajax/write_gpkg.js.php",
            type     : "POST",
            data     : {gpkg_name: "observations.gpkg"},
            async    : false,
            dataType : "text",
            error    : function(request, error) { console.log("not ajax success ");},
            success  : function(data) {
                if (data)
                {   
                    $('#output_observations').html(data);
                }
                else
                {
                    alert('Connexion impossible... Vérifiez votre identifiant et votre mot de passe');
                }
                change_load();
            }
        }); 
  }
else
  {

  }

});


$("#n2k_gpkg").click( function () {
var r=confirm("Êtes-vous sûr de sûr | Ceci effacera toutes les données dans les geopackages ?");
if (r==true)
  {
        console.log("write gpkg");
         $.ajax({
            url      : "php/ajax/write_gpkg.js.php",
            type     : "POST",
            data     : {gpkg_name: "n2k.gpkg"},
            async    : false,
            dataType : "text",
            error    : function(request, error) { console.log("not ajax success ");},
            success  : function(data) {
                if (data)
                {   
                    $('#output_n2k').html(data);
                }
                else
                {
                    alert('Connexion impossible... Vérifiez votre identifiant et votre mot de passe');
                }
                change_load();
            }
        }); 
  }
else
  {

  }

});

$("#o_tax_qgz").click( function () {
var r=confirm("Êtes-vous sûr de sûr ?");
if (r==true)
    {
        console.log("write o_tax.qgz");
            $.ajax({
            url      : "php/ajax/write_o_tax.qgz.js.php",
            type     : "POST",
            data     : {qgz_name: "o_tax.qgz"},
            async    : false,
            dataType : "text",
            error    : function(request, error) { console.log("not ajax success ");},
            success  : function(data) {
                if (data)
                {   
                    $('#output_observations').html(data);
                }
                else
                {
                    alert('Connexion impossible... Vérifiez votre identifiant et votre mot de passe');
                }
                change_load();
            }
        }); 
    }
else
    {

    }
});
