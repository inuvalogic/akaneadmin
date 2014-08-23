/*
 * INUJAX v.1.0.2
 * Wisnu Hafid
 * April 2008
 *
 *
 */
 
	function inujax_connect()
	{
		objXmlHttp = false;
        if (window.XMLHttpRequest) {
            objXmlHttp = new XMLHttpRequest();
            if (objXmlHttp.overrideMimeType) {
                objXmlHttp.overrideMimeType('text/xml');
            }
        } else if (window.ActiveXObject) {
            try {
                objXmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    objXmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {}
            }
        }

        if (!objXmlHttp) {
            alert('Giving up :( Cannot create an XMLHTTP instance');
            return false;
        }

		return objXmlHttp;
	}
	
	function inujax(target, loading_text, url, action, data)
	{
		var checkdiv = document.getElementById(target);
		if (!checkdiv)
		{
			alert('INUJAX ERROR: Element with id '+target+' not found');
			return false;
		}
		var http = inujax_connect();
		
		if (action.toUpperCase() == "POST") {
			http.open("POST", url, true);
			http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			http.setRequestHeader("Content-length", data.length);
			http.setRequestHeader("Connection", "close");
			http.setRequestHeader("Pragma", "no-chace");
			http.onreadystatechange = function (){
				if (http.readyState == 4 && http.status == 200) {
					var resValue = http.responseText;
					checkdiv.innerHTML = resValue;
				} else {
					checkdiv.innerHTML = loading_text;
				}
			}
			http.send(data);
		} else {
			http.open(action, url + '?' + data, true);
			http.onreadystatechange = function (){
				if (http.readyState == 4 && http.status == 200) {
					var resValue = http.responseText;
					checkdiv.innerHTML = resValue;
				} else {
					checkdiv.innerHTML = loading_text;
				}
			}
			http.send(null);
		}
	}	
