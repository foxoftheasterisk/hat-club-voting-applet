
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
    let weights = document.getElementById("weightsBox");
    let totalWeight = 0;
    let noIssueWeight = 0;
    
    //yay, debug
    
    //obtain total weights
    for (let game of weights.children) {
        
        let gameWeight = Number(game.value);
        totalWeight += gameWeight;
        
        if(!(game.classList.contains("issue"))) {
            noIssueWeight += gameWeight;
        }
    }
    
    let remainingWeight = Math.floor(Math.random() * totalWeight) + 1;
    
    //we have no guarantee that these loops will run in the same order
    //luckily, we don't need them to! 
    //it's just as random - and just as weighted - no matter what order is chosen
    for (let game of weights.children) {
        let gameWeight = Number(game.value);
        remainingWeight -= gameWeight;
        
        if(remainingWeight <= 0) {
            selectGame(game);
            
            if(game.classList.contains("issue")) {
                break;
            }
            else {
                return;
            }
        }
        //this loop should never terminate, only break (or return)
    }
    
    //we only reach this point if the random function selected a game with an issue
    //therefore, we want to also select a game without an issue!
    remainingWeight = Math.floor(Math.random() * noIssueWeight) + 1;
    
    //there's probably a way to do this with fewer loops, but i don't care enough.
    for (let game of weights.children) {
        
        //no games with issues allowed
        if(game.classList.contains("issue")) {
            continue;
        }
        
        let gameWeight = Number(game.value);
        remainingWeight -= gameWeight;
        
        if (remainingWeight <= 0) {
            selectGameNonExclusive(game);
            
            return;
        }
    }
}