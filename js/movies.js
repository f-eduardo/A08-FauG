

var addMovies = document.getElementById("addMovies");
var blackboardMovies = document.getElementById("blackboardMovies");

addMovies.addEventListener("click", addMoviesAnimation);

function addMoviesAnimation(){
    blackboardMovies.classList.remove("translateX-100");
}

var backButton = document.getElementById("backButton");

backButton.addEventListener("click", closeMoviesAnimation);

function closeMoviesAnimation(){
    blackboardMovies.classList.add("translateX-100");
}

