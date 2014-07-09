<?php
class DBImpl {    
    static function db_connect($host, $usr, $pwd, $databasename) {
        $db_dialect = SysProperties::getPropertyValue('bancodados.drive');
        $return = null;        
        switch ($db_dialect) {
            case 'mysql': { 
                $return = mysql_connect($host, $usr, $pwd);
                if ($return === FALSE) {
                    Logger::logerror(mysql_error());
                    return FALSE;
                }
                mysql_select_db($databasename, $return);
                break;
            }
            case 'mysqli': {
                $return = mysqli_connect($host, $usr, $pwd, $databasename);
                if ($return === FALSE) {
                    Logger::logerror(mysqli_error($return));
                    return FALSE;
                }
                break;
            }        
            case 'mssql': {
                $return = mssql_connect($host, $usr, $pwd);
                if ($return === FALSE) {
                    Logger::logerror(mssql_get_last_message());
                    return FALSE;
                }
                mssql_select_db($databasename);
                
                break;
            }       
            case 'sqlsrv': {
                $return = sqlsrv_connect($host, array('UID' => $usr, 'PWD' => $pwd, 'Database' => $databasename));
                if ($return === FALSE) {
                    $sql_errors = sqlsrv_errors();
                    Logger::logerror(print_r($sql_errors, TRUE));
                    return FALSE;
                }
                break;
            }
        }
        return $return;
    }
    
    static function db_query($query, $conn) {
        $db_dialect = SysProperties::getPropertyValue('bancodados.drive');
        $return = null;        
        switch ($db_dialect) {
            case 'mysql': {
                $return = mysql_query($query, $conn);
                if ($return === FALSE) {
                    Logger::logerror(mysql_error());
                    return FALSE;
                }
                break;
            }
            case 'mysqli': {
                $return = mysqli_query($conn, $query);
                if ($return === FALSE) {
                    Logger::logerror(mysqli_error($return));
                    return FALSE;
                }
                break;
            }        
            case 'mssql': {
                $return = mssql_query($query, $conn);
                if ($return === FALSE) {
                    Logger::logerror(mssql_get_last_message());
                    return FALSE;
                }
                break;
            }       
            case 'sqlsrv': {
                $return = sqlsrv_query($conn, $query);
                if ($return === FALSE) {
                    $sql_errors = sqlsrv_errors();
                    Logger::logerror(print_r($sql_errors, TRUE));
                    return FALSE;
                }
                break;
            }
        }
        return $return;
    }
    
    static function db_fetch_array($result) {
        $db_dialect = SysProperties::getPropertyValue('bancodados.drive');
        $return = null;        
        switch ($db_dialect) {
            case 'mysql': {
                $return = mysql_fetch_array($result);                
                break;
            }
            case 'mysqli': {
                $return = mysqli_fetch_array($result);
                break;
            }        
            case 'mssql': {
                $return = mssql_fetch_array($result);
                break;
            }       
            case 'sqlsrv': {
                $return = sqlsrv_fetch_array($result);
                break;
            }
        }
        return $return;
    }
    
    static function db_fetch_assoc($result) {
        $db_dialect = SysProperties::getPropertyValue('bancodados.drive');
        $return = null;        
        switch ($db_dialect) {
            case 'mysql': {
                $return = mysql_fetch_assoc($result);
                break;
            }
            case 'mysqli': {
                $return = mysqli_fetch_assoc($result);
                break;
            }        
            case 'mssql': {
                $return = mssql_fetch_assoc($result);
                break;
            }       
            case 'sqlsrv': {
                $return = false;
                break;
            }
        }
        return $return;
    }
    
    static function db_close($conn) {
       $db_dialect = SysProperties::getPropertyValue('bancodados.drive');
       switch ($db_dialect) {
            case 'mysql': {
                mysql_close($conn);
                break;
            }
            case 'mysqli': {
                mysqli_close($conn);
                break;
            }        
            case 'mssql': {
                mssql_close($conn);
                break;
            }       
            case 'sqlsrv': {
                sqlsrv_close($conn);
                break;
            }
        }
    }
    
    static function db_num_rows($result) {
        $db_dialect = SysProperties::getPropertyValue('bancodados.drive');
        $return = null;        
        switch ($db_dialect) {
            case 'mysql': {
                $return = mysql_num_rows($result);
                break;
            }
            case 'mysqli': {
                $return = mysqli_num_rows($result);
                break;
            }        
            case 'mssql': {
                $return = mssql_num_rows($result);
                break;
            }       
            case 'sqlsrv': {
                $return = sqlsrv_num_rows($result);
                break;
            }
        }
        return $return;
    }
    
    static function db_execute($query, $conn) {
        $db_dialect = SysProperties::getPropertyValue('bancodados.drive');
        $return = null;        
        switch ($db_dialect) {
            case 'mysql': {
                $return = mysql_query($query, $conn);
                if ($return === FALSE) {
                    Logger::logerror(mysql_error());
                    return FALSE;
                }
                break;
            }
            case 'mysqli': {
                $return = mysqli_query($conn, $query);
                if ($return === FALSE) {
                    Logger::logerror(mysqli_error($return));
                    return FALSE;
                }
                break;
            }        
            case 'mssql': {
                $return = mssql_execute($query, $conn);
                if ($return === FALSE) {
                    Logger::logerror(mssql_get_last_message());
                    return FALSE;
                }
                break;
            }       
            case 'sqlsrv': {
            	$stmt = sqlsrv_prepare($conn, $query);
            	if ($stmt === FALSE) {
            		$sql_errors = sqlsrv_errors();
            		Logger::logerror(print_r($sql_errors, TRUE));
            		return FALSE;
            	}
                $return = sqlsrv_execute($stmt);
                if ($return === FALSE) {
                    $sql_errors = sqlsrv_errors();
                    Logger::logerror(print_r($sql_errors, TRUE));
                    return FALSE;
                }
                break;
            }
        }
        return $return;
    } 
}

?>
