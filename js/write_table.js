
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
                 $('#output_').html(data);
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
                    $('#output_').html(data);
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



$('button[id^="gpkg__"]').click( function () {
var r=confirm("Êtes-vous sûr de sûr ?");
if (r==true)
    {
        console.log("write "+$(this).attr("id").replace("gpkg__", "")+".gpkg");
            $.ajax({
            url      : "php/ajax/write_gpkg.js.php",
            type     : "POST",
            data     : {gpkg_name: $(this).attr("id").replace("gpkg__", "")+".gpkg"},
            async    : false,
            dataType : "text",
            error    : function(request, error) { console.log("not ajax success ");},
            success  : function(data) {
                if (data)
                {   
                    $('#output_').html(data);
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

$('button[id^="qgz__"]').click( function () {
var r=confirm("Êtes-vous sûr de sûr ?");
if (r==true)
    {
        console.log("write "+$(this).attr("id").replace("qgz__", "")+".qgz");
            $.ajax({
            url      : "php/ajax/write_qgz.js.php",
            type     : "POST",
            data     : {qgz_name: $(this).attr("id").replace("qgz__", "")+".qgz"},
            async    : false,
            dataType : "text",
            error    : function(request, error) { console.log("not ajax success ");},
            success  : function(data) {
                if (data)
                {   
                    $('#output_').html(data);
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