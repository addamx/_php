<?php
ini_set("SMTP", "localhost"); //    Windows 专用
ini_set('sendmail_from', 'addamx@192.168.1.100'); //    Windows 专用
ini_set('smtp_port', '25'); //    Windows 专用
//ini_set('sendmail_path', ''); //Unix 系统专用：规定 sendmail 程序的路径（通常 /usr/sbin/sendmail 或 /usr/lib/sendmail）。

// The message
$message = "Line 1\nLine 2\nLine 3";

// In case any of our lines are larger than 70 characters, we should use wordwrap()
$message = wordwrap($message, 70);

// Send
mail('addamx@126.com', 'My Subject', $message);
