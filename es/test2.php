<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t’tulo</title>
<script language="JavaScript" type="text/javascript" src="jquery.js" charset="utf-8"></script>
<script type="text/javascript" language="javascript">

	function imagePreview() {
	ruta = 'file:///' + document.getElementById("imagen").value;
	document.getElementById("preview").src = ruta;
	}

</script>
</head>

<body>
	<input type="file" id="imagen" name="imagen" onchange="imagePreview()"/>
	<img id="preview" src="" />
</body>
</html>