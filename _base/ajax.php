<?php
$dir = "./function/";
echo htmlspecialchars(file_get_contents($dir . $_POST['file']));
