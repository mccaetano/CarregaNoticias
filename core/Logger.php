<?php
class Logger {
    protected static $mail = "Email automatico enviado pelo sistema.\n\n Mensagem:\n %s.\n\nFavor não responder esse email";
    public static function logerror($message) {
    	Logger::FileTrashHolder("error.txt");
        $level = SysProperties::getPropertyValue("logger.level");
        $sendmail = SysProperties::getPropertyValue("logger.sendmail");
        if (((int)$level) <= 3) {
	        $errorheader = date("Y-m-d - H:i:s.u") . " - [ERROR  ] - " . $message . PHP_EOL;
	        error_log($errorheader, 3, "error.txt");
	        $to = SysProperties::getPropertyValue("logger.mailto");
	        if ($sendmail) {
	        	Logger::SendMail($message);
	        }
        }
    }

    public static function loginfo($message) {
    	Logger::FileTrashHolder("info.txt");
    	$level = SysProperties::getPropertyValue("logger.level");
        $sendmail = SysProperties::getPropertyValue("logger.sendmail");
        if (((int)$level) <= 1) {
	        $errorheader = date("Y-m-d - H:i:s.u") . " - [INFO   ] - " . $message . PHP_EOL;
	        error_log($errorheader, 3, "info.txt");
        }
    }

    public static function logwarn($message) {
    	Logger::FileTrashHolder("error.txt");
    	$level = SysProperties::getPropertyValue("logger.level");
        $sendmail = SysProperties::getPropertyValue("logger.sendmail");
        if (((int)$level) <= 2) {
	        $errorheader = date("Y-m-d - H:i:s.u") . " - [WARNING] - " . $message . PHP_EOL;
	        error_log($errorheader, 3, "error.txt");
	        if ($sendmail) {
	        	Logger::SendMail($message);
	        }
        }
    }
    
    public static function SendMail($message) {    	
    	$logfile = "error.txt";
    	$errorheader = date("Y-m-d - H:i:s.u") . " - [INFO   ] - " . $message . "\n";
    	
    	$phpMailer = new PHPMailer();
    	$phpMailer->IsSMTP();
    	$phpMailer->Host  = SysProperties::getPropertyValue("mail.host");
    	$phpMailer->Port  = SysProperties::getPropertyValue("mail.port");
    	$phpMailer->SMTPAuth = true;
    	$phpMailer->Username  = SysProperties::getPropertyValue("mail.usuario");
    	$phpMailer->Password  = SysProperties::getPropertyValue("mail.senha");
    	$phpMailer->SetFrom(SysProperties::getPropertyValue("mail.from"));
    	$phpMailer->FromName   = SysProperties::getPropertyValue("mail.from");
    	$mailTos = SysProperties::getPropertyValue("logger.mailto");
    	$mailTos = explode(",", $mailTos);
    	for ($i=0; $i<count($mailTos); $i++) {
    		$phpMailer->AddAddress($mailTos[$i], $mailTos[$i]);
    	}    	
    	$phpMailer->Subject = "Mesnagem do Sistema de Integra��o Quabarto";
    	$phpMailer->AltBody = sprintf(Logger::$mail , $message);
    	$phpMailer->IsHTML(false);
    	$phpMailer->Body = sprintf(Logger::$mail , $message);
    	if(!$phpMailer->Send())
    	{
    		error_log($errorheader, 3, $logfile);    	
    	}
    }
    
    public static function FileTrashHolder($logfile) {
    	if (file_exists($logfile)) {
	    	if (filesize($logfile) > 1048576) {
	    		copy($logfile, $logfile . "." . date("YmD_His") . ".bkp");
	    		unlink($logfile);
	    	}
    	}    	
    }
}
?>