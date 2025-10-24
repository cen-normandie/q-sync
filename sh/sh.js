
$(document).ready(function() {
    $('#suivi').on('change', function() {
        const typeSuivi = $(this).val();
        //Releve Phyto
        if (typeSuivi === 'releve_phyto') {
            //
        } else if (typeSuivi === 'carre_contact') {
            //
        }
        clear(false);


        $('#site').off('input').on('input', function() {
            const query = $(this).val();
            if (query.length >= 2) {
                $.ajax({
                    url: 'sh/filtre/get_sites.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        q: query,
                        type_suivi: typeSuivi
                    },
                    success: function(response) {
                        const $list = $('#suggestions');
                        $list.empty();
                        const entries = Object.values(response);

                        if (entries.length > 0) {
                            entries.forEach(function(item) {
                                $list.append('<li data-value="' + item.value + '">' + item.label + '</li>');
                            });
                            $list.show();
                        } else {
                            $list.hide();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Erreur AJAX :', error);
                        $('#suggestions').hide();
                    }
                });
            } else {
                $('#suggestions').hide();
            }
        });
    });

    $('#suggestions').on('click', 'li', function() {
        const id_site_ = $(this).data('value').split(' - ')[0];
        const nom_complet_site = $(this).data('value');
        const type_suivi_ = $('#suivi').val();
        $('#site').val(nom_complet_site);
        $('#suggestions').hide();

        // Charger les années associées au site sélectionné
        $.ajax({
            url: 'sh/filtre/get_annees.php',
            method: 'POST',
            dataType: 'json',
            data: {
                site: id_site_,
                type_suivi: type_suivi_
            },
            success: function(response) {
                const $select_annee = $('#annee');
                $select_annee.empty();

                const annees = Object.values(response);

                if (annees.length > 0) {
                    annees.forEach(function(item) {
                        $select_annee.append('<option value="' + item.value + '">' + item.label + '</option>');
                    });
                } else {
                    $select_annee.append('<option value="">Aucune année disponible</option>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Erreur chargement années :', error);
            }
        });
    });


    $('#annee').on('change', function () {
        const id_site = $('#site').val().split(' - ')[0];
        const type_suivi = $('#suivi').val();
        const selected_annees = $(this).val(); // tableau des années sélectionnées
        console.log('Années sélectionnées :', selected_annees);
        $.ajax({
            url: 'sh/filtre/get_id_releve.php', 
            type: 'POST',
            data: { 
                site: id_site,
                type_suivi: type_suivi,
                annees: selected_annees 
            },
            dataType: 'json',
            // Exemple de réponse JSON attendue :
            /*
            {"0": {"value": "R1__11","label": "R1__11"}, "1": {"value": "R1__12","label": "R1__12"}}
            */
            /* success: function (response) {
                $('#releve_id').empty();
                Object.keys(response).forEach(function (key) {
                    const item = response[key];
                    $('#releve_id').append(
                        $('<option>', {
                            value: item.value,
                            text: item.label
                        })
                    );
                });
            }, */
            success: function(response) {
                const $select_releve_ids = $('#releve_id');
                $select_releve_ids.empty();

                const releve_ids = Object.values(response);

                if (releve_ids.length > 0) {
                    releve_ids.forEach(function(item) {
                        $select_releve_ids.append('<option value="' + item.value + '">' + item.label + '</option>');
                    });
                } else {
                    $select_releve_ids.append('<option value="">Aucun relevé disponible</option>');
                }
            },
            error: function () {
            alert('Erreur lors de la récupération des relevés.');
            }
        });
    });

    $('#graphs').on('click', function() {
        console.log('Chargement des graphiques...');
    });

    $('#clear_site').on('click', function() {
        clear(true)
    });

});

const dtInd =$('#IndicateursEcologiques').DataTable({
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
    dom: '<"top"<"d-flex justify-content-between align-items-center"fB>>t', // export excel -->B :<"top"<"d-flex justify-content-end align-items-center"fB>>t
    buttons: [
        { 
        extend: 'excel', 
        text:'Excel',
        className: 'btn btn-success my-2'
        }
        ],
    scrollY: '300px',
    scrollCollapse: true,
    paging: false,
    columnDefs: [
        {
            target: 0,
            visible: false
        }
    ]
});


function clear(bool) {
    if (bool) {
        document.getElementById("suivi").selectedIndex = 0;
    }
    document.getElementById("site").value = "";
    document.getElementById("suggestions").innerHTML = "";
    document.getElementById("annee").innerHTML = "";
    document.getElementById("releve_id").innerHTML = "";
}



$('#graphs').on('click', function() {


    $("#hide").addClass("d-none");
    $("#outview").removeClass("d-none");

    const type_suivi = $('#suivi').val();
    const site = $('#site').val().split(' - ')[0];
    const annees = $('#annee').val(); // tableau des années sélectionnées
    const releve_ids = $('#releve_id').val(); // tableau des relevés sélectionnés
    console.log('Type de suivi :', type_suivi);
    console.log('Site :', site);
    console.log('Années :', annees);
    console.log('Relevés :', releve_ids);
    //indices écologiques
    dtInd.clear().draw();
    $.ajax({
        url: 'sh/analyse/calcul_ind_eco.js.php',
        type: 'POST',
        data: {
            annees: annees,
            plots: releve_ids,
            site: site,
            type_suivi: type_suivi
        },
        dataType: 'json',
        success: function(data) {
            console.log(data);

            data.forEach(function(row) {
                dtInd.row.add([
                    row.annee,
                    row.id_releve,
                    row.site,
                    row.richesse_specifique,
                    parseFloat(row.indice_shannon).toFixed(2),
                    parseFloat(row.equitabilite).toFixed(2)
                ]).draw();
            });

        }
    });
    //Appartenance phyto
    fetchAppartenance(annees, releve_ids, site, type_suivi)
        .done(function(data) {
            console.log('Données d\'appartenance reçues :', data);
            buildChart(data);
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.error('Erreur lors de la récupération des données d\'appartenance :', textStatus, errorThrown);
        });
    
});




function fetchAppartenance(annees, releve_ids, site, type_suivi) {
    return $.ajax({
        url: 'sh/analyse/calcul_appartenance.js.php',
        method: 'POST',
        dataType: 'json',
        data: {
            annees: annees,
            plots: releve_ids,
            site: site,
            type_suivi: type_suivi
        }
    });
}


function buildChart(data) {
    // Regrouper par année
    const grouped = {};
    data.forEach(row => {
        if (!grouped[row.annee]) grouped[row.annee] = [];
        grouped[row.annee].push(row);
    });

    const categories = Object.keys(grouped);
    const syntaxons = [...new Set(data.map(d => d.appartenance_phyto))];

    // Définir une palette de base pour le gradient
        const gradientColors = [
            
            '#630b0bdf', // rouge
            '#863838df', // rouge-orange
            '#956232df', // orange
            '#f5e500df', // jaune
            '#a8c32fdf', // jaune-vert
            '#4aa832df', // vert
            '#32f5cbdf', // vert-bleu
            '#32a8f5df', // bleu-vert
            '#3263f5df', // bleu
            '#8b32f5df', // violet
            '#dc6fe6df', // rose clair
            '#f5329ddf', // rose foncé
            '#50331Bdf', // brun
            '#808080df',  // gris
            '#000000df'  // noir
        ];


    const series = syntaxons.map((syntaxon, index) => {
            const colorIndex = index % gradientColors.length;
            return {
                name: syntaxon,
                data: categories.map(annee => {
                    const item = grouped[annee].find(d => d.appartenance_phyto === syntaxon);
                    return item ? parseFloat(item.pourcentage) : 0;
                }),
                color: {
                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                    stops: [
                        [0, gradientColors[colorIndex]],
                        [1, Highcharts.color(gradientColors[colorIndex]).brighten(-0.3).get()]
                    ]
                },
                stack: 'phyto',
                borderWidth: 0,
            };
        });

    const chart = Highcharts.chart('appartenance_graph', {
        chart: {
            height: 600,
            type: 'column'
        },
        title: {
            text: 'Profil d’appartenance phyto par année'
        },
        xAxis: {
            categories: categories,
            title: { text: 'Année' }
        },
        yAxis: {
            min: 0,
/*             scrollbar: {
            enabled: true
            }, */
            title: { text: 'Pourcentage (%)' },
            stackLabels: {
                enabled: true,
                formatter: function () {
                    return this.total + '%';
                }
            }
        },
        tooltip: {
            shared: true,
            pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}%</b><br/>'
        },
        plotOptions: {
            column: {
                stacking: 'percent'
            }
        },
        legend: {
            itemWidth: 400
        },
/*         exporting: {
            enabled: true,
            buttons: {
                contextButton: {
                    menuItems: ['downloadPNG', 'downloadJPEG', 'downloadPDF', 'downloadSVG']
                }
            }
        }, */
        series: series
    });
}


