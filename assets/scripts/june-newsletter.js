
function loadScriptDynamically(scriptSrc) {
    // Check if the script is already in the document
    const existingScript = document.querySelector(`script[src="${scriptSrc}"]`);
    if (existingScript) {
        console.log('Script already loaded:', scriptSrc);
        return; // exit if script is already in the document
    }

    // Create a new script element
    const script = document.createElement('script');
    script.src = scriptSrc;

    // Optional: Add event listeners for the load and error events
    script.onload = () => {
        console.log('Script loaded successfully:', scriptSrc);
    };
    script.onerror = (error) => {
        console.error('Error loading script:', scriptSrc, error);
    };

    // Append the script to the document
    document.body.appendChild(script);
}

loadScriptDynamically('https://view.juneapp.com/june-lp-framework/prod/sdk/3.0.0/js/widgets/forms.js');
