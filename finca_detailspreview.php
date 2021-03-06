<?php
@ini_set("display_errors","1");
@ini_set("display_startup_errors","1");

require_once("include/dbcommon.php");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 

require_once("include/finca_variables.php");

$mode = postvalue("mode");

if(!isLogged())
{ 
	return;
}
if(!CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Search"))
{
	return;
}

require_once("classes/searchclause.php");

$cipherer = new RunnerCipherer($strTableName);

require_once('include/xtempl.php');
$xt = new Xtempl();





$layout = new TLayout("detailspreview_bootstrap", "AvenueDeliciousGray", "MobileDeliciousGray");
$layout->version = 3;
	$layout->bootstrapTheme = "cerulean";
$layout->blocks["bare"] = array();
$layout->containers["dcount"] = array();
$layout->container_properties["dcount"] = array(  );
$layout->containers["dcount"][] = array("name"=>"bsdetailspreviewcount",
	"block"=>"", "substyle"=>1  );

$layout->skins["dcount"] = "";

$layout->blocks["bare"][] = "dcount";
$layout->containers["detailspreviewgrid"] = array();
$layout->container_properties["detailspreviewgrid"] = array(  );
$layout->containers["detailspreviewgrid"][] = array("name"=>"detailspreviewfields",
	"block"=>"details_data", "substyle"=>1  );

$layout->skins["detailspreviewgrid"] = "";

$layout->blocks["bare"][] = "detailspreviewgrid";
$page_layouts["finca_detailspreview"] = $layout;




$recordsCounter = 0;

//	process masterkey value
$mastertable = postvalue("mastertable");
$masterKeys = my_json_decode(postvalue("masterKeys"));
$sessionPrefix = "_detailsPreview";
if($mastertable != "")
{
	$_SESSION[$sessionPrefix."_mastertable"]=$mastertable;
//	copy keys to session
	$i = 1;
	if(is_array($masterKeys) && count($masterKeys) > 0)
	{
		while(array_key_exists ("masterkey".$i, $masterKeys))
		{
			$_SESSION[$sessionPrefix."_masterkey".$i] = $masterKeys["masterkey".$i];
			$i++;
		}
	}
	if(isset($_SESSION[$sessionPrefix."_masterkey".$i]))
		unset($_SESSION[$sessionPrefix."_masterkey".$i]);
}
else
	$mastertable = $_SESSION[$sessionPrefix."_mastertable"];

$params = array();
$params['id'] = 1;
$params['xt'] = &$xt;
$params['tName'] = $strTableName;
$params['pageType'] = "detailspreview";
$pageObject = new DetailsPreview($params);

if($mastertable == "propietario")
{
	$where = "";
		$formattedValue = make_db_value("PROPIETARIO_CEDULA",$_SESSION[$sessionPrefix."_masterkey1"]);
	if( $formattedValue == "null" )
		$where .= $pageObject->getFieldSQLDecrypt("PROPIETARIO_CEDULA") . " is null";
	else
		$where .= $pageObject->getFieldSQLDecrypt("PROPIETARIO_CEDULA") . "=" . $formattedValue;
}

$str = SecuritySQL("Search", $strTableName);
if(strlen($str))
	$where.=" and ".$str;
$strSQL = $gQuery->gSQLWhere($where);

$strSQL.=" ".$gstrOrderBy;

$rowcount = $gQuery->gSQLRowCount($where, $pageObject->connection);
$xt->assign("row_count",$rowcount);
if($rowcount) 
{
	$xt->assign("details_data",true);

	$display_count = 10;
	if($mode == "inline")
		$display_count*=2;
		
	if($rowcount>$display_count+2)
	{
		$xt->assign("display_first",true);
		$xt->assign("display_count",$display_count);
	}
	else
		$display_count = $rowcount;

	$rowinfo = array();
	
	require_once getabspath('classes/controls/ViewControlsContainer.php');
	$pSet = new ProjectSettings($strTableName, PAGE_LIST);
	$viewContainer = new ViewControlsContainer($pSet, PAGE_LIST);
	$viewContainer->isDetailsPreview = true;

	$b = true;
	$qResult = $pageObject->connection->query( $strSQL );
	$data = $cipherer->DecryptFetchedArray( $qResult->fetchAssoc() );
	while($data && $recordsCounter<$display_count) {
		$recordsCounter++;
		$row = array();
		$keylink = "";
		$keylink.="&key1=".runner_htmlspecialchars(rawurlencode(@$data["ID"]));
		$keylink.="&key2=".runner_htmlspecialchars(rawurlencode(@$data["PROPIETARIO_CEDULA"]));
	
	
	//	ID - 
			$viewContainer->recId = $recordsCounter;
		    $value = $viewContainer->showDBValue("ID", $data, $keylink);
			$row["ID_value"] = $value;
			$format = $pSet->getViewFormat("ID");
			$class = "rnr-field-text";
			if($format==FORMAT_FILE) 
				$class = ' rnr-field-file'; 
			if($format==FORMAT_AUDIO)
				$class = ' rnr-field-audio';
			if($format==FORMAT_CHECKBOX)
				$class = ' rnr-field-checkbox';
			if($format==FORMAT_NUMBER || IsNumberType($pSet->getFieldType("ID")))
				$class = ' rnr-field-number';
			$row["ID_class"] = $class;
	//	PROPIETARIO_CEDULA - 
			$viewContainer->recId = $recordsCounter;
		    $value = $viewContainer->showDBValue("PROPIETARIO_CEDULA", $data, $keylink);
			$row["PROPIETARIO_CEDULA_value"] = $value;
			$format = $pSet->getViewFormat("PROPIETARIO_CEDULA");
			$class = "rnr-field-text";
			if($format==FORMAT_FILE) 
				$class = ' rnr-field-file'; 
			if($format==FORMAT_AUDIO)
				$class = ' rnr-field-audio';
			if($format==FORMAT_CHECKBOX)
				$class = ' rnr-field-checkbox';
			if($format==FORMAT_NUMBER || IsNumberType($pSet->getFieldType("PROPIETARIO_CEDULA")))
				$class = ' rnr-field-number';
			$row["PROPIETARIO_CEDULA_class"] = $class;
	//	NOMBRE - 
			$viewContainer->recId = $recordsCounter;
		    $value = $viewContainer->showDBValue("NOMBRE", $data, $keylink);
			$row["NOMBRE_value"] = $value;
			$format = $pSet->getViewFormat("NOMBRE");
			$class = "rnr-field-text";
			if($format==FORMAT_FILE) 
				$class = ' rnr-field-file'; 
			if($format==FORMAT_AUDIO)
				$class = ' rnr-field-audio';
			if($format==FORMAT_CHECKBOX)
				$class = ' rnr-field-checkbox';
			if($format==FORMAT_NUMBER || IsNumberType($pSet->getFieldType("NOMBRE")))
				$class = ' rnr-field-number';
			$row["NOMBRE_class"] = $class;
	//	VEREDA - 
			$viewContainer->recId = $recordsCounter;
		    $value = $viewContainer->showDBValue("VEREDA", $data, $keylink);
			$row["VEREDA_value"] = $value;
			$format = $pSet->getViewFormat("VEREDA");
			$class = "rnr-field-text";
			if($format==FORMAT_FILE) 
				$class = ' rnr-field-file'; 
			if($format==FORMAT_AUDIO)
				$class = ' rnr-field-audio';
			if($format==FORMAT_CHECKBOX)
				$class = ' rnr-field-checkbox';
			if($format==FORMAT_NUMBER || IsNumberType($pSet->getFieldType("VEREDA")))
				$class = ' rnr-field-number';
			$row["VEREDA_class"] = $class;
	//	FINCA - 
			$viewContainer->recId = $recordsCounter;
		    $value = $viewContainer->showDBValue("FINCA", $data, $keylink);
			$row["FINCA_value"] = $value;
			$format = $pSet->getViewFormat("FINCA");
			$class = "rnr-field-text";
			if($format==FORMAT_FILE) 
				$class = ' rnr-field-file'; 
			if($format==FORMAT_AUDIO)
				$class = ' rnr-field-audio';
			if($format==FORMAT_CHECKBOX)
				$class = ' rnr-field-checkbox';
			if($format==FORMAT_NUMBER || IsNumberType($pSet->getFieldType("FINCA")))
				$class = ' rnr-field-number';
			$row["FINCA_class"] = $class;
	//	ALTURA - 
			$viewContainer->recId = $recordsCounter;
		    $value = $viewContainer->showDBValue("ALTURA", $data, $keylink);
			$row["ALTURA_value"] = $value;
			$format = $pSet->getViewFormat("ALTURA");
			$class = "rnr-field-text";
			if($format==FORMAT_FILE) 
				$class = ' rnr-field-file'; 
			if($format==FORMAT_AUDIO)
				$class = ' rnr-field-audio';
			if($format==FORMAT_CHECKBOX)
				$class = ' rnr-field-checkbox';
			if($format==FORMAT_NUMBER || IsNumberType($pSet->getFieldType("ALTURA")))
				$class = ' rnr-field-number';
			$row["ALTURA_class"] = $class;
	//	GEOREFERENCIA - 
			$viewContainer->recId = $recordsCounter;
		    $value = $viewContainer->showDBValue("GEOREFERENCIA", $data, $keylink);
			$row["GEOREFERENCIA_value"] = $value;
			$format = $pSet->getViewFormat("GEOREFERENCIA");
			$class = "rnr-field-text";
			if($format==FORMAT_FILE) 
				$class = ' rnr-field-file'; 
			if($format==FORMAT_AUDIO)
				$class = ' rnr-field-audio';
			if($format==FORMAT_CHECKBOX)
				$class = ' rnr-field-checkbox';
			if($format==FORMAT_NUMBER || IsNumberType($pSet->getFieldType("GEOREFERENCIA")))
				$class = ' rnr-field-number';
			$row["GEOREFERENCIA_class"] = $class;
	//	AREA_FINCA - Number
			$viewContainer->recId = $recordsCounter;
		    $value = $viewContainer->showDBValue("AREA_FINCA", $data, $keylink);
			$row["AREA_FINCA_value"] = $value;
			$format = $pSet->getViewFormat("AREA_FINCA");
			$class = "rnr-field-text";
			if($format==FORMAT_FILE) 
				$class = ' rnr-field-file'; 
			if($format==FORMAT_AUDIO)
				$class = ' rnr-field-audio';
			if($format==FORMAT_CHECKBOX)
				$class = ' rnr-field-checkbox';
			if($format==FORMAT_NUMBER || IsNumberType($pSet->getFieldType("AREA_FINCA")))
				$class = ' rnr-field-number';
			$row["AREA_FINCA_class"] = $class;
	//	LINEA_PRINCIPAL - 
			$viewContainer->recId = $recordsCounter;
		    $value = $viewContainer->showDBValue("LINEA_PRINCIPAL", $data, $keylink);
			$row["LINEA_PRINCIPAL_value"] = $value;
			$format = $pSet->getViewFormat("LINEA_PRINCIPAL");
			$class = "rnr-field-text";
			if($format==FORMAT_FILE) 
				$class = ' rnr-field-file'; 
			if($format==FORMAT_AUDIO)
				$class = ' rnr-field-audio';
			if($format==FORMAT_CHECKBOX)
				$class = ' rnr-field-checkbox';
			if($format==FORMAT_NUMBER || IsNumberType($pSet->getFieldType("LINEA_PRINCIPAL")))
				$class = ' rnr-field-number';
			$row["LINEA_PRINCIPAL_class"] = $class;
	//	AREA_ L_P - Number
			$viewContainer->recId = $recordsCounter;
		    $value = $viewContainer->showDBValue("AREA_ L_P", $data, $keylink);
			$row["AREA__L_P_value"] = $value;
			$format = $pSet->getViewFormat("AREA_ L_P");
			$class = "rnr-field-text";
			if($format==FORMAT_FILE) 
				$class = ' rnr-field-file'; 
			if($format==FORMAT_AUDIO)
				$class = ' rnr-field-audio';
			if($format==FORMAT_CHECKBOX)
				$class = ' rnr-field-checkbox';
			if($format==FORMAT_NUMBER || IsNumberType($pSet->getFieldType("AREA_ L_P")))
				$class = ' rnr-field-number';
			$row["AREA__L_P_class"] = $class;
	//	AÑO - 
			$viewContainer->recId = $recordsCounter;
		    $value = $viewContainer->showDBValue("AÑO", $data, $keylink);
			$row["A_O_value"] = $value;
			$format = $pSet->getViewFormat("AÑO");
			$class = "rnr-field-text";
			if($format==FORMAT_FILE) 
				$class = ' rnr-field-file'; 
			if($format==FORMAT_AUDIO)
				$class = ' rnr-field-audio';
			if($format==FORMAT_CHECKBOX)
				$class = ' rnr-field-checkbox';
			if($format==FORMAT_NUMBER || IsNumberType($pSet->getFieldType("AÑO")))
				$class = ' rnr-field-number';
			$row["A_O_class"] = $class;
		$rowinfo[] = $row;
		if ($b) {
			$rowinfo2[] = $row;
			$b = false;
		}
		$data = $cipherer->DecryptFetchedArray( $qResult->fetchAssoc() );
	}
	$xt->assign_loopsection("details_row",$rowinfo);
	$xt->assign_loopsection("details_row_header",$rowinfo2); // assign class for header
}
$returnJSON = array("success" => true);
$xt->load_template(GetTemplateName("finca", "detailspreview"));
$returnJSON["body"] = $xt->fetch_loaded();

if($mode!="inline")
{
	$returnJSON["counter"] = postvalue("counter");
	$layout = GetPageLayout(GoodFieldName($strTableName), 'detailspreview');
	if($layout)
	{
		foreach($layout->getCSSFiles(isRTL(), mobileDeviceDetected() && $layout->version != BOOTSTRAP_LAYOUT) as $css)
		{
			$returnJSON['CSSFiles'][] = $css;
		}
	}	
}	

echo printJSON($returnJSON);
exit();
?>