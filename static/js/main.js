function sendlocation({ coords }){
    const { latitude, longitude } = coords;
    const position = [latitude, longitude];
    console.log(position);

    fetch('getclocks.php?lat='+position[0]+"&long="+position[1])
        .then(response => response.json())
        .then(clocks => alert(clocks[0].location + "  " + clocks[0].dist));

}

function sendlocatioOn({ coords }){
    const { latitude, longitude } = coords;
    const position = [latitude, longitude];
    console.log(position);
    location.href = '?lat=' + position[0] + '&' + 'long=' + position[1];
}