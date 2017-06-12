<html>
  <head>
     <title>Twitter Login</title>
             <link href="css/bootstrap.css" rel="stylesheet">
			 <link href = "css/jquery-ui.css" rel="stylesheet">
			 <link href = "css/custom.css" rel="stylesheet">
	</head>
	<body>
       <div class="back">
       <h1>Hello! Twitter</h1>
       <form class="form-group" method="post" action="index.php" >
          <Button type="submit" class="btn btn-default" name="login"> Login with Twitter </Button>
       </form>
    </div>
	
<?php
session_start();
require 'autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

define('CONSUMER_KEY', 'your consumer key'); // add your app consumer key between single quotes
define('CONSUMER_SECRET', 'your consumer secret'); // add your app consumer secret key between single quotes
define('OAUTH_CALLBACK', 'your callback url'); // your app callback URL

if(isset($_POST['login'])){

if (!isset($_SESSION['access_token'])) {
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
	$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));
	$_SESSION['oauth_token'] = $request_token['oauth_token'];
	$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
	$url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
	header("Location:".$url);
} else {
	$access_token = $_SESSION['access_token'];
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
	$user = $connection->get("account/verify_credentials");
	echo  $user->name;
	echo  $user->screen_name;
	echo "<img src='".$user->profile_image_url."'/><br><br></div>";

	//now we are able to fetch the twitts from the user
	$tweets = $connection->get('statuses/user_timeline', ['count' => 20, 'exclude_replies' => true, 'include_rts' => false ]);
    //storing the tweets in empty array
    $array_tweets[] = $tweets;
    $page = 0;

    //looping for getting simplyfied form
    for($count = 20; $count < 50 ; $count += 10){
    	$max = count($array_tweets[$page]) - 1;
        $tweets = $connection->get('statuses/user_timeline', ['count' => 20, 'exclude_replies' => true, 'max_id' => $array_tweets[$page][$max]->id_str,  'include_rts' => false]);
		$array_tweets[] = $tweets;
		$page += 1;
	}
    
    //echo "<pre>";
	//print_r($array_tweets);
    //echo "<pre>";

    //now for printing twitts
    // printing recent tweets on screen
	$start = 1;
	foreach ($array_tweets as $page) {
		foreach ($page as $key) {
			echo $start . ':' . $key->text . '<br/>';
			$start++;
		}
	}

}}else{
	}?>

</body>
</html>

 