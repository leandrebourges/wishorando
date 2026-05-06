export function getElevation(lat, lon) {
    const url = 'https://nationalmap.gov/epqs/pqs.php?output=json&x='+lon+'&y='+lat+'&units=Meters';

    fetch(url)
        .then(response => response.json())
        // requête a fonctionné correctement -> on renvoie l'elevation
        .then(data => {
            return parseFloat(data.USGS_Elevation_Point_Query_Service.Elevation_Query.Elevation);
        })
        // requête a eu un problème -> on met erreur dans console et on renvoie null
        .catch(err => {
            console.log(err);
            return null;
        });
}