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
		$data_Criacao = new DateTime($noticia->Data);
		$sql = "exec [dbo].[InsereNoticia] " .
				"'".mb_convert_encoding((string)$noticia->Page_Title, "ISO-8859-1", "auto")."'," . 
				"null," . 
				"'".(string)$noticia->Imagem1."',".
				"'".(string)$noticia->Imagem2."',".
				"'".(string)$noticia->Imagem3."'," .
				"'".(string)$noticia->Imagem4."'," .
				"'".mb_convert_encoding((string)$noticia->NoticiaCompleta,"ISO-8859-1", "auto")."',".
				"0,".
				"'".date_format($data_Criacao, "Y-m-d")."',".
				"'".(string)$noticia->Page_URL."',". 
				"null," . 
				"null";
		
		$db->db_execute($sql, $conn);
		Logger::loginfo("registro processado(".mb_convert_encoding((string)$noticia->Page_Title, "ISO-8859-1", "auto").")");
		echo "registro processado(".mb_convert_encoding((string)$noticia->Page_Title, "ISO-8859-1", "auto").")";
	}
}
$db->db_close($conn);
echo "</pre>".PHP_EOL;

exit(0);
