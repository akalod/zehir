<?php
/**
 * Created by PhpStorm.
 * User: sengul
 * Date: 7/2/2018
 * Time: 5:21 PM
 */

namespace Zehir\System;


use PHPMailer;
use Zehir\Settings\Setup;
use Zehir\Filters;

class Mail
{

    public static function layoutSend($toMail, $title, $layout, $data)
    {
        $body = App::layout($layout, $data);
        return self::realSend($toMail, $title, $body);
    }

    public static function send($toMail, $title, $data, $bodyContent = '')
    {
        $body = $bodyContent;
        $body .= '<table border="0" style="font-family: Tahoma">';

        $c = 0;
        foreach ($data as $k => $v) {
            $n = $c % 2 ? "<tr>" : "<tr style='background: #efefef;'>";
            $body .= $n . "<td style=\"width:150px;padding:5px\">$k</td><td>$v</td></tr>";
            $c++;
        }

        $body .= '</table>';

        return self::realSend($toMail, $title, $body);

    }

    public static function manuelSend($toMail, $title, $content)
    {
        return self::realSend($toMail, $title, $content);
    }

    private static function realSend($toMail, $title, $body)
    {
        $mail = new PHPMailer;

        $mail->SMTPDebug = 0;                               // Enable verbose debug output

        $mail->isSMTP();
        $mail->CharSet = 'UTF-8';
        $mail->Host = Setup::$SMTP['Host'];
        $mail->SMTPAuth = Setup::$SMTP['SMTPAuth'];
        $mail->Username = Setup::$SMTP['Username'];
        $mail->Password = Setup::$SMTP['Password'];
        // $mail->SMTPSecure = 'tls';
        $mail->Port = Setup::$SMTP['Port'];

        $mail->From = Setup::$SMTP['Username'];
        $mail->FromName = Setup::$SMTP['Username'];
        $mail->isHTML(true);

        $mail->Subject = $title;

        $mail->Body = $body;

        $mail->smtpConnect(
            array(
                "ssl" => array(
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                    "allow_self_signed" => true
                )
            )
        );

        if (Setup::$target!='prod') {
            $mail->addAddress(Setup::$SMTP['DevMail']);
        } else {

            if (is_array($toMail)) {
                $c = count($toMail);

                for ($i = 0; $c > $i; $i++) {
                    $mail->addAddress($toMail[$i]);
                }
            } else {
                $mail->addAddress($toMail);
            }

        }

        if (!$mail->send()) {
            return false;
        } else {
            return true;
        }
    }
}