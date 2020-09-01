/*
var imgLink = document.querySelectorAll(".imgLink");
var blackboard = document.getElementById("blackboard");

imgLink.forEach(function(event) {
    event.addEventListener("click", () => {
        blackboard.classList.add("display");
    })
});
*/

var editButton = document.getElementById("editButton");
var blackboardEdit = document.getElementById("blackboardEdit");

editButton.addEventListener("click",openblackboardEdit);

function openblackboardEdit (){
    blackboardEdit.classList.remove("translateX-100");
}

var deletButton = document.getElementById("deletButton");
var delet = document.getElementById("delete");
var blackboardDelete = document.getElementById("blackboardDelete");
 
deletButton.addEventListener("click", deletButtonFunction);

function deletButtonFunction (){
    blackboardDelete.classList.add("display");
};

var backButtonDelete = document.getElementById("backButtonDelete");

backButtonDelete.addEventListener("click", closeblackboardDelete);

function closeblackboardDelete (){
    blackboardDelete.classList.remove("display");

}






