<?php # This is a set of functions you can use to make the most of Socialize This's code.

// Change time to "3 hours ago"
if(!function_exists("st_timespan")){function st_timespan($tm,$rcs = 0) { # http://www.php.net/manual/en/function.time.php#91864
    $cur_tm = time(); $dif = $cur_tm-$tm;
    $pds = array('second','minute','hour','day','week','month','year','decade');
    $lngh = array(1,60,3600,86400,604800,2630880,31570560,315705600);
    for($v = sizeof($lngh)-1; ($v >= 0)&&(($no = $dif/$lngh[$v])<=1); $v--); if($v < 0) $v = 0; $_tm = $cur_tm-($dif%$lngh[$v]);
   
    $no = floor($no); if($no <> 1) $pds[$v] .='s'; $x=sprintf("%d %s ",$no,$pds[$v]);
    if(($rcs == 1)&&($v >= 1)&&(($cur_tm-$_tm) > 0)) $x .= time_ago($_tm);
    return $x;
}}

// Changes tweets to contain links.
if(!function_exists("st_AddLinksToTweet")){function st_AddLinksToTweet($text){ # http://bavotasan.com/tutorials/turn-plain-text-urls-into-active-links-using-php/
	$text = ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]","<a href=\"\\0\" rel=\"nofollow\" title=\"\\0\">\\0</a>", $text);
	$text = preg_replace('#\@([_A-Za-z0-9-]+)#ism', '<a href="http://www.twitter.com/$1" rel="nofollow" title="See @$1 on Twitter">@$1</a>', $text);
	$text = preg_replace('#\#([_A-Za-z0-9-]+)#ism', '<a href="http://www.twitter.com//#!/search?q=%23$1" rel="nofollow" title="Search for @$1 on Twitter">#$1</a>', $text);
	return $text;
}}

// This gets/caches recent tweets of a user. 
if(!function_exists("st_getTweets")){function st_getTweets($useCache=TRUE, $username=NULL){
	if($username == NULL){$username = get_option('st_noti_twitter_user');}
	$tweets_parsed = NULL; 
	if($useCache == TRUE && get_option('cachetime_getTweets_'.$username) >= mktime(date("H")-1,  date("i"), 0, date("m")  , date("d"), date("Y"))){
		$tweets_parsed =  unserialize(get_option('cache_getTweets_'.$username));
	}
	if(!is_array($tweets_parsed) || $tweets_parsed == ''){
		global $ql; $i = 0;
		$tweets = json_decode($ql->t->getTimeline($username, 25));
		if(is_array($tweets)){foreach($tweets as $tweet){
			if($tweet->in_reply_to_screen_name == NULL){
				$i++;
				$tweets_parsed[$i]['text'] = st_AddLinksToTweet($tweet->text);
				$tweets_parsed[$i]['screen_name'] = $tweet->user->screen_name;
				$tweets_parsed[$i]['created_at'] = strtotime($tweet->created_at);
				$tweets_parsed[$i]['id'] = number_format($tweet->id, 0,'.','');	
			}
		}}
		update_option('cache_getTweets_'.$username, serialize($tweets_parsed));
		update_option('cachetime_getTweets_'.$username, time());
	}
	$i = 0;
	if(is_array($tweets_parsed)){foreach($tweets_parsed as $id => $tweet){$i++;
		echo '<p>'.$tweet['text'].'<br><em><a href="http://twitter.com/'.$tweet['screen_name'].'/status/'.$tweet['id'].'" rel="nofollow" title="See the Tweet on Twitter">Tweeted '.st_timespan($tweet['created_at']).' ago</a></em></p>';
		if($i >= 5){
			break;
		}
	}}
}}

// Twitter Data
if(!function_exists("st_getTwitterData")){
function st_getTwitterData($post=null){
	if($post == null){global $post;}
	return get_post_meta($post->ID, 'st_twitter');
}}

// Reddit Data
if(!function_exists("st_getRedditData")){function st_getRedditData($post=null){
	if($post == null){global $post;}
	return get_post_meta($post->ID, 'st_reddit');
}}

// Facebook Data
if(!function_exists("st_getFacebookData")){function st_getFacebookData($post=null){
	if($post == null){global $post;}
	return get_post_meta($post->ID, 'st_facebook');
}}

// Google Plus (+1) Data
if(!function_exists("st_getGooglePlusData")){function st_getGooglePlusData($post=null){
	if($post == null){global $post;}
	return get_post_meta($post->ID, 'st_googleplus');
}}
?>
