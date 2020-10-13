var youAreHereDotConfig = {
    center: {
        lng: 151.77136275392934,
        lat: -32.92746324645514
    },
    markerOptions: {
        offZOpacity: 0.2,
        zLevel: 1,
        offset: [0, -29]
    },
    bearing: -51.95753181875392,
    pitch: 48.49,
    zoom: 20.8,
    zLevel: 1
};

var destLngLat;

// Just the same way to initialize as always...
var myMap = new Mazemap.Map({
    container: 'map',
    campuses: 128,
    center: youAreHereDotConfig.center,
    zoom: youAreHereDotConfig.zoom,
    zLevel: youAreHereDotConfig.zLevel,
    pitch: youAreHereDotConfig.pitch,
    bearing: youAreHereDotConfig.bearing,
    zLevelControl: false,
    scrollZoom: true,
    doubleClickZoom: true,
    touchZoomRotate: true,
    minZoom: 19
});

myMap.on('click', onMapClick);

const modalBody = document.querySelector('.modal-body')

const nameDiv = document.createElement('div')
const floorDiv = document.createElement('div')

const title = document.createElement('p');
const floor = document.createElement('p');

const nameHeader = document.createElement('h3');
const floorHeader = document.createElement('h3');

nameHeader.innerText = "Name";
floorHeader.innerText = "Floor"

nameDiv.appendChild(nameHeader)
floorDiv.appendChild(floorHeader)

modalBody.appendChild(nameDiv)
modalBody.appendChild(floorDiv)

function onMapClick(e) {

    // un-highlight any already highlighted rooms
    placePoiMarker(null);
    mySearchInput.clearSearch();

    var lngLat = e.lngLat;
    var zLevel = myMap.zLevel;

    // Fetching via Data API
    // NB: Adding optional campusId parameter, makes lookup much faster, but can be omitted
    Mazemap.Data.getPoiAt(lngLat, zLevel).then(poi => {
        // Run custom highlight function
        placePoiMarker(poi);

        console.log(names, floorName)

    }).catch(function () {
        return false;
    });

    // add to modal-body div

}



myMap.on('load', function () {

    // Add zoom and rotation controls to the map.
    myMap.addControl(new Mazemap.mapboxgl.NavigationControl(), 'bottom-left');

    var floorBar = new Mazemap.ZLevelBarControl({
        className: 'custom-zlevel-bar',

        maxHeight: 300,
        buttonHeight: 60,
        autoUpdate: true,
        zLevels: {
            '1': '1st Floor',
            '2': '2nd Floor'
        }
    });
    myMap.addControl(floorBar, 'middle-right');

    myMap.addSource('heatpoints', {
        "type": "geojson",
        "data": "./heatpoints.geojson"
    });



    function onResize() {
        var height = myMap.getCanvas().clientHeight;
        var maxHeight = height - 50; // 50 pixels account for margins and spacing
        floorBar.setMaxHeight(maxHeight);
    };
    myMap.on('resize', onResize);

    onResize();

    initSearch();

    // create a DOM element for the marker
    // var el = document.createElement('img');
    // el.className = 'you-are-here-marker';
    // el.src = 'you-are-here-man.png'

    var blueDot = new Mazemap.BlueDot({
        accuracyCircle: true,
        zLevel: 1,
        radius: 3
    }).setLngLat(youAreHereDotConfig.center).addTo(myMap);

    // new Mazemap.ZLevelMarker(blueDot).on('click', function(){
    //     myMap.flyTo(youAreHereDotConfig);
    // });

    function moveDots() {

        function setNewPosition() {
    
            var newLngLat = calcLinearLatLng(youAreHereDotConfig.center, destLngLat, 10);
    
            blueDot.setLngLat(newLngLat, {
                animate: true,
                duration: 400
            });
    
            myMap.flyTo({
                zoom: mapZoom,
                center: newLngLat,
                duration: 400,
                easing: function (a) {
                    return a;
                }
            });
    
        }
    
        function setNewAccuracy() {
            var accuracy = Math.random() * 3 + 10;
            blueDot.setAccuracy(accuracy);
        }
    
        if (destLngLat) {
            setInterval(setNewPosition, 250);
            setInterval(setNewAccuracy, 2000);
    
            setInterval(toggleZoomInOut, 5000);
        } else
            alert('Please select at least one')
    
    }
    
    document.querySelector('.demoBtn').addEventListener('click', moveDots);


    // Initialize a Highlighter for POIs
    // Storing the object on the map just makes it easy to access for other things
    myMap.highlighter = new Mazemap.Highlighter(myMap, {
        showOutline: true, // optional
        showFill: true, // optional
        outlineColor: Mazemap.Util.Colors.MazeColors.MazeBlue, // optional
        fillColor: Mazemap.Util.Colors.MazeColors.MazeBlue // optional
    });
    //new Mazemap.ZLevelMarker( youAreHereDotConfig.markerOptions ).setLngLat( youAreHereDotConfig.lngLat ).addTo(myMap);

});


