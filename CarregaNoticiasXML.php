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
$xmlload = new xmlLoad();
 
$arquivo = (string)$_POST['arquivo'];

Logger::loginfo("lendo XML: $arquivo");
$xmldata = $xmlload->loadXML($arquivo);
Logger::loginfo("Total de registro processados para o arquivo xml: ". count($xmldata));
$sys = new SysProperties();
$dbcon = array(
	"host" => (string)$sys->getPropertyValue("bancodados.servidor"),
	"usr" => (string)$sys->getPropertyValue("bancodados.usuario"),
	"pwd" => (string)$sys->getPropertyValue("bancodados.senha"),
	"database" => (string)$sys->getPropertyValue("bancodados.basedados")
);
$db = new DBImpl();
$conn = $db->db_connect($dbcon["host"], $dbcon["usr"], $dbcon["pwd"], $dbcon["database"]);

if (!is_null($xmldata)) {
	foreach ($xmldata as $noticia) {
		
		$sql = "exec [dbo].[InsereNoticia] " .
				"'".iconv("UTF-8", "ISO-8859-1//TRANSLIT", (string)$noticia->Page_Title)."'," . 
				"null," . 
				"'".(string)$noticia->Imagem1."',".
				"null," .
				"null," .
				"null," .
				"'".iconv("UTF-8", "ISO-8859-1//TRANSLIT", (string)$noticia->NoticiaCompleta)."',".
				"0,".
				"'".date("Y-m-d")."',".
				"'".(string)$noticia->Page_URL."',". 
				"null," . 
				"null";
		
		$db->db_execute($sql, $conn);
		Logger::loginfo("registro processado(".iconv("UTF-8", "ISO-8859-1//TRANSLIT", (string)$noticia->Page_Title).")");
		echo "registro processado(".iconv("UTF-8", "ISO-8859-1//TRANSLIT", (string)$noticia->Page_Title).")";
	}
}
$db->db_close($conn);
echo "</pre>".PHP_EOL;

exit(0);
