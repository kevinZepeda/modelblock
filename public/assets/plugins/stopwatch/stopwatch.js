var Stopwatch = (function() {
	var s;
	return {
		settings: {
			stop: 0,
			hours: 0,
			mills: 0,
			secs: 0,
			mins: 0,
			i: 1,
			times: ["00:00"],
		},
		init: function() {
			s = this.settings;
			setInterval(this.timer, 1);
		},
		reinit: function() {
			s = this.settings;
			s.stop = 0;
		},
		restart: function() {
			s.mills = 0;
			s.hours = 0;
			s.secs = 0,
			s.mins = 0;
			s.stop = 0;
			this.start();
		},
		continue: function(hours, mins, secs) {
			s = this.settings;
			s.hours = hours;
			s.secs = secs,
			s.mins = mins;
			setInterval(this.timer, 1);
			console.log(mins);
		},
		start: function() {
			s.stop = 0;
		},
		stop: function() {
			s.stop = 1;
		},
		timer: function() {
			if (s.stop === 0) {			
				if (s.mills === 100) {
					s.secs++;
					s.mills = 0;
				}
				if (s.secs === 60) {
					s.mins++;
					s.secs = 0;
				}
				if(s.mins === 60){
					s.hours++;
					s.mins = 0;
				}	
				$("#time").val(("0" + s.hours).slice(-2) + ":" + ("0" + s.mins).slice(-2) + ":"
					 						 + ("0" + s.secs).slice(-2));
				s.mills++;
			}
		}
	};
})();