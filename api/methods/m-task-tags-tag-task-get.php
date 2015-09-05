<?php
$route = '/task/tags/:tag/task/';
$app->get($route, function ($tag)  use ($app){

	$ReturnObject = array();

 	$request = $app->request();
 	$params = $request->params();

	if(isset($_REQUEST['week'])){ $week = $params['week']; } else { $week = date('W'); }
	if(isset($_REQUEST['year'])){ $year = $params['year']; } else { $year = date('Y'); }

	$Query = "SELECT b.* from tags t";
	$Query .= " JOIN task_tag_pivot btp ON t.Tag_ID = btp.Tag_ID";
	$Query .= " JOIN task b ON btp.Task_ID = b.ID";
	$Query .= " WHERE WEEK(b.Post_Date) = " . $week . " AND YEAR(b.Post_Date) = " . $year . " AND Tag = '" . $tag . "'";

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

		array_push($ReturnObject, $F);
		}

		$app->response()->header("Content-Type", "application/json");
		echo format_json(json_encode($ReturnObject));
	});
?>
