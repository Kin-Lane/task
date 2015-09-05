<?php
$route = '/task/';
$app->get($route, function ()  use ($app,$Plan){

	$ReturnObject = array();

 	$request = $app->request();
 	$params = $request->params();

	if(isset($params['query'])){ $query = trim(mysql_real_escape_string($params['query'])); } else { $query = '';}
	if(isset($params['page'])){ $page = trim(mysql_real_escape_string($params['page'])); } else { $page = 0;}
	if(isset($params['count'])){ $count = trim(mysql_real_escape_string($params['count'])); } else { $count = 250;}
	if(isset($params['sort'])){ $sort = trim(mysql_real_escape_string($params['sort'])); } else { $sort = 'Title';}
	if(isset($params['order'])){ $order = trim(mysql_real_escape_string($params['order'])); } else { $order = 'DESC';}

	// Pull from MySQL
	if($query!='')
		{
		$Query = "SELECT * FROM task WHERE Title LIKE '%" . $query . "%' OR  Body LIKE '%" . $query . "%'";
		}
	else
		{
		$Query = "SELECT * FROM task";
		}
	$Query .= " ORDER BY " . $sort . " " . $order . " LIMIT " . $page . "," . $count;
	//echo $Query . "<br />";

	$DatabaseResult = mysql_query($Query) or die('Query failed: ' . mysql_error());

	while ($Database = mysql_fetch_assoc($DatabaseResult))
		{

		$task_id = $Database['task_id'];
		$post_date = $Database['post_date'];
		$title = $Database['title'];
		$body = $Database['body'];
    $url = $Database['url'];
		$status = $Database['status'];

		// manipulation zone

		$host = $_SERVER['HTTP_HOST'];
		$task_id = prepareIdOut($task_id,$host);

		if($Plan=="Public")
			{
			$F = array();
			$F['task_id'] = 0;
			$F['post_date'] = date();
			$F['title'] = "My tasks, not yours!"
			$F['body'] = "";
			$F['url'] = "http://apievangelist.com";
			$F['status'] = "active";			
			}
		else
			{
			$F = array();
			$F['task_id'] = $task_id;
			$F['post_date'] = $post_date;
			$F['title'] = $title;
			$F['body'] = $body;
			$F['url'] = $url;
			$F['status'] = $status;
			}
		array_push($ReturnObject, $F);
		}

		$app->response()->header("Content-Type", "application/json");
		echo format_json(json_encode($ReturnObject));
	});
?>
