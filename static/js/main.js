function sendlocation({ coords }){
    const { latitude, longitude } = coords;
    const position = [latitude, longitude];
    console.log(position);

    fetch('getclocks.php?lat='+position[0]+"&long="+position[1])
        .then(response => response.json())
        .then(clocks => clockstopage(clocks));
}

function clockstopage(clocks) {
    let html = [];
    clocks.forEach(function (item, i, arr) {

    })
    
}

function sendlocatioOn({ coords }){
    const { latitude, longitude } = coords;
    const position = [latitude, longitude];
    console.log(position);
    location.href = '?lat=' + position[0] + '&' + 'long=' + position[1];
}


function process({coords}) {
    const { latitude, longitude } = coords;
    const position = [latitude, longitude];
    console.log(position);
    document.getElementById("lat").value = position[0];
    document.getElementById("long").value = position[1];
    document.getElementById("lat").closest("form").submit();
}

function countDisplayClocs(count) {
    if (location.href.indexOf("count") !== -1) {
        location.href = location.href.slice(0, location.href.indexOf("count") + 6) + count;
    } else if (location.href.includes("&")){
        location.href += "&count=" + count;
    }
}