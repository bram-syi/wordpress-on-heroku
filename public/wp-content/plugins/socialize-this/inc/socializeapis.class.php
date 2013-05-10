<?php

// TweetMeme Class made by Mike Rogers
if (!class_exists('SocializeAPIs')) {

    class SocializeAPIs {

        public $headerInfo, $results;

        private function apiCall($url, $httpHeaders=null, $postFields=null) {
            if (function_exists('curl_init')) {
                $ch = curl_init();
                //curl_setopt($ch, CURLOPT_VERBOSE, 1);
                //curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                //curl_setopt($ch, CURLOPT_PORT, 80);
                if($postFields != null){
		            curl_setopt($ch, CURLOPT_POST, true);
		            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
                }
                if($httpHeaders != null){
					curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeaders);
				}
                curl_setopt($ch, CURLOPT_URL, $url);

                $this->results = curl_exec($ch);
                $this->headerInfo = curl_getinfo($ch);
                curl_close($ch);
                
                if ($this->headerInfo['http_code'] == 200) {
                    return $this->results;
                }
                return FALSE;
            }
            return FALSE;
        }

        public function TweetMeme($url) {
            $data = unserialize($this->apiCall('http://api.tweetmeme.com/url_info.php?url=' . urlencode($url)));
            return array('tm_link' => $data['story']['tm_link'], 'url_count' => (int) $data['story']['url_count']); 
        }

        public function Reddit($url) {
            $data = json_decode($this->apiCall('http://www.reddit.com/api/info.json?url=' . urlencode($url)));
            if ($data->data->modhash != '') {
                $data = $data->data->children[0]->data;
                return array('permalink' => (string) $data->permalink, 'score' => (int) $data->score, 'num_comments' => (int) $data->num_comments);
            } else {
                return array('permalink' => '', 'score' => 0, 'num_comments' => 0);
            }
        }
        
        # http://www.johndyer.name/post/Getting-Counts-Twitter-Links-Facebook-Likes-Shares-and-Google-Plus-One-Buttons.aspx
        // Tweets containing URL
        public function Twitter($url) {
            $data = json_decode($this->apiCall('http://urls.api.twitter.com/1/urls/count.json?url=' . urlencode($url)), true);
            if(isset($data['count'])){
            	return (int) $data['count'];
            }
            return 0;
        }
        
        // Facebook likes.
        public function FacebookLikes($url) {
            $data = json_decode($this->apiCall('http://graph.facebook.com/?ids=' . urlencode($url)), true);
            if(isset($data[$url]['shares'])){
            	return (int) $data[$url]['shares'];
            }
            return 0;
        }
        
        // Google Plus +1's
        public function GooglePlusOnes($url) {
            $data = json_decode($this->apiCall('https://clients6.google.com/rpc?key=AIzaSyCKSbrvQasunBoV16zDH9R33D88CeLr9gQ', array( 'Content-type: application/json'), '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $url . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]'), true);
            if(isset($data[0]['result']['metadata']['globalCounts']['count'])){
            	return (int) $data[0]['result']['metadata']['globalCounts']['count'];
            }
            return 0;
        }
        
        // Google Reader Feed
        /*public function GoogleReader($url) {
            $data = json_decode($this->apiCall('http://www.google.com/reader/api/0/stream/details?s=feed/'.urlencode($url).'&output=json&fetchTrends=false', null, $headers), true);
            return (int) $data['subscribers'];
        }*/
    }

}
?>
