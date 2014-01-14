var canvas;
var container;
var gl;

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

//
//    function reload(){
//            var container = document.getElementById("container");
//            var content = container.innerHTML;
//            container.innerHTML= content;
//        }


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
    setInterval(function() {
        globe.rotate();
    }, 80);
}

function setGlobeData() {
    setInterval(function() {
        interval = 5000;
        time = time + interval;
        dataResp = $.ajax({
            type: 'GET',
            url: 'data.php?offset=' + time + '&search=' + search + '&interval=' + interval,
            async: false
        });
        dataObj = dataResp.responseText;
        data = jQuery.parseJSON(dataObj);
        window.data = data;
        if (data.length > 0) {
            for (i = 0; i < data.length; i += 1) {
                globe.addData(data[i], {format: 'magnitude', animated: true});
            }
            globe.createPoints();
            globe.animate();
        }
    }, 2000);
}

//
//dataSource.bind("sync", function(e) {
//  $('container').data("container").dataSource.read();
//});
//
function addGlobeData(search, colour) {
    dataResp = $.ajax({
        type: 'GET',
        url: 'search.php?search=' + search + '&colour=' + colour,
        async: false
    });
    dataObj = dataResp.responseText;
    if (dataObj) {
        data = jQuery.parseJSON(dataObj);
        window.data = data;
        if (dataObj && data.length > 0) {
            globe.addData(data, {format: 'magnitude', animated: false});
            globe.createPoints();
            globe.animate();
        }
        else {
            alert("No Results");

        }
    } else {
        alert("Please wait 10 mins and try again");
    }
}