function toggleZoomInOut() {
    zoomInOutBool = !zoomInOutBool;

    if (zoomInOutBool) {
        mapZoom = 20.5;
    } else {
        mapZoom = 18.837;
    };
}

// Returns a linear moving point along the line between startLatLng and endLatLng given a speed factor
function calcLinearLatLng(startLatLng, endLatLng, speed) {

    var startPoint = myMap.project(startLatLng);
    var endPoint = myMap.project(endLatLng);

    var dX = endPoint.x - startPoint.x;
    var dY = endPoint.y - startPoint.y;

    var now = performance.now();

    var lengthTime = speed * 1000;

    var timeFraction = (now % lengthTime) / lengthTime;

    var fractionX = dX * timeFraction;
    var fractionY = dY * timeFraction;

    var fractionPoint = new Mazemap.mapboxgl.Point(fractionX, fractionY);


    //Alternate the direction
    var direction = Math.floor(now / lengthTime) % 2;

    var calcPoint;
    if (direction) {
        calcPoint = endPoint.sub(fractionPoint);
    } else {
        calcPoint = startPoint.add(fractionPoint);
    }
    var calcLatLng = myMap.unproject(calcPoint);

    return calcLatLng;
}

function highlightRoom(poi) {
    // If the POI has a polygon, use the default 'highlight' function to draw a marked outline around the POI.
    var poiId = poi && (poi.properties.poiId || poi.properties.id);

    if (poiId) {
        Mazemap.Data.getPoi(poiId).then(function (poi) {
            if (poi.geometry.type === "Polygon") {

                myMap.highlighter.highlight(poi);
            }
        })
    } else if (poi === null) {
        myMap.highlighter.clear();
    }
}

function placePoiMarker(poi) {

    if (!poi) {
        highlightRoom(null);
        setRoute(null);

        return;
    }
    // Get a center point for the POI, because the data can return a polygon instead of just a point sometimes
    var lngLat = Mazemap.Util.getPoiLngLat(poi);
    var zLevel = poi.properties.zValue || poi.properties.zLevel;

    if (resultMarker) {
        resultMarker.remove();
    }

    resultMarker
        .setLngLat(lngLat)
        .setZLevel(zLevel)
        .addTo(myMap);
    // console.log(lngLat)

    destLngLat = lngLat;
    myMap.zLevel = zLevel;

    highlightRoom(poi);

    var start = {
        lngLat: youAreHereDotConfig.center,
        zLevel: youAreHereDotConfig.zLevel
    };
    var dest = {
        lngLat: lngLat,
        zLevel: zLevel
    };

    console.log(poi)

    // destructure
    const {
        properties: {
            zName,
            names,
            dispPoiNames,
            floorName
        }
    } = poi

    // set text                
    title.innerText = "";
    title.innerText = dispPoiNames || names

    floor.innerText = "";
    floor.innerText = zName || floorName;

    setRoute(start, dest);

    nameDiv.appendChild(title)
    floorDiv.appendChild(floor)

    //myMap.flyTo({center: lngLat, zoom: 19, duration: 2000});
}


if (screenfull.enabled) {
    document.getElementById("fullscreen-btn").style.display = "block";
}

function setRoute(start, dest) {
    if (start === null) {
        myMap.route.clear();
        return;
    }
    Mazemap.Data.getRouteJSON(start, dest)
        .then(function (geojson) {
            myMap.route.setPath(geojson);
            console.log('@ geojson', geojson);
            var bounds = Mazemap.Util.Turf.bbox(geojson);
            myMap.fitBoundsRotated(bounds, {
                padding: 100
            });
        });
}

function resetMap() {
    location.reload()
};

function initSearch() {


    var mySearch = new Mazemap.Search.SearchController({
        campusid: 128,

        rows: 30,

        withpois: true,
        withbuilding: false,
        withtype: false,
        withcampus: false,

        resultsFormat: 'geojson'
    });

    window.mySearchInput = new Mazemap.Search.SearchInput({
        container: document.getElementById('search-input-container'),
        input: document.getElementById('searchInput'),
        suggestions: document.getElementById('suggestions'),
        searchController: mySearch
    });
    window.mySearchInput.on('itemclick', function (e) {
        var poiFeature = e.item;
        // console.log(e)
        placePoiMarker(poiFeature);
    });

    myMap.getCanvas().addEventListener('focus', function () {
        mySearchInput.hideSuggestions();
    });


    window.resultMarker = new Mazemap.MazeMarker({
        color: 'rgb(253, 117, 38)',
        innerCircle: true,
        innerCircleColor: '#FFF',
        size: 34,
        innerCircleScale: 0.5,
        zLevel: 1
    })

    document.getElementById('search-input-container').style.display = '';
}