let timeout;

/* Positionne le dropdown sous l'input */
function positionDropdown() {
    const input = document.getElementById("searchInput");
    const result = document.getElementById("result");

    const rect = input.getBoundingClientRect();

    result.style.left = rect.left + "px";
    result.style.top = (rect.bottom + window.scrollY) + "px";
    result.style.width = rect.width + "px";
}

/* Recherche temps réel */
document.getElementById("searchInput").addEventListener("input", function(e) {
    const query = e.target.value.trim();
    const result = document.getElementById("result");

    clearTimeout(timeout);

    if (!query) {
        result.style.display = "none";
        return;
    }

    timeout = setTimeout(() => {
        fetch("recherche.php?nom=" + encodeURIComponent(query))
            .then(res => res.json())
            .then(data => {

                positionDropdown();

                if (data.length === 0) {
                    result.innerHTML = "<p>Aucun résultat</p>";
                } else {
                    result.innerHTML = data.map(item =>
                        `<p>${item}</p>`
                    ).join("");
                }

                result.style.display = "block";
            });

    }, 300);
});

/* Repositionnement dynamique */
window.addEventListener("scroll", positionDropdown);
window.addEventListener("resize", positionDropdown);

/* Fermer si clic ailleurs */
document.addEventListener("click", function(e) {
    if (!document.querySelector(".search-container").contains(e.target)) {
        document.getElementById("result").style.display = "none";
    }
});