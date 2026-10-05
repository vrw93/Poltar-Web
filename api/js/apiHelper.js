async function api(
    url, csrfToken, data = null, debugMode = false, file = false
) {
    const response = await fetch(url, {
        method: data ? "POST" : "GET",
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': csrfToken
        },
        body: data ? JSON.stringify(data) : null
    });

    if(debugMode){
        const result = await response.text();
        console.log(result);
    }

    if(file){
        return await response.blob();
    }
    return await response.json();
}