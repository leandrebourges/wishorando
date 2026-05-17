<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/barre_recherche.css">
</head>
<body>

<nav class="header">
    <a class="active" href="page_recherche.php" id="main"><i class="fa fa-home fa-fw" aria-hidden="true"></i> Wishorando</a>
    <a class="active" href="page_creation_sortie.php"><i class="fa fa-pencil fa-fw" aria-hidden="true"></i> Créer une nouvelle randonnée</a>
    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Rechercher...">
        <button onclick="handleSearch()"><i class="fa fa-search"></i></button>
        <div id = "result"></div>
    </div>
</nav>

<script>
    let timeout;

    function handleSearch() {
        const query = document.getElementById("searchInput").value.trim();
        const result = document.getElementById("result");

        if (!query) {
            result.textContent = "Veuillez entrer un élément à rechercher.";
            return;
        }

        fetch("php_requests/recherche.php?nom=" + encodeURIComponent(query))
            .then(res => res.json())
            .then(data => {
                if (data.length === 0) {
                    result.textContent = "Aucun résultat.";
                } else {
                    result.innerHTML = data.map(item => `<p><a href="detail_rando.php?id=${item.id}">${item.nom} (${item.distance} km)</a></p>`).join("");
                }
                result.style.display = "block";
            });
    }

    document.getElementById("searchInput").addEventListener("input", function() {
        clearTimeout(timeout);
        timeout = setTimeout(handleSearch, 300);
    });

    document.addEventListener("click", function(e) {
        if (!document.querySelector(".search-container").contains(e.target)) {
            document.getElementById("result").style.display = "none";
        }
    });
</script>

</body>
</html>