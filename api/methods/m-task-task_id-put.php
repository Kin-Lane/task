<?php
$route = '/task/:task_id/';
$app->put($route, function ($task_id) use ($app){

	$host = $_SERVER['HTTP_HOST'];
	$task_id = prepareIdIn($task_id,$host);

	$ReturnObject = array();

 	$request = $app->request();
 	$params = $request->params();

	if(isset($params['post_date'])){ $post_date = mysql_real_escape_string($params['post_date']); } else { $post_date = date('Y-m-d H:i:s'); }
	if(isset($params['title'])){ $title = mysql_real_escape_string($params['title']); } else { $title = 'No Title'; }
	if(isset($params['url'])){ $url = mysql_real_escape_string($params['url']); } else { $url = ''; }
	if(isset($params['body'])){ $body = mysql_real_escape_string($params['body']); } else { $body = ''; }

	$Query = "SELECT * FROM task WHERE task_id = " . $task_id;
	//echo $Query . "<br />";
	$Database = mysql_query($Query) or die('Query failed: ' . mysql_error());

	if($Database && mysql_num_rows($Database))
		{
		$query = "UPDATE task SET";

		$query .= " Title = '" . mysql_real_escape_string($title) . "'";

		if($url!='') { $query .= ", url = '" . $url . "'"; }
		if($body!='') { $query .= ", body = '" . $body . "'"; }

		$query .= " WHERE task_id = '" . $task_id . "'";

		//echo $query . "<br />";
		mysql_query($query) or die('Query failed: ' . mysql_error());
		}

	$task_id = prepareIdOut($task_id,$host);

	$F = array();
	$F['task_id'] = $task_id;
	$F['post_date'] = $post_date;
	$F['title'] = $title;
	$F['url'] = $url;
	$F['body'] = $body;

	array_push($ReturnObject, $F);

	$app->response()->header("Content-Type", "application/json");
	echo stripslashes(format_json(json_encode($ReturnObject)));

	});
?>
