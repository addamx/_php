<?PHP
require_once "PHPMailer/PHPMailer.php";
require_once "PHPMailer/Exception.php";
require_once "PHPMailer/smtp.php";
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

date_default_timezone_set("PRC"); //设定时区
$mail = new PHPMailer(true);
try {
    //Server settings
    $mail->SMTPDebug = 2; // 启用SMTP调试功能
    // 1 = errors and messages
    // 2 = messages only
    $mail->isSMTP(); //设定使用SMTP服务
    $mail->Host       = 'smtp.126.com;'; // Specify main and backup SMTP servers
    $mail->SMTPSecure = 'tls'; // 安全协议,163用ssl,hotmail gmail用tls.
    $mail->Port       = 25; //25,465,587;                   // SMTP服务器的端口号
    $mail->SMTPAuth   = true; // Enable SMTP authentication

    $mail->Username = 'addamx@126.com'; // SMTP username
    $mail->Password = 'xhrbl753951'; // SMTP password

    //Recipients
    $mail->setFrom('addamx@126.com', 'Mailer');
    $mail->addAddress('694575796@qq.com', 'addamx'); // Add a recipient
    // $mail->addAddress('ellen@example.com'); // Name is optional
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    //Attachments
    $mail->addAttachment('miku.jpg'); // Add attachments

    //Content
    $mail->isHTML(true); // Set email format to HTML
    //$mail->msgHTML    //HTML内容转换
    $mail->Subject = 'PHPMailer邮件发送测试';
    $mail->Body    = '测试是否发送成功.';
    $mail->AltBody = '这是简单文本';

    $mail->CharSet = "UTF-8";

    $mail->send(); //返回bool, 表示发送成功或失败
    echo 'Message has been sent';
} catch (Exception $e) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo; //调用错误提示.
}

/*
一般POP3端口110/995, SMTP端口25/465. 括号后面是可能的协议及端口.一般加密都是SSL.hotmail貌似除外(总之就不停失败)

Hotmail:
POP3服务器地址:pop3.live.com (tls: 995)
SMTP服务器地址:smtp.live.com (tls: 25/587)

Gmail邮箱
POP3：pop.gmail.com (SSL/TLS:995)
SMTP：smtp.gmail.com (SSL/TLS:465/587)
IMAP: imap.gmail.com (SSL/TLS:993)

网易系列: 均需ssl验证. 一般是POP3:110,SMTP:25,IMAP:143
126邮箱：
126免费邮箱目前不直接开放smtp、pop3，但是对于126至尊邮(vip.126)开放pop3和smtp
POP：POP.126.com
SMTP：SMTP.126.com
http://mail.126.com/help/client_04.htm

163邮箱：
IMAP: imap.163.com (143,ssl: 993)
POP：pop.163.com (110,ssl: 995)
SMTP：smtp.163.com (25,ssl: 465,994)
http://mail.163.com/help/help_client_04.htm

新浪收费邮箱
POP3：pop3.vip.sina.com
SMTP：smtp.vip.sina.com

网易@yeah.net邮箱：
POP3: pop.yeah.net;
SMTP: smtp.yeah.net

网易@netease.com邮箱：
POP3: pop.netease.com;
SMTP: smtp.netease.com

网易188财富邮
POP3：pop.188.com
SMTP：smtp.188.com

263.net:
POP3服务器地址:pop3.263.net
SMTP服务器地址:smtp.263.net

QQ邮箱
POP：pop.qq.com  (110,ssl:995)
SMTP：smtp.qq.com  (25,ssl:465/587)
为了保障用户邮箱的安全，QQ邮箱设置了POP3/SMTP的开关。系统缺省设置是“关闭”，在用户需要POP3/SMTP功能时请“开启”。

新浪邮箱：
POP：pop.sina.com.cn 或：pop3.sina.com.cn
SMTP：smtp.sina.com.cn
http://tech.sina.com.cn/sinahelp/2002-06-14/120714.shtml

阿里云
POP3: pop3.aliyun.com(无密:110,ssl:995);
SMTP: smtp.aliyun.com(无密,ssl:465);

TOM邮箱： (比较垃圾啊..)
POP：pop.tom.com
SMTP：smtp.tom.com (不支持ssl:25)
http://mail.tom.com/help/

搜狐邮箱：
POP：pop3.sohu.com
SMTP：smtp.sohu.com
http://help.sohu.com/help_2.php?fatherid=2

信网@eyou.com：
POP3: pop3.eyou.com；
SMTP: mx.eyou.com

亿唐@etang.com：
POP3: pop.free.etang.com
SMTP: smtp.free.etang.com

21世纪@21cn.com：
POP3: pop.21cn.com；
SMTP: smtp.21cn.com
注意: 网页新注册的用户不支持使用外部电子邮件客户端程序发送邮件

中华网免费邮件@mail.china.com：不支持pop3和smtp服务，因此，不可以使用outlook、foxmail等软件来进行邮件的收发，只能通过登陆网页进行收发。

雅虎@yahoo.com.cn：作为一种基于 web 的电子邮件服务，雅虎中国目前不提供对 POP 或者 SMTP 服务器的访问。这意味着不能用外部电子邮件客户端程序（例如 Netscape Mail、Foxmail 或 Outlook）来访问雅虎中国的电邮帐户。
接收服务器：pop.mail.yahoo.com
发送服务器：smtp.mail.yahoo.com
 */
