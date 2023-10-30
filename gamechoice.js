
function selectGame(gameID, gameDisplayName) {
    let output = document.getElementById("Output");
    output.style.display = "inline-block";
    output.innerHTML = gameDisplayName;
}