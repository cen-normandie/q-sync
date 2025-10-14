
$(document).ready(function() {
    $('#suivi').on('change', function() {
        const typeSuivi = $(this).val();

        $('#site').off('input').on('input', function() {
            const query = $(this).val();

            if (query.length >= 2) {
                $.ajax({
                    url: 'filtre/get_sites.php', // à adapter
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        q: query,
                        type_suivi: typeSuivi
                    },
                    success: function(response) {
                        const $list = $('#suggestions');
                        $list.empty();

                        // Adaptation au format { "0": { "value": "...", "label": "..." }, ... }
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
        $('#site').val($(this).text());
        $('#suggestions').hide();
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
