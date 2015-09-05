<?php
$route = '/task/';
$app->post($route, function () use ($app){

	$Add = 1;
	$ReturnObject = array();

 	$request = $app->request();
 	$params = $request->params();

	if(isset($params['post_date'])){ $post_date = mysql_real_escape_string($params['post_date']); } else { $post_date = date('Y-m-d H:i:s'); }
	if(isset($params['title'])){ $title = mysql_real_escape_string($params['title']); } else { $title = 'No Title'; }
	if(isset($params['url'])){ $url = mysql_real_escape_string($params['url']); } else { $url = ''; }
	if(isset($params['body'])){ $body = mysql_real_escape_string($params['body']); } else { $body = ''; }

  $Query = "SELECT * FROM task WHERE Title = '" . $title . "'";
	//echo $Query . "<br />";
	$Database = mysql_query($Query) or die('Query failed: ' . mysql_error());

	if($Database && mysql_num_rows($Database))
		{
		$ThisTask = mysql_fetch_assoc($Database);
		$task_id = $ThisTask['ID'];
		}
	else
		{
		$Query = "INSERT INTO task(Post_Date,Title,Author,Summary,Body,Footer,News_ID)";
		$Query .= " VALUES(";
		$Query .= "'" . mysql_real_escape_string($post_date) . "',";
		$Query .= "'" . mysql_real_escape_string($title) . "',";
		$Query .= "'" . mysql_real_escape_string($url) . "',";
		$Query .= "'" . mysql_real_escape_string($body) . "'";
		$Query .= ")";
		//echo $Query . "<br />";
		mysql_query($Query) or die('Query failed: ' . mysql_error());
		$task_id = mysql_insert_id();
		}

	$host = $_SERVER['HTTP_HOST'];
	$task_id = prepareIdOut($task_id,$host);

	$ReturnObject['task_id'] = $task_id;

	$app->response()->header("Content-Type", "application/json");
	echo format_json(json_encode($ReturnObject));

	});
?>
