// JavaScript Document
	function setCookie(name, value, expires, path, domain, secure) 
	{
		// set time, it's in milliseconds
		var today = new Date();
		today.setTime(today.getTime());
	
		if(expires)
		{
		expires = expires * 1000 * 60 * 60 * 24;
		}
		var expires_date = new Date( today.getTime() + (expires) );
		
		document.cookie = name + "=" +escape( value ) +
		( ( expires ) ? ";expires=" + expires_date.toGMTString() : "" ) + 
		( ( path ) ? ";path=" + path : "" ) + 
		( ( domain ) ? ";domain=" + domain : "" ) +
		( ( secure ) ? ";secure" : "" );
		
	}
	/*
	* This will retrieve the cookie by name, if the cookie does not exist, it will return false, so you can do things like 
	* if ( Get_Cookie( 'your_cookie' ) ) do something.
	*/
	function getCookie(name) 
	{
		var start = document.cookie.indexOf(name + "=");
		var len = start + name.length + 1;
		if((!start) && (name != document.cookie.substring(0, name.length )))
		{
	  		return null;
		}
		if(start == -1)
		{ 
			return null;
		}
		var end = document.cookie.indexOf(";", len);
		if(end == -1)
		{ 
			end = document.cookie.length;
		}

		return decodeURI(unescape(document.cookie.substring(len, end)));
	}
	
	function deleteCookie(name) 
	{
		document.cookie = name+"=; expires=Thu, 01 Jan 1970 00:00:00 GMT";
	}

	