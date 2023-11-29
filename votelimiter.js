
function checkVoteLimit()
{
    let limiter = document.getElementById("vote-limit");
    let max_votes = limiter.getAttribute("data-votes");
    
    let checkboxes = document.getElementsByTagName("input");
    //that's kinda ooky, but we shouldn't have any non-checkbox inputs on this page...
    
    let areDisabled = false;
    let shouldBeDisabled = false;
    let voteCount = 0;
    
    for (let box of checkboxes)
    {
        if(box.checked)
        {
            voteCount++;
            if(voteCount >= max_votes)
            {
                shouldBeDisabled = true;
            }
        }
        else
        {
            if(box.disabled)
            {
                areDisabled = true;
                //this assumes that all the blank checkboxes will be disabled or enabled at any given time.
                //it SHOULD be a correct assumption.
            }
        }
    }
    
    let display = document.getElementById("current-votes");
    if (display != null)
    {
        display.innerHTML = (max_votes - voteCount);
    }
    
    
    if(areDisabled != shouldBeDisabled)
    {
        for (let box of checkboxes)
        {
            if(!box.checked)
            {
                box.disabled = shouldBeDisabled;
            }
        }
    }
    
    //ohh my gosh. that was so easy!!
}