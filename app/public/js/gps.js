document.addEventListener("DOMContentLoaded", function(event) { 
	// how often should we send location data? in seconds
	var sendInterval = 5;

	var runnerId;
	while (!runnerId){
		runnerId = prompt("Nom du cycliste :", "");
		// Sanitize ruunerId
		runnerId = runnerId.replace(" ", "-")
						   .replace("&", "-")
						   .replace("'", "")
						   .replace("\"", "")
						   .replace("?", "")
						   .replace("%", "");
	}

	var countPoints = 0;
	var intervalId;
	var watchId;
	var idx = 0;
	var formData = {};
	var status = u("#status p"), strt = u('#start'), stop = u('#stop'), msg = u('#msg p');

	// Init
	status.text("Not tracking");
	strt.on("click", startTrack);
	stop.on("click", stopTrack);
	msg.text("");

	function startTrack(){
		if(navigator.geolocation){
			watchId = navigator.geolocation.watchPosition(geo_success, errorHandler,
			{enableHighAccuracy:true, maximumAge:30000, timeout:27000});
		}
		else{
			alert("Sorry, device does not support geolocation! Update your browser.");
		}
	}

	function stopTrack(){
		clearInterval(intervalId);
		navigator.geolocation.clearWatch(watchId);
		idx = 0;
		status.text("Not tracking")
		status.removeClass("active")
		status.addClass("stopped")

		strt.attr("disabled", "");
		stop.attr("disabled", "disabled");
	}
	
	function geo_success(position){
		status.text("Tracking active")
		status.removeClass("stopped")
		status.addClass("active");

		strt.attr("disabled", "disabled");
		stop.attr("disabled", "");
		
		lat = position.coords.latitude;
		lon = position.coords.longitude;

		formData.lat=lat;
		formData.lon=lon;
				
		if(idx === 0){
			intervalId = setInterval(postData, sendInterval*1000);
		}

		idx++;
	}

	function addTime(){
		// insert time in formData-object
		var d = new Date();
		var d_utc = ISODateString(d);
		
		formData.time=d_utc;
		
		// date to ISO 8601,
		function ISODateString(d){
			function pad(n){return n<10 ? '0'+n : n}
			return d.getUTCFullYear()+'-'
			+ pad(d.getUTCMonth()+1)+'-'
			+ pad(d.getUTCDate())+'T'
			+ pad(d.getUTCHours())+':'
			+ pad(d.getUTCMinutes())+':'
			+ pad(d.getUTCSeconds())+'Z'
		}
	}

	async function postData(){
		addTime();
		msg.text("");
		msg.removeClass("err")
		msg.removeClass("ok")

		let response = await fetch("index.php?action=track&runnerId="+runnerId, {
			method: 'POST',
			headers: {
			  'Content-Type': 'application/json;charset=utf-8'
			},
			body: JSON.stringify(formData)
		});

		let result = await response.json();
		if (result.message != "OK") {
			msg.addClass("err")
			msg.text(result.message);
		} else {
			countPoints++;
			var s = (countPoints > 1)? "s":"";
			msg.addClass("ok")
			msg.html("<ul class='lastPoint'><li>" + formData.lat + ", " + formData.lon + "</li><li>" + formData.time + "</li><li>" + countPoints + " point" + s + " </li></ul>" );
		}
	
	}

	function errorHandler(err){ 
		if(err.code == 1) {
			alert("Error: Access was denied");
		}
		else if(err.code == 2) {
			alert("Error: Position is unavailable");
		}
	}
});