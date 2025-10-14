document.addEventListener("DOMContentLoaded", function () {
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
    });
});