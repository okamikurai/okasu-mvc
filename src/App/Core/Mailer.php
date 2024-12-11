<?php
/**
*   @since 20220701
*   @author Okami
*   Variables para el envio de correo
**/
namespace Sk\App\Core;

use Sk\App\Core\Conf;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Mailer extends PHPMailer {

    public function __construct($mailer, $host, $port, $username, $password, $encryption, $fromName, $fromAddress, $app = '', $exceptions = null) {
        parent::__construct($exceptions);
        
        $this->setLanguage("es");
        $this->IsSMTP();

        $this->Mailer = $mailer;
        $this->SMTPAuth = true;
        $this->SMTPSecure = $encryption;
        $this->CharSet = "UTF-8";
        $this->Encoding = "quoted-printable";

        $this->Host = $host;
        $this->Port = $port;
        $this->Username = $username;
        $this->Password = $password;
        $this->Sender = $username;
        $this->From = $fromAddress;
        $this->FromName = $fromName;

        $this->XMailer = $app . " Mailer";
        $this->Timeout=10;

        /* Only For Debug*/

        /*
        $ this->SMTPDebug = 2;
        //$ this->SMTPDebug = SMTP::DEBUG_SERVER;
        $ this->Debugoutput = static function ($str, $level) {
            error_log("Debug level $level; message: $str\n");

        };*/
    }

    public function is_valid_email($correo) {
        return false !== filter_var($correo, FILTER_VALIDATE_EMAIL);
    }

    public function sendMail($correo, $nombre, $asunto, $body, $altbody, $reply = array(), $mailcc = array(), $bcc = array(), $adjuntos = array(), $imgIncrusta = array()){
        if (!$this->is_valid_email($correo)) {
            throw new Exception('ERROR: Correo no válido.');
        }
        
        $this->AddAddress($correo, $nombre);
        $this->Subject = $asunto;

        if (count($reply)>0) {
            foreach ($reply as $replyto) {
                if ($this->is_valid_email($replyto)) {
                    $this->AddReplyTo($replyto);
                }
            }
        }

        if (count($mailcc)>0) {
            foreach ($mailcc as $ccMail) {
                if ($this->is_valid_email($ccMail)) {
                    $this->AddReplyTo($ccMail);
                }
            }
        }

        if (count($bcc)>0) {
            foreach ($bcc as $bccMail) {
                if ($this->is_valid_email($bccMail)) {
                    $this->AddReplyTo($bccMail);
                }
            }
        }

        if (count($adjuntos)>0) {
            foreach ($adjuntos as $fa => $file) {
                $this->AddAttachment($file["file"], $file["name"]);
            }
        }

        if (count($imgIncrusta)>0) {
            foreach ($imgIncrusta as $img) {
                $this->AddEmbeddedImage($img["fileimg"], $img["cid"], $img["imgAdj"]);
            }
        }

        $this->IsHTML(true);
        $this->Body = $body;
        $this->AltBody = $altbody;

        if(!$this->Send()) {
            error_log( $this->ErrorInfo );
            $this->clearAllRecipients();
            $this->clearAttachments();
            return $this->ErrorInfo;
        } else {
            $this->clearAllRecipients();
            $this->clearAttachments();
            return true;
        }
    }
}
