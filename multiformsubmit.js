
async function submitAllForms(submitButton) {
    let buttonText = submitButton.innerHTML;
    submitButton.innerHTML = "Submitting..."
    submitButton.disabled = true;
    
    let forms = document.getElementsByTagName("form");
    let isError = false;
    let submissions = [];
    
    for (let form of forms)
    {
        if(! form.checkValidity()) {
            form.reportValidity();
            isError = true;
            continue;
        }
        
        //start each form submission in parallel
        let request = fetch(form.action, {
            method: form.method,
            header: { "content-type": form.enctype },
            body: new FormData(form), 
        });
        //grey magic: i think i know what this call is doing, but i only kinda know why
        
        submissions.push( { request: request, form: form });
    }
    
    //once all requests are started, we wait for them to end
    //so we can confirm they worked
    for (let submission of submissions)
    {
        let response;
        try {
            response = await submission.request;
        }
        catch (error) {
            let errorDisplay = document.createElement("p");
            errorDisplay.innerHTML = "Error!! " + error;
            submission.form.appendChild(errorDisplay);
            isError = true;
        }
        
        if(response.ok)
        {
            let placeholder = document.createElement("div");
            placeholder.className = submission.form.className;
            placeholder.innerHTML = "Submitted!";
            submission.form.parentNode.replaceChild(placeholder, submission.form);
        }
        else
        {
            let errorDisplay = document.createElement("p");
            errorDisplay.innerHTML = "Error " + response.status + " " + response.statusText;
            submission.form.appendChild(errorDisplay);
            isError = true;
        }
    }
    
    if(isError)
    {
        submitButton.disabled = false;
        submitButton.innerHTML = buttonText;
    }
    else
    {
        setTimeout(function() {window.location.href = "homepage.php";}, 2000);
    }
}