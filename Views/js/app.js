function logout() {
    var xhttp = new XMLHttpRequest();

    xhttp.open("GET", "/lista_tareas/Controllers/logout.php", false);

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            window.location.href = "/lista_tareas/";
        }
    };

    xhttp.send();
}