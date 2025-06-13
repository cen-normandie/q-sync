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
    scrollY: '400px',
    scrollCollapse: true,
    paging: false
});

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
            console.log(data);
            for (const ele in qsync_liste) {
                let rowNode = dtQSync.row.add( [
                    qsync_liste[ele].uuid,
                    qsync_liste[ele].personne, 
                    qsync_liste[ele].nb_faune,
                    qsync_liste[ele].nb_flore,
                    qsync_liste[ele].update,
                    qsync_liste[ele].version
                ] ).node().id = qsync_liste[ele].uuid;
            }
            dtQSync.draw();
            change_load();
            }
    });
}
load_qsync();