<?php
    if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {        
		header( 'location: /index.php' );
    }
?>
<!doctype html>

<!--
  Copyright (C) 2011-2012 Pavel Shramov
  Copyright (C) 2013 Maxime Petazzoni <maxime.petazzoni@bulix.org>
  All Rights Reserved.

  Redistribution and use in source and binary forms, with or without
  modification, are permitted provided that the following conditions are met:

  - Redistributions of source code must retain the above copyright notice,
    this list of conditions and the following disclaimer.

  - Redistributions in binary form must reproduce the above copyright notice,
    this list of conditions and the following disclaimer in the documentation
    and/or other materials provided with the distribution.

  THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
  AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
  IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
  ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
  LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
  CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
  SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
  INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
  CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
  ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
  POSSIBILITY OF SUCH DAMAGE.
-->
  <head>
  <title>visualiser</title>
	<link rel="stylesheet" href="/css/vendor/mini-3.0.1/mini-default.min.css">
    <link rel="stylesheet" href="/css/vendor/leaflet-1.7.1/leaflet.css" />
    <style type="text/css">
	  .box { border: 1px #aaa solid; border-radius: 7px; margin:1.5em;}
      .box header { padding: 0.5em; }
      .box h3 { margin: 0; padding: 0; font-weight: bold; }
      .box footer { background: #f0f0f0; padding: 0.5em; }
    table#list {overflow: scroll; max-height: 600px;}
    button.action {font-size: 0.7em;}
    .gpx .start { font-size: smaller; color: #444; }
    .gpx .map { border: 1px #888 solid; border-left: none; border-right: none; height: 600px; margin: 0; }
    .gpx ul.info { list-style: none; margin: 0; padding: 0; font-size: smaller; }
    .gpx ul.info li { color: #666; padding: 2px; display: inline; }
    .gpx ul.info li span { color: black; }
    </style>
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col-sm-2 list box" id="tracklist">
          <header>
          <h3>Liste des traces</h3>
          </header>
          <table id="list" class="striped hoverable"></table>
      </div>

      <div class="col-sm-9 gpx box" id="demo" data-gpx-source="" data-map-target="demo-map">
            <header>
              <h3>Loading...</h3>
              <span class="start"></span>
            </header>

            <article>
              <div class="map" id="demo-map"></div>
            </article>

            <footer>
              <ul class="info">
                <li>Distance :&nbsp;<span class="distance"></span>&nbsp;km</li>
                &mdash; <li>Temps :&nbsp;<span class="duration"></span></li>
                &mdash; <li>El&eacute;vation :&nbsp;+<span class="elevation-gain"></span>&nbsp;m,
                  -<span class="elevation-loss"></span>&nbsp;m
                  (D&eacute;nivel&eacute; :&nbsp;<span class="elevation-net"></span>&nbsp;m)</li>
              </ul>
            </footer>
      </div>
    </div>
  </div>    
  <script src="js/vendor/leaflet.js"></script>
  <script src="js/vendor/gpx.js"></script>
  <script type="application/javascript">
	document.addEventListener("DOMContentLoaded", function(event) { 
    const date_options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };

    function set_listening_action() {
      var buttons = document.querySelectorAll('.action');
      buttons.forEach(b => {
          b.addEventListener('click', function () {
          // location.href="index.php?action=delete&runnerid=" + this.id;
          fetch("index.php?action=delete&runnerid=" + this.id, {
            method: 'GET',
            headers: {
              'Content-Type': 'application/json;charset=utf-8'
            }
          })
          .then(response => response.json())
          .then(data => {
            console.log(data);
            location.href="index.php?action=view";
          })
          .catch(error => {console.log(error)});
        });
      });
    }

	  function display_list(elt, data) {
      data.forEach(t => {
        var tr = document.createElement("tr");
        tr.innerHTML = "<td><a href=\"index.php?action=view&trackname=" + t.trackname + "\">" + t.trackname + "</a></td>";
        tr.innerHTML += "<td><button id=\"" + t.trackname + "\" class='small rounded shadowed action'>Supprimer</button></td>";
        elt.appendChild(tr);		
      });
	  }

    function display_gpx(elt, url) {
      if (!elt) return;
  
      var mapid = elt.getAttribute('data-map-target');

      if (!url || !mapid) return;

      function _t(t) { return elt.getElementsByTagName(t)[0]; }
      function _c(c) { return elt.getElementsByClassName(c)[0]; }

      var map = L.map(mapid);
      L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data &copy; <a href="http://www.osm.org">OpenStreetMap</a>'
      }).addTo(map);

      var control = L.control.layers(null, null).addTo(map);

      new L.GPX(url, {
        async: true,
        marker_options: {
          startIconUrl: 'images/pin-icon-start.png',
          endIconUrl:   'images/pin-icon-end.png',
          shadowUrl:    'images/pin-shadow.png',
    iconSize: [22, 32],
      shadowSize: [35, 35],
    iconAnchor: [11, 32],
    shadowAnchor: [11, 33],
        },
      }).on('loaded', function(e) {
        var gpx = e.target;
        map.fitBounds(gpx.getBounds());
        control.addOverlay(gpx, gpx.get_name());

        /*
          * Note: the code below relies on the fact that the demo GPX file is
          * an actual GPS track with timing and heartrate information.
          */
        _t('h3').textContent = gpx.get_name();
        _c('start').textContent = gpx.get_start_time().toLocaleDateString('fr-FR', date_options) + ', '
          + gpx.get_start_time().toLocaleTimeString();
        _c('distance').textContent = (gpx.get_distance()/1000).toFixed(2);
        _c('duration').textContent = gpx.get_duration_string(gpx.get_moving_time());
        _c('elevation-gain').textContent = gpx.to_ft(gpx.get_elevation_gain()).toFixed(0);
        _c('elevation-loss').textContent = gpx.to_ft(gpx.get_elevation_loss()).toFixed(0);
        _c('elevation-net').textContent  = gpx.to_ft(gpx.get_elevation_gain()
          - gpx.get_elevation_loss()).toFixed(0);
      }).addTo(map);
    }

	function get_gpx_trace(trackname) {
		if (trackname !== undefined && trackname !== "" ) {
			fetch("index.php?action=gpx&runnerid="+trackname, {
				method: 'GET',
				headers: {
					'Content-Type': 'application/json;charset=utf-8'
				}
			})
				.then(response => response.json())
				.then(data => {
					display_gpx(document.getElementById('demo'),  data.file);
					})
				.catch(error => {console.log(error)});
		}
	}

	function get_list() {
	  fetch("index.php?action=tracklist", {
			method: 'GET',
			headers: {
			  'Content-Type': 'application/json;charset=utf-8'
			}
		})
			.then(response => response.json())
			.then(data => {
				display_list(document.getElementById('list'),  data);
        set_listening_action();
			 })
			.catch(error => {console.log(error)});
	}

  // Let's start 
	get_list();
	get_gpx_trace('<?php if (isset($_GET['trackname'])) { echo $_GET['trackname']; } else {} ?>');
	
	});
  </script>
</body>
</html>