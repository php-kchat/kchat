<div class="col-md-10 content">
	<div class="col-md-12 content">
		<div class="panel panel-default">
			<div class="panel-heading">
				Dashboard
			</div>
			<div class="panel-body" >
			<div id='chart' style="width:100%;height:300px"></div>
			<script>
			var data = [
			  {
				x: ['2013-10-04 22:23:00', '2013-11-04 22:23:00', '2013-12-04 22:23:00', '2014-01-04 22:23:00', '2014-02-04 22:23:00', '2014-03-04 22:23:00', '2014-04-04 22:23:00', '2014-05-04 22:23:00', '2014-06-04 22:23:00', '2014-07-04 22:23:00', '2014-08-04 22:23:00', '2014-09-04 22:23:00', '2014-10-04 22:23:00', '2014-11-04 22:23:00', '2014-12-04 22:23:00', '2015-01-04 22:23:00', '2015-02-04 22:23:00', '2015-03-04 22:23:00'],
				y: [ 50, 0, 50, 0, 30, 0, 25, 20, 0, 0, 0, 0, 0, 99, 0, 0, 0, 100],
				type: 'scatter'
			  }
			];

			Plotly.newPlot('chart', data);
			</script>
			</div>
		</div>
	</div>
	<div class="col-md-6 content">
		<div class="panel panel-default">
			<div class="panel-heading">
				Dashboard
			</div>
			<div class="panel-body" >
			<div id='map' style="width:100%;height:300px"></div>
			<script>
				var cities = new L.LayerGroup();

				L.marker([39.61, -105.02]).bindPopup('This is Littleton, CO.').addTo(cities),
				L.marker([39.74, -104.99]).bindPopup('This is Denver, CO.').addTo(cities),
				L.marker([39.73, -104.8]).bindPopup('This is Aurora, CO.').addTo(cities),
				L.marker([39.77, -105.23]).bindPopup('This is Golden, CO.').addTo(cities);


				var mbAttr = 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
						'<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
						'Imagery © <a href="http://mapbox.com">Mapbox</a>',
					mbUrl = 'https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw';

				var grayscale   = L.tileLayer(mbUrl, {id: 'mapbox.light', attribution: mbAttr}),
					streets  = L.tileLayer(mbUrl, {id: 'mapbox.streets',   attribution: mbAttr});

				var map = L.map('map', {
					center: [39.73, -104.99],
					zoom: 1,
					layers: [grayscale, cities]
				});

				var baseLayers = {
					"Grayscale": grayscale,
					"Streets": streets
				};

				var overlays = {
					"Cities": cities
				};

				L.control.layers(baseLayers, overlays).addTo(map);
			</script>
			</div>
		</div>
	</div>
	<div class="col-md-6 content">
		<div class="panel panel-default">
			<div class="panel-heading">
				Dashboard
			</div>
			<div class="panel-body" >
			<div id='map2' style="width:100%;height:300px"></div>
			<script type="text/javascript" src="<?php echo $data['config']['url']; ?>/kchat/assets/leaflet/us-states.js"></script>
			<script type="text/javascript">

				var map = L.map('map2').setView([37.8, -96], 4);

				L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
					maxZoom: 1,
					attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
						'<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
						'Imagery © <a href="http://mapbox.com">Mapbox</a>',
					id: 'mapbox.light'
				}).addTo(map);


				// control that shows state info on hover
				var info = L.control();

				info.onAdd = function (map) {
					this._div = L.DomUtil.create('div', 'info');
					this.update();
					return this._div;
				};

				info.update = function (props) {
					this._div.innerHTML = '<h4>Users on KChat</h4>' +  (props ?
						'<b>' + props.name + '</b><br />' + props.density + ' people / mi<sup>2</sup>'
						: 'Hover over a state');
				};

				info.addTo(map);


				// get color depending on population density value
				function getColor(d) {
					return d > 1000 ? '#800026' :
							d > 500  ? '#BD0026' :
							d > 200  ? '#E31A1C' :
							d > 100  ? '#FC4E2A' :
							d > 50   ? '#FD8D3C' :
							d > 20   ? '#FEB24C' :
							d > 10   ? '#FED976' :
										'#FFEDA0';
				}

				function style(feature) {
					return {
						weight: 2,
						opacity: 1,
						color: 'white',
						dashArray: '3',
						fillOpacity: 0.7,
						fillColor: getColor(feature.properties.density)
					};
				}

				function highlightFeature(e) {
					var layer = e.target;

					layer.setStyle({
						weight: 5,
						color: '#666',
						dashArray: '',
						fillOpacity: 0.7
					});

					if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
						layer.bringToFront();
					}

					info.update(layer.feature.properties);
				}

				var geojson;

				function resetHighlight(e) {
					geojson.resetStyle(e.target);
					info.update();
				}

				function zoomToFeature(e) {
					map.fitBounds(e.target.getBounds());
				}

				function onEachFeature(feature, layer) {
					layer.on({
						mouseover: highlightFeature,
						mouseout: resetHighlight,
						click: zoomToFeature
					});
				}

				geojson = L.geoJson(statesData, {
					style: style,
					onEachFeature: onEachFeature
				}).addTo(map);

				map.attributionControl.addAttribution('Population data &copy; <a href="http://census.gov/">US Census Bureau</a>');


				var legend = L.control({position: 'bottomright'});

				legend.onAdd = function (map) {

					var div = L.DomUtil.create('div', 'info legend'),
						grades = [0, 10, 20, 50, 100, 200, 500, 1000],
						labels = [],
						from, to;

					for (var i = 0; i < grades.length; i++) {
						from = grades[i];
						to = grades[i + 1];

						labels.push(
							'<i style="background:' + getColor(from + 1) + '"></i> ' +
							from + (to ? '&ndash;' + to : '+'));
					}

					div.innerHTML = labels.join('<br>');
					return div;
				};

				legend.addTo(map);

			</script>
			</div>
		</div>
	</div>
</div>