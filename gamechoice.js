
function selectGame(game, output) {
    if(game.classList.contains("issue")) {
        document.getElementById("Output").style.display = "none";
        if(output === undefined) {
            output = document.getElementById("IssueOutput");
        }
    }
    else {
        document.getElementById("IssueOutput").style.display = "none";
        if(output === undefined) {
            output = document.getElementById("Output");
        }
    }
    
    selectGameNonExclusive(game, output);
}

function selectGameNonExclusive(game, output) {
    if(output === undefined) {
        if(game.classList.contains("issue")) {
            output = document.getElementById("IssueOutput");
        }
        else {
            output = document.getElementById("Output");
        }
    }
    
    output.style.display = "flex";
    output.children.namedItem("title").innerHTML = game.name;
    output.children.namedItem("button").value = game.id;
    
    if(game.classList.contains("issue")) {
        output.children.namedItem("issues").innerHTML = game.children.namedItem("issues").innerHTML;
    }
}

function chooseGame() {
    
}