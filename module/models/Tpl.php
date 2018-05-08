<?php

namespace module\models;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use yii\base\Object;
use yii\di\Container;
use yii\di\ServiceLocator;

class Tpl
{

    public function test()
    {
//        $class = 'module\models\TestContainer';
//
//        $db = [
//            'class' => $class,
//            'a' => 1234567
//        ];
//
//        $location = new ServiceLocator();
//        $location->setComponents(['db'=>$db]);
//        $obj = $location->get('db');
//        return $obj;
//        $to = "somebody@example.com";
//        $subject = "My subject";
//        $txt = "Hello world!";
//        $headers = "From: webmaster@example.com" . "\r\n" .
//            "CC: somebodyelse@example.com";
//
//        return mail($to,$subject,$txt,$headers);


        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = 2;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp-mail.outlook.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'wsd15321@outlook.com';                 // SMTP username
            $mail->Password = 'acg19121';                           // SMTP password
            $mail->SMTPSecure = 'STARTTLS';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('wsd15321@outlook.com','test99');
//            $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
            $mail->addAddress('3444157027@qq.com');       // Name is optional
//            $mail->addReplyTo('info@example.com', 'Information');
//            $mail->addCC('cc@example.com');
//            $mail->addBCC('bcc@example.com');

            //Attachments
//            $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//            $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            //Content
            $text = $this->getText();
            $mail->CharSet = 'utf-8';
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = '这是标题';
            $mail->Body    = $text;
//            $mail->AltBody = '下内容';

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }



    }


    private function getText()
    {
        $t = '<style>@CHARSET "UTF-8";
body{
    min-width:1680px;
    padding-right:8px;
}

table{
    border-collapse:collapse;
    border-spacing:0;
}

td,th{
    min-width:100px;
}

.pdf {
    width: 100%;
    min-width: 1200px;
    height: auto;
    margin: auto auto;
    padding: 0;
    border: 1px solid black;
    position: relative;
    /*border: 1px solid fuchsia;*/
}

.pdf-title {
    width: 100%;
    height: auto;
    margin: auto auto;
    text-align: center;
    line-height: 50px;
    letter-spacing: 1px;
    font-size: 1.5em;
    font-weight: bold;
}


.pdf-head {
    width:100%;
    height:auto;
    margin: auto auto;
}

.pdf-head  tr {
    width: 100%;
    height: 80px;
}

.pdf-head  tr td{
    width: 100%;
    height: 80px;
    overflow:hidden;
}

.pdf-card {
    width: 100%;
    height: auto;
    margin: auto auto;
}


.pdf-card tr td{
    /*border: 1px solid black;*/
    line-height:28px;
    font-size: 16px;
}

.pdf-tip { 
    width:100%;
    border: 1px solid black;
    height:auto;
}

.pdf-tip tr td {
    line-height:30px;
} 

.pdf-remark { 
    width:100%;
    /*border: 1px solid black;*/
    height:auto;
    font-size: 15px;
}


.pdf-img{
    width:100%;
    border: 1px solid black;
}

.pdf-img tr td{
    width:25%;
    text-align:center;
    border: 1px solid black;
}







.pdf-top {
    width: 100%;
    height: 100px;
    margin: auto auto;
}

.pdf-top tr td {
    width: 25%;
    height: auto;
    line-height: 40px;
}

.pdf-body {
    width: 100%;
    height: auto;
    margin: auto auto;
    font-family: "Open Sans", "Segoe UI";
}

.pdf-body  tr {
    width: 100%;
}

.pdf-body  tr td, th {
    border: 1px solid black;
    max-width: 150px;
    word-break: break-all;
    text-align: center;
    line-height: 20px;
    font-size: 14px;
}

.pdf-body  tr th {
    font-weight: blod;
}

.pdf-bottom {
    width: 100%;
    height: auto;
    margin: auto auto;
    border: 1px solid black;
    /*background-color: brown;*/
}

.pdf-bottom tr {
    text-align: center;
}

.pdf-bottom img {
    max-width: 33%;
    max-height: 33%;
    float: left;
}</style><head><meta charset = \'utf-8\'></head><table class="pdf-head">
            <tr>
            <td style="width:20%"><img  style ="width:120px;" src = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAUgAAAFIAQMAAAAI2vVwAAAABlBMVEX///8AAABVwtN+AAAAAXRSTlMAQObYZgAAAe5JREFUaIHt1dFy41AIA1D+/6e1s0USpLPTS/dZTpvY+LgPoJJCH9Wv0lWXurpqkU9Zfw+6atQlfD1YS0RepPvuCfhGP7pF5FH2HfiyBCP/U7rnYq5G/kYyuH3al+z6LkUe5cc31z9f69ss8iV1eCGo707y9yPyZ7n+71FKMH95m+eRb6mVqoZj7hQMK/Iqi+/9sSbCKUAhj7xIx5f9p2ozW4IDinxKxZU/moMfYq0iTxJcAX7w2zCUbESepNr+9bv2QlPoGac88mfpEGM2ArgN+NTciXzKzi3zPLGeNCvCkSeptPK2G90PwcXIo2SM91Wp0VjPR14lMwutgZVbhxmRN+kor/Z2rRxw74vIpySkLhPoe6y4HiJPsuyhU4tSndXIt0RvUkyWWZqBrFxHviSrcGYd5RkOXeRBTk/ZXyW6ZltoBJFv+fW+krr+gteCBxD5lOouvAZ6ChpBfayIyLecEVSp07qxTiKvUn1mjsF+QzsCmkrkSU6ay2medzjSkSfZn32Nebi0JwwiT3KqawIfy2HmEvmSOtzuKi0Mt50XkQcJ71ToewofpzOPyItc//XlamEqy0QeJOC9wDavugbTtyJ/Jb0TtF89DFcjr3ILWHgKkVdZauxnXGuth1kdkS+JSW13uFb/vRMq8iT/AE/WIEANU2cBAAAAAElFTkSuQmCC"></td>
            <td style="width:60%;text-align:center;font-size:25px;font-weight:blod;line-height:80px;">（海勒展厅）生产通知单</td>
            <td style="width:20%;text-align:right;"></td>
            </tr>
            </table><table class = "pdf-card"><tr><td style="width: 25%;"><b>单据号：</b>DD011804240004</td><td style="width: 25%;"><b>客户名称：</b>金六福</td><td style="width: 25%;"><b>柜台名称：</b></td><td style="width: 25%;"><b>部门名称：</b>物流部</td></tr></table><table class = "pdf-card"><tr><td style="width: 25%;"><b>制单日期：</b>2018-04-24</td><td style="width: 25%;"><b>下单时间：</b></td><td style="width: 25%;"><b>加工商名称：</b>昌源达</td><td style="width: 25%;"><b>流水号：</b>0a5a</td></tr></table><table class = "pdf-card"><tr><td style="width: 50%;"><b>提取码：</b></td><td style="width: 50%;"><b>表头备注：</b></td></tr></table><div class="blank"></div><table class = "pdf-body"><tr><th style="width: 3%">行</th><th style="width: 7%">子客户</th><th style="width: 7%">成色名称</th><th style="width: 7%">字印名称</th><th style="width: 6%">产品名称</th><th style="width: 6%">存货分类</th><th style="width: 6%">单重(克)</th><th style="width: 6%">应收数量</th><th style="width: 4%">单位</th><th style="width: 7%">总重量(克)</th><th style="width: 9%">规格</th><th style="width: 13%">工艺要求</th><th style="width: 6%">交货日期</th><th style="width: 7%">表体备注</th><th style="width: 3%">生产线</th><th style="width: 3%">图片</th></tr><tr><td style="width: 3%">1</td><td style="width: 7%;"></td><td style="width: 7%;">红白分色</td><td style="width: 7%;">空白字印</td><td style="width: 6%;">88</td><td style="width: 6%;">耳钉</td><td style="width: 6%;">32</td><td style="width: 6%;">1</td><td style="width: 4%;">对</td><td style="width: 7%;">32.00</td><td style="width: 9%;"></td><td style="width: 13%;"></td><td style="width: 6%;">2018-04-26</td><td style="width: 7%;"></td><td style="width: 3%;"></td><td style="max-width:400px;width: 3%;"></td></tr></table><table class = "pdf-body"><tr><td style="width: 3%">总</td><td style="width: 7%">-</td><td style="width: 7%">-</td><td style="width: 7%">-</td><td style="width: 6%">-</td><td style="width: 6%">-</td><td style="width: 6%">-</td><td style="width: 6%">1.00</td><td style="width: 4%">-</td><td style="width: 7%">32.00</td><td style="width: 9%">-</td><td style="width: 13%">-</td><td style="width: 6%">-</td><td style="width: 7%">-</td><td style="width: 3%">-</td><td style="width: 3%">-</td></tr></table><div class="blank"></div><table class = "pdf-card"><tr><td style="width: 33.333333333333%;"><b>制单时间：</b>2018-04-24 11:26:25</td><td style="width: 33.333333333333%;"><b>制单人：</b>337845818@qq.com</td><td style="width: 33.333333333333%;"><b>审核人：</b></td></tr></table><div class="blank"></div><table class = "pdf-remark"><tr><td>耳钉32g共1件 32g。      </td></tr></table><div class="blank"></div><table class = "pdf-remark"><tr><td>友情提示：<br>生产要求：收到订单后如有不能按订单所要求的生产或出货时，请第一时间与跟单员联系。<br>出货要求：厂商必须按订单要求的字印内容、规格、货品工艺要求款式、订单总量等出货，不可超单、漏单，出货时须分客户包装，并在包装上注明客户名称。送货时请带上生产通知单，我司会根据订单要求收货，如有出现没有带生产通知单，误打字印以及混字印情况的，我们可以根据公司要求拒绝收货。<br>成色要求：我司会在收货后随机抽检做表面或破坏性检测鉴定，所有无焊产品成色检测要求必须达9995或以上，如果货品成色不能达到与我司所签合同的标准将批量退货。<br>其他要求：为了方便我们对单，麻烦您交货时带上原单。</td></tr></table><div class="blank"></div>
';
        return $t;
    }



}


class C{
    public $db;
    public function __construct($db = '')
    {
        $this->db = $db;
    }
}

