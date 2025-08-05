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
                    '<span class="">'+qsync_liste[ele].obs_faune+'</span>',
                    '<span class="">'+qsync_liste[ele].obs_flore+'</span>',
                    '<span class="">'+qsync_liste[ele].obs_cc+'</span>',
                    qsync_liste[ele].update,
                    qsync_liste[ele].version,
                    '<button class="btn btn-primary btn-sm" onclick="uuid_event_click(\''+qsync_liste[ele].uuid+'\')"><i class="fas fa-file-import pr-1"></i> Import</button>'
                ] ).node().id = qsync_liste[ele].uuid;
            }
            dtQSync.draw();
            graph_sum('Flore', data_json[0].flore_a_integrer, data_json[0].flore_en_attente);
            change_load();
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
            reload();
            }
    });
});



//////////////////////////////////////////////////////
//Gestion des dom et evenement pour le graphique general
//////////////////////////////////////////////////////
function graph_sum( nom_projet_, a_integrer, integrer) {
    chart_global = new Highcharts.chart('container_sum', {
        chart: {
            type: 'bar',
            height: 100,
            backgroundColor:'#f8f9fa'
        },
        title: {
            text: ``,//Importé / En attente 
            align: 'center'
        },
        subtitle: {
            text: '',
            align: 'center'
        },
        plotOptions: {
            series: {
                grouping: false,
                borderWidth: 0
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
            }
        },
        yAxis: [{
            title: {
                text: 'nb obs'
            },
            showFirstLabel: false
        }],
        series: [{
            color: 'rgba(0,0,0,.2)',
            pointPlacement: 0,
            data: [a_integrer],
            name: 'En attente'
        }, {
            name: 'Intégrées',
            id: 'main',
            dataLabels: [{
                enabled: true,
                inside: true,
                style: {
                    fontSize: '10px'
                }
            }],
            data: [integrer]
        }],
        exporting: {
            allowHTML: true
        }
    });
}