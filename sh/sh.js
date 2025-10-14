
$(document).ready(function() {
    $('#suivi').on('change', function() {
        const typeSuivi = $(this).val();

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
        const id_Site = $(this).data('value').split(' - ')[0];
        const nom_complet_site = $(this).data('value');
        const type_suivi = $('#suivi').val();
        $('#site').val(nom_complet_site);
        $('#suggestions').hide();

        // Charger les années associées au site sélectionné
        $.ajax({
            url: 'sh/filtre/get_annees.php',
            method: 'POST',
            dataType: 'json',
            data: {
                site: id_Site,
                type_suivi: type_suivi,
            },
            success: function(data) {
                const $select = $('#annee');
                $select.empty();
                if (Array.isArray(data) && data.length > 0) {
                    data.forEach(function(annee) {
                        $select.append('<option value="' + annee + '">' + annee + '</option>');
                    });
                } else {
                    $select.append('<option value="">Aucune année disponible</option>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Erreur chargement années :', error);
            }
        });
    });
});










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
