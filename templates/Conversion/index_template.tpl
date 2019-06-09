<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset={CONFIG="CHARSET"}" />
		<title>{VAR="PAGE_TITLE"}</title>
		<link rel="icon" type="image/png" sizes="32x32" href="http://localhost/lr6ext/templates/Conversion/favicons/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="http://localhost/lr6ext/templates/Conversion/favicons/favicon-16x16.png">
		<link rel="stylesheet" type="text/css" href="http://localhost/lr6ext/templates/Conversion/style.css" />
	</head>	
	<body>
		<div id = "container">
			{FILE="HEADER"}
			{FILE="NAV"}
			<div class = "main">
				{FILE="ARTICLE"}
				{FILE="FORM"}
			</div>
		</div>
		<footer>
				&copy; FilimonDevelopment
		<footer>
	</body>
</html>
<script>
	function count(e, el, n){
		if (window.XMLHttpRequest) {
			e.preventDefault();
			console.log("keke");
			var http = new XMLHttpRequest(), href = el.href;
			http.open("POST", "http://localhost/lr6ext/stat.php", true);
			http.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			http.timeout = 10000;
			http.addEventListener('loadend', function() { location = href });
			http.send("url=" + location + "&title=" + document.title + "&log=" + n);  
		}
	}
</script>