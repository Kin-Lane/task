<?php
$route = '/task/:task_id/';
$app->get($route, function ($task_id)  use ($app){

	$host = $_SERVER['HTTP_HOST'];
	$task_id = prepareIdIn($task_id,$host);

	$ReturnObject = array();

	$Query = "SELECT * FROM task WHERE ID = " . $task_id;
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

		$task_id = prepareIdOut($task_id,$host);

		$F = array();
		$F['task_id'] = $task_id;
		$F['post_date'] = $post_date;
		$F['title'] = $title;
		$F['body'] = $body;
		$F['url'] = $url;
		$F['status'] = $status;

		$ReturnObject = $F;
		}

		$app->response()->header("Content-Type", "application/json");
		echo format_json(json_encode($ReturnObject));
	});
?>
