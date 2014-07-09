<?php
mb_internal_encoding("ISO-8859-1");

require_once 'core/SysProperties.php';
require_once 'PHPMailer/class.smtp.php';
require_once 'PHPMailer/class.phpmailer.php';
require_once 'core/Logger.php';
require_once 'core/SessionCtrl.php';
require_once 'core/FileIO.php';
require_once 'core/xmlLoad.php';
require_once 'core/DBImpl.php';
require_once 'core/BancoDadosFactory.php';
require_once 'core/HTTPRequest.php';

SessionCtlr::StartLongSession();

echo "<pre>".PHP_EOL;

$dirin = SysProperties::getPropertyValue("carga.diretorio.leitura");
Logger::loginfo("Drietorior:" . $dirin);
echo "Diretório: " . realpath($dirin) . "<br/>";

$listaarquivos = array();
foreach (scandir($dirin) as $file) {
	if (strpos($file, ".xml")) {
		$listaarquivos[] = realpath($dirin . "/" . $file);
	}
}

Logger::loginfo("Total de arquivos a processar:" . count($listaarquivos));
echo "Total de arquivos a processar:" . count($listaarquivos)."<br>".PHP_EOL;

foreach ($listaarquivos as $arquivo) {

	$fields = array("arquivo" => $arquivo);
	echo "Arquivo encontrado: $arquivo<br>".PHP_EOL;
	Logger::loginfo("Arquivo encontrado: $arquivo");
	HTTPRequest::PostDataAssync("noticias/CarregaNoticiasXML.php", $fields);
}

echo "</pre>".PHP_EOL;

exit(0);
?>