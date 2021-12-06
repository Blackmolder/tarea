<?php 
header("Content-type: text/html; charset=latin1");
session_name('LINEA');
session_start();
require_once 'class.conexion.php';
require_once 'Connections/class.rc4crypt.php';
require_once("funciones.php");

$rut 		= $_POST['rut'];
$estado 	= $_POST['estado'];
$fecha_cp	= $_POST['fecha_cp'];
$glosa 		= $_POST['glosa'];
	

$rc4 = new rc4crypt();
$sUsuario = $rc4->decrypt($_SESSION['cy_us']);
$sPassword = $rc4->decrypt($_SESSION['cy_pas']);
$sBase = $rc4->decrypt($_SESSION['cy_base']);
$sPerfil = $rc4->decrypt($_SESSION['cy_perfil']);
$nUsuario= $rc4->decrypt($_SESSION['cy_usercod']);
$cnn = new conexion($sUsuario, $sPassword, $sBase);
$enlace_base = $cnn->enlace_principal;
$enlace_btc = $cnn->enlace_BTC;

if($estado==56 and $fecha_cp==''){$var_11 = ", CLIFCOM=CURDATE() ";$sGlosa = "E39 a E".$estado.": ".$glosa;}
else{$var_11 = ", CLIFCOM='".$fecha_cp."'";$sGlosa = "E35 a E".$estado.": ".$glosa;}

	$sActualiza = "UPDATE COBVEC SET CLIEST2=".$estado.", CLIFEST=CURDATE() ".$var_11." WHERE CLIRUT=".$rut; 
	//echo $sActualiza;
	if( $sql1 = @mysql_query($sActualiza, $enlace_base) ){
		if( mysql_affected_rows() != 0 ){ 
		//$sGlosa = "E35 a E".$estado.": ".$glosa;
		$ngescod1n=1201;
		$ngescod2n=0;
		if($sBase == 'BBVACJ'){
			if ($estado == '2'){$ngescod2n=70;}
			else if ($estado == '56'){$ngescod2n=102;}
			}
		else{
			if ($estado == '2'){$ngescod2n=33;}
			else if ($estado == '56'){$ngescod2n=45;}
			}
		$ngescod3n=13;
		//$ngescod3n=obtieneRANKBBVA($rut,$enlace_base);
		grabagestio2($rut,'',0,0,1463,$sGlosa,$fecha_cp,$ngescod1n,$ngescod2n,$ngescod3n,
		'',0,0,0,'','','','',$nUsuario,$enlace_base,$sBase,$enlace_btc );
		echo "Cambio de Estado Realizado Correctamente.";}
		else {echo "NO se pudo actualizar Estado, ERROR.";}
		}
	else {echo "NO se pudo actualizar Estado, ERROR: ".mysql_error($enlace_base);}

//echo $sActualiza;

$cnn->cerrar_enlaces();

function obtieneRANKBBVA($rut,$enlace_base)
{
		$sSql = "SELECT RANKBBVA FROM COBVEC WHERE CLIRUT=".$rut." limit 1";
		$sqlcampos	= mysql_query($sSql, $enlace_base);
		if ($rowcampos = mysql_fetch_array($sqlcampos)){$rankbbva=$rowcampos['CLIMOT'];}
		if ($rankbbva==''){$rankbbva=0;}
		return $rankbbva;
}
?>