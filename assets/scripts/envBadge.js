window.onload = () => {
    // This script adds a badge indicating the environment (STAGING or LOCAL) on the website.
    // Function to create and display the environment badge
    function displayBadge(text) {
        // Create the badge element
        const badge = document.createElement('div');
        badge.textContent = text;

        // Get the current hostname and href
        const hostname = window.location.hostname.toLowerCase();

        // Style the badge
        Object.assign(badge.style, {
            position: 'fixed',
            bottom: '10px',
            right: '10px',
            backgroundColor: hostname.endsWith('.local') ? 'red' : 'orange',
            color: 'white',
            padding: '8px 12px',
            borderRadius: '4px',
            fontSize: '14px',
            fontFamily: 'Arial, sans-serif',
            fontWeight: 'bold',
            zIndex: '1000',
            boxShadow: '0 2px 6px rgba(0, 0, 0, 0.3)',
            textTransform: 'uppercase',
        });

        // Append the badge to the body
        document.body.appendChild(badge);
    }

    // Get the current hostname and href
    const hostname = window.location.hostname.toLowerCase();
    const href = window.location.href.toLowerCase();

    // Check for LOCAL environment (.local TLD)
    if (hostname.endsWith('.local')) {
        displayBadge('LOCAL');
    }
    // Check for STAGING environment (URL containing 'stag')
    else if (href.includes('stag')) {
        displayBadge('STAGING');
    }
    // No badge for other environments (e.g., production)

}
