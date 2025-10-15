
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

function clear(bool) {
    if (bool) {
        document.getElementById("suivi").selectedIndex = 0;
    }
    document.getElementById("site").value = "";
    document.getElementById("suggestions").innerHTML = "";
    document.getElementById("annee").innerHTML = "";
    document.getElementById("releve_id").innerHTML = "";
}






    /* const type_suivi = document.getElementById("suivi");
    const siteSelect = document.getElementById("site");
    const anneeSelect = document.getElementById("annee");
    const plotSelect = document.getElementById("plot");
    const transectSelect = document.getElementById("transect");
    const idReleveSelect = document.getElementById("releve_id");

    function fetchOptions(endpoint, params, targetSelect) {
        fetch(endpoint, {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams(params)
        })
        .then(response => response.json())
        .then(data => {
            targetSelect.innerHTML = "";
            data.forEach(item => {
                const option = document.createElement("option");
                option.value = item.value;
                option.textContent = item.label;
                targetSelect.appendChild(option);
            });
        });
    }
    type_suivi.addEventListener("change", function () {
        fetchOptions("filtre/get_sites.php", { type_suivi: type_suivi.value }, siteSelect);
        anneeSelect.innerHTML = "";
        plotSelect.innerHTML = "";
        transectSelect.innerHTML = "";
        idReleveSelect.innerHTML = "";
    });
    siteSelect.addEventListener("change", function () {
        fetchOptions("filtre/get_annees.php", { site: siteSelect.value }, anneeSelect);
    });

    anneeSelect.addEventListener("change", function () {
        fetchOptions("filtre/get_plots.php", { site: siteSelect.value, annee: anneeSelect.value }, plotSelect);
    });

    plotSelect.addEventListener("change", function () {
        fetchOptions("filtre/get_transects.php", {
            site: siteSelect.value,
            annee: anneeSelect.value,
            plot: plotSelect.value
        }, transectSelect);
    });

    transectSelect.addEventListener("change", function () {
        fetchOptions("filtre/get_ids.php", {
            site: siteSelect.value,
            annee: anneeSelect.value,
            plot: plotSelect.value,
            transect: transectSelect.value
        }, idReleveSelect);
    }); */
