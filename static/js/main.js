function sendlocatioOn({ coords }){
    const { latitude, longitude } = coords;
    const position = [latitude, longitude];
    console.log(position);
    let xhr = new XMLHttpRequest();
    xhr.open('GET', 'index.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send(JSON.stringify({
        lat: position[0],
        long: position[1]
    }));
}

function sendlocation({ coords }){
    const { latitude, longitude } = coords;
    const position = [latitude, longitude];
    console.log(position);
    location.href = '?lat=' + position[0] + '&' + 'long=' + position[1];
}