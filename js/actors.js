

var addActors = document.getElementById("addActors");
var blackboardActors = document.getElementById("blackboardActors");

addActors.addEventListener("click", addActorsAnimation);

function addActorsAnimation(){
    blackboardActors.classList.remove("translateX-100");
}

var backButton = document.getElementById("backButton");

backButton.addEventListener("click", closeMoviesAnimation);

function closeMoviesAnimation(){
    blackboardActors.classList.add("translateX-100");
}


