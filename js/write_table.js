
$('button[id^="w_"]').click( function () {
var r=confirm("Êtes-vous sûr de sûr ?");
if (r==true)
  {
     change_load();
     console.log("write table in gpkg : " + $(this).attr('gpkg')+ ' table_name '+$(this).attr('table'));
     $.ajax({
         url      : "php/ajax/write_table_from_skeleton_2.js.php",
         type     : "POST",
         data     : {
            table_name: $(this).attr('table'), 
            gpkg_name : $(this).attr('gpkg')},
         async    : false,
         dataType : "text",
         error    : function(request, error) { console.log("not ajax success ");},
         success  : function(data) {
             if (data)
             {   
                 $('#output_').html(data);
                 file_scan ();
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
                    file_scan ();
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

$('#delete_file').click( function () {
    var r=confirm("Êtes-vous sûr de vouloir supprimer ce fichier ?");
    if (r==true)
    {
        console.log("delete file: " + $('#delete_file_name').val());
        $.ajax({
            url      : "php/ajax/delete_file.js.php",
            type     : "POST",
            data     : {file_name: $('#delete_file_name').val()},
            async    : false,
            dataType : "text",
            error    : function(request, error) { console.log("not ajax success ");},
            success  : function(data) {
                if (data)
                {
                    $('#output_').html(data);
                    file_scan ();
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
                    file_scan ();
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



function file_scan () {
    $.ajax({
        url      : "php/file_scan.php",
        data     : {},
        method   : "POST",
        dataType : "text",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);change_load();},
        success  : function(data) {
            change_load();
            $('#output_').append("<p>"+data+"</p>");
            }
    });
}