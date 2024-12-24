<?php namespace framework;

class ModelSmtpMail extends PHPMailer
{
    private $messages = "";


    public function __construct($host, $port, $userName, $password)
    {
        $this->isSMTP();
        $this->Timeout = 5;
        $this->Host = $host;
        $this->Port = $port;
        $this->SMTPAuth = true;
        $this->Username = $userName;
        $this->Password = $password;
        $this->SMTPDebug = 2;
        $this->Debugoutput = function ($message, $level)
        {
            $this->messages .= $message;
        };
    }

    public function sendMessage($fromAddress, $fromName, $toAddress, $toName, $subject, $htmlMessage, $plainTextMessage="", $debug=false) {
        $log = new ModelLogger("smtpMail");
        $log->writeMessage("Send message");
        $log->writeMessage("From email : {$fromAddress}");
        $log->writeMessage("From name  : {$fromName}");
        $log->writeMessage("To email   : {$toAddress}");
        $log->writeMessage("To name    : {$toName}");
        $log->writeMessage("To email   : {$subject}");
        $this->setFrom($fromAddress, $fromName);
        $this->addAddress($toAddress, $toName);
        $this->Subject = $subject;
        if ($plainTextMessage == "") {
            $this->IsHTML(true);
        }
        else {
            $this->AltBody = $plainTextMessage;
        }
        $this->msgHTML($htmlMessage);
        $result = $this->send();
        $log->writeMessage("Send result: " . var_export($result, true));
        if (!$result || $debug)
        {
            $log->writeMessage("Mail Error: " . $this->ErrorInfo);
            $log->writeMessage($this->messages);
        }
        return $result;
    }
}
