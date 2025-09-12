const dtQSync =$('#QSync').DataTable({
    "language": {
        "paginate": {
            "previous": "Préc.",
            "next": "Suiv."
        },
        "search": "Filtrer :",
        "sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
        "sInfo":           "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
        "sInfoEmpty":      "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
        "sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
        "sInfoPostFix":    "",
        "sLoadingRecords": "Chargement en cours...",
        "sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
        "sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau"
    },
    dom: '<"top"<"d-flex justify-content-between align-items-center"f>>t', // export excel -->B :<"top"<"d-flex justify-content-end align-items-center"fB>>t
    scrollY: '200px',
    scrollCollapse: true,
    paging: false,
    columnDefs: [
        {
            target: 0,
            visible: false
        }
    ]
});
//dtQSync.column( 0 ).visible(false);


function load_qsync () {
    change_load('Chargement');
    $.ajax({
        url      : "php/ajax/dashboard.js.php",
        data     : {},
        method   : "POST",
        dataType : "json",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);change_load();},
        success  : function(data) {
            qsync_liste = data ;
            for (const ele in qsync_liste) {
                let rowNode = dtQSync.row.add( [
                    qsync_liste[ele].uuid_nx,
                    qsync_liste[ele].personne, 
                    qsync_liste[ele].observations_gpkg,
                    qsync_liste[ele].n2k_gpkg
                ] ).node().id = qsync_liste[ele].uuid;
            }
            dtQSync.draw();
            change_load();
            }
    });
}
load_qsync();




$('#refresh').on('click', function() {
    $.ajax({
        url      : "php/ajax/nextcloud_search_data.js.php",
        data     : {},
        method   : "POST",
        dataType : "text",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);change_load();},
        success  : function(data) {
            console.log(data);
            change_load();
            window.location.reload();
            }
    });
});

$('#import_faune').on('click', function() {
    $.ajax({
        url      : "php/ajax/imports/imports_faune.js.php",
        data     : {},
        method   : "POST",
        dataType : "text",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);change_load();},
        success  : function(data) {
            change_load();
            $('#console').empty();
            $('#console').append( "<p>"+data+"</p>" );
            file_scan ('console');
            }
    });
});
$('#import_flore').on('click', function() {
    $.ajax({
        url      : "php/ajax/imports/imports_flore_.js.php",
        data     : {},
        method   : "POST",
        dataType : "text",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);change_load();},
        success  : function(data) {
            change_load();
            $('#console').empty();
            $('#console').append( "<p>"+data+"</p>" );
            file_scan ('console');
            }
    });
});

$('#import_cc').on('click', function() {
    $.ajax({
        url      : "php/ajax/imports/imports_carre_contact.js.php",
        data     : {},
        method   : "POST",
        dataType : "text",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);change_load();},
        success  : function(data) {
            change_load();
            $('#console').empty();
            $('#console').append( "<p>"+data+"</p>" );
            file_scan ('console');
            }
    });
});

$('#import_n2k').on('click', function() {
    $.ajax({
        url      : "php/ajax/imports/imports_n2k.js.php",
        data     : {},
        method   : "POST",
        dataType : "text",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);change_load();},
        success  : function(data) {
            change_load();
            $('#console').empty();
            $('#console').append( "<p>"+data+"</p>" );
            file_scan ('console');
            }
    });
});

$('#file_scan').on('click', function() {
    $('#console').empty();
    $('#console').append( "<p>Scan des fichiers en cours...</p>" );
    file_scan ('console');
});

function file_scan (console_name) {
    $.ajax({
        url      : "php/file_scan.php",
        data     : {},
        method   : "POST",
        dataType : "text",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);change_load();},
        success  : function(data) {
            change_load();
            $('#'+console_name).append( "<p class='text-success'>"+data+"</p>" );
            }
    });
}

