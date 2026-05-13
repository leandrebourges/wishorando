export async function getElevation(lat, lon) {
    const url = `https://api.open-meteo.com/v1/elevation?latitude=${lat}&longitude=${lon}`;

    const response = await fetch(url)

    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Erreur lors de la récupération de l'altitude (lat ${lat}, lon ${lon})`);
        }

        const result = await response.json();
        return parseFloat(result.elevation);

    } catch (error) {
        console.error(error.message);
        return null
    }
}