<?php
//datatype:json & charset:utf-8
header('Content-Type: application/json; charset=UTF-8');
	if(@$_GET["vid"]!="")
	{
		$vid=$_GET['vid'];
	}
	else
	{
		$vid="null";
	}
	if(@$_GET["limit"] != "")
	{
		$limit=$_GET["limit"];	
		//echo $limit;
	}
	else
	{
		$limit="";
	}

//read DB
	//mysqli(servername,username,pwd,dbname)

	$conn = new mysqli("192.168.2.200","fangbib1_123","123","fangbib1_testinvoice");

	//checkconnection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	//下sql語法:查看自己所有的發票
	$sql = "SELECT * FROM `pocket`;";
    //echo $sql;
	$result = $conn->query($sql);
	$messageArr = array();
	$dataarray= array();	


	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
		
			$invoice=$row["vid"];
			$statue=$row["statue"];			
			//data逐一加入dataarray	
			$dataarray[] = $row;
			
		}
	} 


	//close connection
	$conn->close();

	//print result
	$messageArr["data"] = $dataarray;


	//送入時間格式
    $messageArr["status"] = array();
	date_default_timezone_set('America/La_Paz');
	$today = date('Y-m-d\TH:i:sP');//RFC3339格式
	$datetime= array(
	"code" => "0",
	"message" => "Success",
	"datetime" => $today
	);	
	$messageArr["status"] = $datetime;	


if(!empty($messageArr))
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

?>
