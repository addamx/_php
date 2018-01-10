<?php
$dir   = "./function/";
$files = scandir($dir);
unset($files[0]);
unset($files[1]);

$file = $dir . "recursion_01.php";
$code = htmlspecialchars(file_get_contents($file));

?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta charset="utf-8">
	<style type="text/css">
		#nav {
			width:15%;
			float:left;
		}
		ul {
			padding-left: 0px;
			overflow:auto;
		}
		li {
			list-style: none;
			margin-left:2px;
		}
		#icode {
			width:50%;
			float:left;
		}
		pre {
			overflow:auto;
		}
		#result iframe{
			width:34%;
			height: 500px;
			float:right;
			overflow:auto;
		}

	</style>
	<link href="solarized_dark.min.css" rel="stylesheet">
	<script src="jquery.min.js"></script>
	<script src="highlight.min.js"></script>
	<script>hljs.initHighlightingOnLoad();</script>
</head>
<body>
<div id="nav">
<ul id="flist">
<?php
foreach ($files as $f) {
    echo "<li><a href='{$dir}{$f}' target='myframe'>{$f}</a></li>";
}

?>
</ul>
</div>
<div id="icode">
	<pre><code style="font-size:100%;font-family:consolas"><?php echo $code ?></code></pre>
</div>
<div id="result">
	<iframe src="<?php echo $file ?>" name="myframe"></iframe>
</div>

<script type="text/javascript">

document.getElementById('flist').addEventListener('click', function(ev) {
	loadcode.call(ev.srcElement);
}, false);

var loadcode = function () {
    var xhr = new XMLHttpRequest();
    xhr.open('POST','./ajax.php',true);
    xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xhr.send('file=' + this.text);

    xhr.onreadystatechange = function() {
        if (this.readyState==4 && this.status == 200) {
        	var pre = document.getElementsByTagName("pre")[0];
        	pre.innerHTML = this.responseText;

            hljs.highlightBlock(pre);
            pre.setAttribute("style","font-size:100%;font-family:consolas");
        }

    }
}
</script>
</body>
</html>