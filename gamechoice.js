
function selectGame(game) {
    let output;
    if(game.classList.contains("button-issue"))
    {
        output = document.getElementById("IssueOutput");
        document.getElementById("Output").style.display = "none";
    }
    else
    {
        output = document.getElementById("Output");
        document.getElementById("IssueOutput").style.display = "none";
    }
    
    output.style.display = "inline-block";
    output.children.namedItem("title").innerHTML = game.name;
}