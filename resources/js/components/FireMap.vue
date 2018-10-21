<div id='map' style='width: 400px; height: 300px;'></div>
<script>
mapboxgl.accessToken = 'pk.eyJ1IjoibXBvcnRlcjkiLCJhIjoiY2puaHg2ZWxuMGltMzNwbnkyMWVja2FyYyJ9.P72G1J4lJN5yaj1azYAmaw';
var map = new mapboxgl.Map({
        container: 'map', // container id
        style: 'mapbox://styles/mapbox/streets-v9', // stylesheet location
        center: [-0.1404545, 51.5220163], // starting position [lng, lat]
        zoom: 16 // starting zoom
});
</script>