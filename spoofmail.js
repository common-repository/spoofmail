// ========================================================================
// Native ajax support helpers
// ========================================================================
_spoofMailHelpers = {

	XMLHttpFactories: [
			function () {return new XMLHttpRequest();},
			function () {return new ActiveXObject("Msxml2.XMLHTTP");},
			function () {return new ActiveXObject("Msxml3.XMLHTTP");},
			function () {return new ActiveXObject("Microsoft.XMLHTTP");}
		],

	sendRequest: function (url,callback) {
			var req = this.createXMLHTTPObject();
			if (!req) return;
			var method = "GET";
			req.open(method,url,true);
			req.onreadystatechange = function () {
				if (req.readyState != 4) return;
				if (req.status != 200 && req.status != 304) {
					console.error(req.status);
					return;
				}
				callback(req.response, req);
			};
			if (req.readyState == 4) return;
			req.send();
		},

	createXMLHTTPObject: function() {
			var xmlhttp = false;
			for (var i = 0; i < this.XMLHttpFactories.length; i++) {
				try { xmlhttp = this.XMLHttpFactories[i](); }
				catch (e) { continue; }
				break;
			}
			return xmlhttp;
		}
};

// ========================================================================
// FakeMail function
// ========================================================================
var spoofMail = function(email, callback) {
	var url = verificationURL + '?email=' + email;
	_spoofMailHelpers.sendRequest(url, function(res, resObj){
		callback(JSON.parse(res), resObj);
	});

};

// ========================================================================
// Create jQuery plugin if jquery is included
// ========================================================================
if (typeof jQuery !== 'undefined') {
	jQuery.spoofMail = function(email, callback) {
		var url = verificationURL + '?email=' + email;
		jQuery.getJSON(url, function(res, status, resObj){
			callback(res, resObj);
		});
	};
}