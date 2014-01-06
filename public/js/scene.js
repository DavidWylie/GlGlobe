var globe;
var container;
var animate;
var search;
var time;

function setMinTime() {
    return $.ajax({type: 'GET', url: 'times.php?search=' + search, async: false});
}

function setDataList() {
    $.ajax("dataList.php", {async: 'false'}).success(function(j) {
        var options = '<option value="default">Pick database...</option>';
        data = jQuery.parseJSON(j);
        for (var i = 0; i < data.length; i++) {
            options += '<option value="' + data[i] + '">' + data[i] + '</option>';
        }

        $("#language").html(options);
    });
}

function outputUpdate(speednum) {
    document.querySelector('#speednum').value = vol;
}

function initGlobe() {
    document.body.style.backgroundImage = 'none'; // remove loading
    container = document.getElementById('container');
    globe = new DAT.Globe(container);
    globe.addData([], {format: 'magnitude', animated: false});
    globe.createPoints();
    globe.animate();
    window.globe = globe;
    animate = function() {
        requestAnimationFrame(animate);
    }

    animate();
}

function setGlobeData() {
    setInterval(function() {
        interval = 5000;
        time = time+interval;
        dataResp = $.ajax({
            url:'data.php?offset=' + time + '&search=' + search + '&interval=' + interval,
            async: false
        });
        dataObj = dataResp.responseText;
        data = jQuery.parseJSON(dataObj);
        
        globe.addData(data, {format: 'magnitude', animated: false});
        globe.createPoints();
        globe.animate();
    }, 2000);
}


