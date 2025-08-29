const dtQSync =$('#QSync_observations').DataTable({
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

function uuid_event_click (uuid) {
    console.log(uuid);
}


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
            data_json =[{
                flore_a_integrer: 0,
                flore_en_attente: 0,
                faune_a_integrer: 0,
                faune_en_attente: 0,
                cc_a_integrer: 0,
                cc_en_attente: 0,
                color: '#007bff'
            }];
            console.log(data);
            for (const ele in qsync_liste) {
                data_json[0].flore_a_integrer += parseInt(qsync_liste[ele].obs_flore.split(' / ')[0]);
                data_json[0].flore_en_attente += parseInt(qsync_liste[ele].obs_flore.split(' / ')[1]);
                data_json[0].faune_a_integrer += parseInt(qsync_liste[ele].obs_faune.split(' / ')[0]);
                data_json[0].faune_en_attente += parseInt(qsync_liste[ele].obs_faune.split(' / ')[1]);
                data_json[0].cc_a_integrer += parseInt(qsync_liste[ele].obs_cc.split(' / ')[0]);
                data_json[0].cc_en_attente += parseInt(qsync_liste[ele].obs_cc.split(' / ')[1]);
                let rowNode = dtQSync.row.add( [
                    qsync_liste[ele].uuid,
                    qsync_liste[ele].personne, 
                    qsync_liste[ele].obs_faune,
                    qsync_liste[ele].obs_flore,
                    qsync_liste[ele].obs_cc,
                    /* '<span class="">'+qsync_liste[ele].obs_faune+'</span><button style="color:rgba(236, 214, 14, 0.75);" class="btn btn-sm" uuid="'+qsync_liste[ele].uuid+'" id="faune_import_'+qsync_liste[ele].uuid+'"><i class="fas fa-file-import"></i></button>',
                    '<span class="">'+qsync_liste[ele].obs_flore+'</span><button style="color:rgba(39, 196, 60, 0.75);" class="btn btn-sm" uuid="'+qsync_liste[ele].uuid+'" id="flore_import_'+qsync_liste[ele].uuid+'"><i class="fas fa-file-import"></i></button>',
                    '<span class="">'+qsync_liste[ele].obs_cc+'</span><button style="color:rgba(196, 39, 162, 0.75);" class="btn btn-sm" uuid="'+qsync_liste[ele].uuid+'" id="cc_import_'+qsync_liste[ele].uuid+'"><i class="fas fa-file-import"></i></button>', */
                    qsync_liste[ele].update
                    /* ,
                    qsync_liste[ele].version,
                    '<span id="output_'+qsync_liste[ele].uuid+'"></span>' 
                    */
                ] ).node().id = qsync_liste[ele].uuid;
            }
            dtQSync.draw();
            graph_sum('Flore', data_json[0].flore_a_integrer, data_json[0].flore_en_attente, 'rgba(39, 196, 60, 0.75)');
            graph_sum('Faune', data_json[0].faune_a_integrer, data_json[0].faune_en_attente, 'rgba(236, 214, 14, 0.75)');
            graph_sum('CC', data_json[0].cc_a_integrer, data_json[0].cc_en_attente, 'rgba(196, 39, 162, 0.75)');
            change_load();

            //Ajout des evenements sur les boutons d'import faune
            /* $('button[id^="faune_import_"]').click( function () {
                change_load();
                console.log("write " + $(this).attr('id').split('faune_import_')[1]);
                $.ajax({
                    url      : "php/ajax/import_faune.js.php",
                    type     : "POST",
                    data     : {uuid_user: $(this).attr('id').split('faune_import_')[1]},
                    async    : false,
                    dataType : "text",
                    error    : function(request, error) { console.log("not ajax success ");},
                    success  : function(data) {
                        if (data)
                        {   
                            $('#output_'+$(this).attr('id').split('faune_import_')[1]).html(data);
                            change_load();
                        }
                        else
                        {
                            alert('Connexion impossible... Vérifiez votre identifiant et votre mot de passe');
                        }
                    }
                });
            }); */





            }
    });
}
load_qsync();


$('#refresh').on('click', function() {
    $.ajax({
        url      : "php/ajax/nextcloud_search_data.php",
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
            $('#console_observations').empty();
            $('#console_observations').append( "<p>"+data+"</p>" );
            file_scan ();
            }
    });
});
$('#import_flore').on('click', function() {
    $.ajax({
        url      : "php/ajax/imports/imports_flore.js.php",
        data     : {},
        method   : "POST",
        dataType : "text",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);change_load();},
        success  : function(data) {
            change_load();
            $('#console_observations').empty();
            $('#console_observations').append( "<p>"+data+"</p>" );
            file_scan ();
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
            $('#console_observations').empty();
            $('#console_observations').append( "<p>"+data+"</p>" );
            file_scan ();
            }
    });
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
            $('#console_observations').append( "<p>"+data+"</p>" );
            }
    });
}

//////////////////////////////////////////////////////
//Gestion des dom et evenement pour le graphique general
//////////////////////////////////////////////////////
function graph_sum( nom_projet_, a_integrer, integrer, color_) {

    chart_global = new Highcharts.chart('container_' + nom_projet_, {
        chart: {
            type: 'bar',
            height: 120,
            backgroundColor:'#f8f9fa'
        },
        title: {
            text: ``,//Importé / En attente
            align: 'center'
        },
        subtitle: {
            text: `${nom_projet_}`,
            align: 'center'
        },
        plotOptions: {
            series: {
                grouping: false,
                borderWidth: 0
            },
            bar: {
                colorByPoint: true
            }
        },
        legend: {
            enabled: false
        },
        credits: {
            enabled: false
        },
        tooltip: {
            shared: true,
            headerFormat: '<span style="font-size: 15px">{point.point.name}</span><br/>',
            pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: {point.y}<br/>'
        },
        xAxis: {
            type: 'category',
            accessibility: {
                description: ''
            },
            categories: ['En attente', 'Intégrées']
        },
        yAxis: [{
            title: {
                text: '',
            },
            showFirstLabel: false
        }],
        colors: ['rgba(0,0,0,.2)', color_ ],
        series: 
        [
            {
            data: [a_integrer, integrer],
            name: ''
            }
        ],
        exporting: {
            allowHTML: true
        }
    });
}