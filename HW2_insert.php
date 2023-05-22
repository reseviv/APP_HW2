<?php
//datatype:json & charset:utf-8
header('Content-Type: application/json; charset=UTF-8'); 

	//------------------//
//echo strlen(@$_POST['vid']);
//當發票沒有滿8碼不可以新增，免得對獎錯誤
	if(@$_POST['vid'] != "" && strlen(@$_POST['vid'])==8)
	{

		$vid=$_POST['vid'];	
		//突然發現POST[]裡面用單雙引號都可以哈哈	
	}
	else
	{
		$vid="";
	}

//read DB
	//mysqli(servername,username,pwd,dbname)
	$conn = new mysqli("192.168.2.200","fangbib1_123","123","fangbib1_testinvoice");

	//checkconnection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}


	
	if($vid !="")
	{
		//一開始都先設定成還沒對講，等使用者要對獎時再按對獎的button(in app layout)
		$sql = "INSERT INTO `pocket`(`vid`, `statue`) VALUES (".@$vid.",'not yet award'); ";
		$result = $conn->query($sql);
	}

	$messageArr = array();
	$dataarray= array();	

	@$num_rows='';
	
	if (isset($result->num_rows) && ($result->num_rows >0)) {
		// 輸出 每一個資料row

		while($row = $result->fetch_assoc()) {
		
			$vid=$row["vid"];
			$statue=$row["statue"];
			
		}
	} 

	$conn->close();
	//------------------------------------------------
	//輸出結果JSON格式		
	$dataarray= array(
	"vid" => @$vid,//
	"statue" => "not yet award",//	
	);	
	$messageArr["data"] = $dataarray;
	//------------------------------------------------------
	//送入時間格式
    $messageArr["status"] = array();
	date_default_timezone_set('America/La_Paz');
	$today = date('Y-m-d\TH:i:sP');//RFC3339格式
	$datetime= array(
	"code" => "0",
	"message" => "Success Insert Message",
	"datetime" => $today
	);	
	$messageArr["status"] = $datetime;	

//長度沒有8碼就回傳error
if(!empty('vid') && strlen(@$_POST["vid"])==8)
{
	http_response_code(200);
    echo json_encode($messageArr);	
}
else
{		
	http_response_code(404);	

	//因為沒有帳號，我們就預設讓它為空陣列
	$messageArr["data"] =[];

	$messageArr["status"] = array();
	date_default_timezone_set('America/La_Paz');
	$today = date('Y-m-d\TH:i:sP');//RFC3339 format
	$datetime= array(
	"code" => "404",
	"message" => "Error vid is null",
	"datetime" => $today
	);	
	$messageArr["status"] = $datetime;
	echo json_encode($messageArr);	
}
//echo json_encode($messageArr);	
?>