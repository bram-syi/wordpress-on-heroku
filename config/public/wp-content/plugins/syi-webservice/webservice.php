<?php
/**
 * Description: Script to provides webservice actions for the mobile application.
 * Version: 0.1b
 * Author: Aditi
 * @package Service
 * 
 * Purpose: SYI WebService to access SYI Wordpress blog.
 * Callers: SYI mobile devices
 * Returns: A response in XML.
 * Example: webservice.php?method=GetDonations&os=kojax&CharityId=16

 * 
 * Available Methods			Arguments:
 * ------------------			----------
 * GetCharities() 				os
 * GetDonations()				
 * UploadMediaForDonation()		
 * AcceptPost()					
 * RejectPost()					PostId, CharityId
 * 
 * Sample outputs and expected arguments.
 * Sample error message:
 *  <?xml version="1.0" encoding="utf-8"?>
 * <object id="body">
<string id="Status">Error</string>
<string id="Message">Class does not serve the method 'LogIO'. Available are GetCharities, GetDonations, UploadMediaForDonation, AcceptPost, RejectPost</string>
</object>
 * 
 * GetCharities() 	os
 * 
 *
 * 
 * <?xml version="1.0" encoding="utf-8"?>
 * <vector id="AuthKeys">
		<object id="AuthKey">
			<string id="CharityID">1</string>
			<string id="description">Charity</string>
			<string id="Key">1111</string>
		</object>
		<object id="AuthKey">
		...
	</vector>
	
	GetDonations()		os, CharityId,[LastSync]
	
<?xml version="1.0" encoding="utf-8"?>
<vector id="Donations"  Time="2009-08-26 13:31:11" xmlns="http://kojax.ms/kbf">
	<object id="Donation">
		<string id="Id">39</string>
		<string id="Date">3rd Mar 2009, 3:33 PM</string>
		<string id="CharityId">??</string>
		<string id="DonorName">Test User</string>
		<string id="RecipientName">??</string>
		<string id="Description">A month of medical care</string>
		<string id="GiftStatus">delivered</string>
		<string id="DonationStatus">1</string>
		<string id="DonationStatusString">not recorded</string>
	</object>
	<object id="Donation">
		<string id="Id">30</string>
	...
</vector>

UploadMediaForDonation()		POST: RecipientName, DonationId, CharityId, photo

<?xml version="1.0" encoding="utf-8"?>
<object id="body" xmlns="http://kojax.ms/kbf">
	<int id="MediaId">57</int>
	<int id="PostId">58</int>
</object>

AcceptPost()			PostId, CharityId

<?xml version="1.0" encoding="utf-8"?>
<object id="body" xmlns="http://kojax.ms/kbf">
	<string id="Status">Success</string>
	<string id="Message">Success</string>
</object>


RejectPost()			PostId, CharityId
<?xml version="1.0" encoding="utf-8"?>
<object id="body" xmlns="http://kojax.ms/kbf">
	<string id="Status">Success</string>
	<string id="Message">Success</string>
</object>
 * 
 * 
 * GetPicture()			MediaId, CharityId
 * 
 *  JPEG raw output
 *  
 *  
 */




header('Content-type: text/xml');

/** Include the bootstrap for setting up WordPress environment */
require_once('../../../wp-load.php');
require_once('../../../wp-admin/includes/admin.php');
require_once('../../../wp-settings.php');
require_once('../../../wp-admin/includes/image.php');

$exposed_methods	=	array('GetCharities', 'GetDonations', 'UploadMediaForDonation', 'GetPicture', 'AcceptPost', 'RejectPost','UpdateGiftStatus', 'GetDonationHistory');


$oService = new SYIMobileService();
# Log the received input to the log file.
$oService->LogIO($_SERVER['QUERY_STRING']);
$url_params = $_GET ;
foreach($url_params as $key => $value){
	$url_params[$key] = mysql_real_escape_string($value) ;
}

# prepare received data to send to the service class.
$method 			= $url_params['method'] ;
$arguments 			= $url_params ;
unset($arguments['method']);

# Security checking: Check that the called mwthod is in the exposed method list
if (!in_array($method, $exposed_methods)) $oService->SetErrorAndEnd("Class does not serve the method '$method'. Available are " . implode(', ', $exposed_methods));

# ensure that this method is provided in the service class.
$public_properties 	= get_class_methods(get_class($oService));
if (!in_array($method, $public_properties)) $oService->SetErrorAndEnd("Class does not provide the method '$method'.");
# check for argument match
if (isset($oService->expectedArgumentKeys[$method])){
	$expected_args 		= $oService->expectedArgumentKeys[$method] ;
	sort($expected_args) ;
	$supplied_args 		= array_keys($arguments) ;
	sort($supplied_args);
	$missed_params_array = array_diff($expected_args, $supplied_args);
	if ( count($missed_params_array) > 0 ){
		$oService->SetErrorAndEnd("Argument mismatch for method $method(). Missing parameter(s) "  . implode(', ', array_diff($expected_args, $supplied_args))) ;
	}
}

# call the method.
$result_array['Response'] 	= $oService->$method($arguments) ;
if (($method == 'GetCharities' OR $method == 'GetDonations') AND
(isset($arguments['os']) AND $arguments['os']!='kojax')) {
	$wpdb->query("SELECT NOW() AS sTime") ;
	$attributes['Time'] 		= $wpdb->last_result[0]->sTime ;
	$xml_string  				= $oService->EncodeOutput($result_array, $attributes) ;
}else{
	$xml_string  				= $oService->EncodeOutput($result_array) ;
}
print $xml_string ;
exit;





# Service class begins

class SYIMobileService{
	public  $expectedArgumentKeys ;
	private $conf;
	private $response ;
	private $wpdb ;

	/**
	 * Class constructor
	 */
	function __construct(){
		$this->response['Status']  								= 'Success';
		$this->response['Message'] 								= 'Success';
		$this->response											=  (object)array('Status'=>array('Status'=>'Success', 'Message'=>'Success'));

		$this->expectedArgumentKeys['GetCharities'] 			= array('os') ;
		$this->expectedArgumentKeys['GetDonations'] 			= array('os', 'CharityId') ;
		$this->expectedArgumentKeys['GetDonationNotes'] 		= array('os', 'CharityId');
		$this->expectedArgumentKeys['UpdateGiftStatus'] 		= array();
		$this->expectedArgumentKeys['UploadMediaForDonation'] 	= array();
		global $wpdb ;
		$this->wpdb = $wpdb ;
	
		# Hook to get correct upload directory path.
		add_action('upload_dir', 'syi_upload_path_bug_fix');
	}








	/* Web service methods start */

	/** @desc Returns the charity list.
	 * Outputs XML with required fields fetched from the table 'charityAuthKeys'
	 * expecetd values in $args: 'os',
	 * @access public
	 * @param $last_sync
	 * @param $auth_key
	 * @param $os
	 * @return array
	 */
	public function GetCharities($args){
		#$last_sync 	= $args['LastSync'];
		$os			= $args['os'];

		$response  		= $this->response ;
		$sql 			= "SELECT charityID AS CharityID, description AS description, authKey AS `Key` FROM charityAuthKeys" ;
		$response 	= $this->SqlToArray($sql, 'AuthKeys');
		return $response ;
	}

	/** @desc Returns pending donation list for the supplied Charity id.
	 *
	 * Uses ImpactStatus field in the Donation table to decide it is a pending one or not.
	 * The field value which correspond to "Not Published" has been retrived. As of now the id is"1" in the master table "impact_feedback_status".
	 * Joins with master tables and mapping tables to retrieve the required fields.
	 *
	 *
	 * Tables
	 * ------
	 * Gift : Hold the gift details sucha as gift name.
	 * Donation: Donation info and various donation statuses such as impact story status, gift status etc.
	 * DonationGiver, DonationDonor and DonationGifts: Mapping tables for gifts, donations and giver.
	 *
	 *
	 *
	 *
	 * @access Public
	 * @param $last_sync
	 * @param $auth_key
	 * @param $os
	 * @return array
	 */
	public function GetDonations($args){
		$last_sync_filter 	= isset($args['LastSync'])	?	" AND (donation.updateDate > '{$args['LastSync']}' OR donation.donationDate > '{$args['LastSync']}')  "	:	''	;
		$os					= $args['os'];
		$charity_id			= $args['CharityId'];
		$status_in_string	= $this->OsIsWin()	? '1,3,4'	: '1' ;
		/*
		 * Joins tables required to fetch required information.
		 * Sort on donation.impactStatus to make "Not recorded"
		 * donations on top. This status is having an id of 1.
		 *
		 */
		$sql = "SELECT
				donation.donationID AS Id,
				DATE_FORMAT(donation.donationDate, '%D %b %Y, %l:%i %p')  AS Date,
				'$charity_id' AS CharityId,
				CONCAT(donationGiver.firstName, ' ' , donationGiver.lastName) AS DonorName,
				'' AS RecipientName,
				gift.displayName AS Description,
				item_distribution_status.distribution_status AS GiftStatus,
				donation.impactStatus AS DonationStatus,
				impact_feedback_status.feedback_status AS DonationStatusString
				FROM
				donation 
				LEFT JOIN donationGiver ON donationGiver.ID = donation.donorID
				LEFT JOIN donationGifts ON donation.donationID = donationGifts.donationID
				LEFT JOIN gift ON donationGifts.giftID=gift.id
				LEFT JOIN impact_feedback_status ON impact_feedback_status.id = donation.impactStatus
				LEFT JOIN item_distribution_status ON item_distribution_status.id = donation.distributionStatus
				
				WHERE 
				donation.impactStatus IN($status_in_string)
				AND gift.blog_id = '$charity_id'
				$last_sync_filter
				ORDER BY  donation.impactStatus
				";
				$response	 		= $this->SqlToArray($sql, 'Donations');
				return $response ;
	}

	/**
	 * @desc Return a list of history for the donations in the given charity id.
	 * @param $args
	 * @return array
	 */
	public function GetDonationHistory($args){
		$last_sync_filter 	= isset($args['LastSync'])	?	" AND donationHistory.transactionDate > '{$args['LastSync']}' "	:	''	;
		$CharityId			= $args['CharityId'];
		$sql = "SELECT
			donation.donationID AS DonationId, 
			donationHistoryID AS Id,
			DATE_FORMAT(donationHistory.transactionDate, '%d-%b-%Y')  AS NoteDate,
			ACTION AS NoteDescription
			FROM 
			donationHistory
			LEFT JOIN donation ON donationHistory.donationID = donation.donationID
			LEFT JOIN donationGifts ON donation.donationID = donationGifts.donationID
			LEFT JOIN gift ON donationGifts.giftID=gift.id
			 WHERE donation.impactStatus IN(1, 3, 4) 
				AND gift.blog_id = '$CharityId'
				$last_sync_filter
				ORDER BY donationHistory.donationID, donationHistory.transactionDate
				";
					
				$response 	= $this->SqlToArray($sql);
				$this->EncodeOutputForDonationHistory($response);
				exit;
	}

	/**
	 * @desc receives a JSON string and updates gift statuses.
	 * @param $args
	 * @return array
	 */

	public function UpdateGiftStatus($args){
		# additional code for testing. Will be removed later.
		if (isset($args['test'])){
			$input_json			=	'{"Donations":{
		 "Donation":[
		 {"DonationId":"2","GiftStatus":"delivered"},
		 {"DonationId":"3","GiftStatus":"delivered"}
		 ]}
		 }' ;
		}else{
			$input_json				= $this->GetPostedData();
		}
		if (strlen($input_json) == 0 ){
			$this->SetErrorAndEnd('JSON data did not find in post payload.');
		}
		$donation_ids_and_statuses = json_decode($input_json, true);

		//$this->CheckForInnoDb('donation');
		# The update process will be treated as a single transaction to make it atomic.
		$this->Query("BEGIN");
		foreach($donation_ids_and_statuses['Donations']['Donation'] as $donation_row){
			$donation_id			= $donation_row['DonationId'];
			$gift_status_text 		= $donation_row['GiftStatus'];
			$sql 					= "SELECT id, distribution_status FROM item_distribution_status WHERE distribution_status = '$gift_status_text' LIMIT 1" ;
			$id_array 				= $this->SqlToArray($sql);
			$distribution_status_id	= $id_array[0]->id ;
			$distribution_status_text= $id_array[0]->distribution_status;
			# check supplied status text exist in the status master table. Throw error if not found.
			if (count($id_array) == 0){
				$this->SetErrorAndEnd("Status '$gift_status_text' not found. No records updated.") ;
			}
			$this->AddToDonationHistory($donation_id, "Set Distribution status = $distribution_status_text");
			$this->ChangeDonationUpdateDate($donation_id);			
			$sql = "UPDATE donation SET distributionStatus = '$distribution_status_id' WHERE donationId = '$donation_id' LIMIT 1" ;
			$this->Query($sql) ;
		}
		$this->Query("COMMIT");
		$response['body'] = (object) array('Status'=>'Success', 'Message'=>'Success');
		return $response ;
		# ALTER TABLE donation  ENGINE = InnoDB
		# SELECT donationId, distributionStatus FROM donation WHERE donationID IN (2,3)
		# ALTER TABLE `donation` ADD `updateDate` DATETIME NULL ;
		
	}



	/**
	 * @desc Make the post to "Pending Review" state.
	 * @param $args
	 * @return array
	 */
	public function AcceptPost($args){
		$post_id 				= $args['PostId'];
		$charity_id 			= $args['CharityId'] ;
		$this->SetBlogId($charity_id) ;
		# Check whether the post exist or not.
		$post_object 			= wp_get_single_post($post_id) ;
		if (!isset($post_object->ID)){
			$this->SetErrorAndEnd("Post $post_id not found.");
		}
		if ($post_object->post_status == 'publish'){
			$this->SetErrorAndEnd('This is a published post, could not change the status');
		}
		$donation_id_from_meta 	= get_post_meta($post_id, 'donationIds', true);
		if ( $donation_id_from_meta == ''){
			$this->SetErrorAndEnd('Donation Id did not find in the post meta.');
		}
		$post_array['ID']			= $post_id ;
		$post_array['post_status'] 	= 'pending' ;
		wp_update_post($post_array) OR $this->SetErrorAndEnd('Picture status changing failed.');
		$body = array('Status'=>'Success', 'Message'=>'Success');
		$this->ChangeDonationUpdateDate($donation_id_from_meta, true);
		$this->ChangeDonationImpactStatusToRecorded($donation_id_from_meta);
		$this->AddToDonationHistory($donation_id_from_meta, 'Set Impact = Recorded');
		$response['body'] = (object)$body ;
		return $response ;
	}


	/**
	 * @desc Delete the draft post and related media also.
	 * @param $args
	 * @return array
	 */
	public function RejectPost($args){
		$charity_id 	= $args['CharityId'] ;
		$post_id 		= $args['PostId'];
		$this->SetBlogId($charity_id) ;
		# Check whether the post exist or not.
		$post_object 	= wp_get_single_post($post_id) ;
		if (!isset($post_object->ID)){
			$this->SetErrorAndEnd("Post $post_id not found.");
		}
		if ($post_object->post_status == 'publish'){
			$this->SetErrorAndEnd('This is a published post, could not change the status');
		}

		$post_media_ids 	= get_post_meta($post_id, 'MediaPostIds' ) ;
		if(!is_array($post_media_ids)){
			$post_media_ids = array();
		}

		# Delete the post
		wp_delete_post($post_id) OR $this->SetErrorAndEnd('Post deletion failed.') ;

		foreach($post_media_ids as $media_id){
			wp_delete_post($media_id) OR $this->SetErrorAndEnd("Media deletion failed. Media id:$media_id") ;
		}


		$response['body'] = (object) array('Status'=>'Success', 'Message'=>'Success');
		return $response ;
	}

	/**
	 * @desc Receives poted data, build a imgae file from that and add
	 * it to the Wordpress Media Library and create a post by using the receipient name as title
	 * and donation details a s post meta data.The  generated post will have the media as embededed as content.
	 *
	 * @param $args
	 * @return unknown_type
	 */

	public function UploadMediaForDonation($args){
		global $wpdb ;
		# test code, to read from a local file.
		if (isset($_GET['test'])){
			$file_bits 			= file_get_contents(dirname(__FILE__). DIRECTORY_SEPARATOR . 'car3.jpg');
			$params = $_GET ;
		}else{
			$sPosted			=	$this->GetPostedData() ;
			parse_str($sPosted, $params);
			$_GET['CharityId']	=	$params['CharityId']; # to read from the upload hook.
			$file_bits			=	$params['photo'] ;
			$file_bits			=	base64_decode($file_bits);
			#prepare for logging. remove file bits.
			$logging_data		=	$sPosted ;
			$logging_data		=	preg_replace('/photo=.*/', 'photo=<binary>', $logging_data);
			$this->LogIO($logging_data, 'I');
			$this->LogIO('Post data length: ' . strlen($sPosted) , 'I');
		}
		$RecipentName		=	mysql_real_escape_string($params['RecipientName']);
		$DonationId			=	mysql_real_escape_string($params['DonationId']) ;
		$CharityId			=	mysql_real_escape_string($params['CharityId']);
		# retrieve plugin settings before switch to the blog because it stored in the admin site only.
		$thumbnail_size_posts = strtolower(get_option('thumbnail_size_posts', 'Medium'));
		$post_title			  = str_ireplace('%name%', $RecipentName, get_option('new_post_title', '%name% has been impacted.')) ;
		# Switch to the requested charity blog.
		$this->SetBlogId($CharityId) ;
		
		if (strlen($file_bits) == 0){
			$this->SetErrorAndEnd('No image data find in the posted payload.');
		}
		$name				= strlen(trim($RecipentName)) == 0	?	rand(1000,9999) : $RecipentName;
		$name 				= $name . '.jpg'  ;
		$file				= wp_upload_bits( $name ,null, $file_bits  ) ;
		$name_parts 		= pathinfo($name);
		$file_type_info		= wp_check_filetype($name);
		$name 				= trim( substr( $name, 0, -(1 + strlen($name_parts['extension'])) ) ); # stripping out extension
		$file['type'] 		= $file_type_info['type'] ;
		$url 				= $file['url'];
		$type 				= $file['type'];
		$file				= $file['file'];
		$title 				= $name;
		$content 			= '';
		$post_data 			= array() ;
		
		# confirm the donation status is not "Published". Show error message if yes.
		if ( $this->DonationStatusIsPublished($DonationId) ){
			$this->SetErrorAndEnd('This is a published donation. Could not upload media.');
		}
		# Construct the attachment array
		$attachment = array_merge( array(
			'post_mime_type' 	=> $type,
			'guid' 				=> $url,
			'post_parent' 		=> $post_id,
			'post_title' 		=> $title,
			'post_content' 		=> $content,
		), $post_data );
		# Save the data
		$upload_dir								= wp_upload_dir();
		$upload_subdir							= $upload_dir['subdir'];
		$file_with_subdir_path					= $upload_subdir . '/' . basename($file) ;
		$media_id 								= wp_insert_attachment($attachment, $file_with_subdir_path, $post_id);
		if ( !is_wp_error($media_id) ) {
			$generated_metadata 				= wp_generate_attachment_metadata( $media_id, $file );
			wp_update_attachment_metadata( $media_id, $generated_metadata );
			$post_content 						= wp_get_attachment_image($media_id, 'medium', true);
			$post_content						= preg_replace('/height="[0-9]*"/', '', $post_content);
			$post['post_title']	 				= $post_title ;
			$post_content						= "<a href=\"$url\">$post_content</a>" ;
			$post['post_content']				= $post_content ;
			$post['post_status']				= 'draft' ;
			$post['guid'] 						= $url ;
			$post_id							= wp_insert_post($post, true);
			# make the attachment as child of the new post.
			$post 								= array() ;
			$post['ID'] 						= $media_id ;
			$post['post_parent'] 				= $post_id ;
			wp_update_post($post);
			# attach donation id to the post as metadata.
			add_post_meta($post_id, 'donationIds', $DonationId);
			# attach embeded image info. It is used to delete associated media when the post is deleted.
			add_post_meta($post_id, 'MediaPostIds', $media_id);
			unset($response) ;
			$aBody = array();
			$aBody['MediaId'] = $media_id ;
			$aBody['PostId'] = $post_id ;
			$response['body'] 					= (object)$aBody ;
			# call AcceptPost method if request is from Windows mobile.
			if( $this->OsIsWin() ){
				$new_args['PostId'] 	= $post_id ;
				$new_args['CharityId'] 	= $CharityId ;
				$this->AcceptPost($new_args);
			}
		}
		return $response;
	}
	/**
	 * @desc Receives a media id and outputs the media stream with proper content header.
	 * @param $args
	 * @return nothing
	 */

	public function GetPicture($args){

		$media_id 	= $args['MediaId'] ;
		$CharityId	= $args['CharityId'] ;
		$bits		= '' ;

		$this->SetBlogId($CharityId) ;
		$file_path 	= get_attached_file($media_id);
		$r 			= wp_get_attachment_thumb_file($media_id);
		$upload_dir = wp_upload_dir();
		/*pr(1);
		echo ($upload_dir['basedir'] . $file_path);
		die();*/
		if (!is_null($file_path)){
			$full_path =  $upload_dir['basedir'] . $file_path ;
			$bits		= file_get_contents($full_path) ;
			//echo count($bits);
			header('Content-type: image/jpeg');
		}
		print $bits ;
		exit;

	}


	/* Web service methods end
	 * ----------------------------------------------------------------------------------------------
	 * */

	/**
	 * @desc Used to execute a custom query using WP DB APIs .
	 * @param $query
	 * @return resulted rows
	 */
	private function Query($query){
		$wpdb = $this->wpdb ;
		$row_count = $wpdb->query($query);
		if ($row_count === false){
			$this->SetErrorAndEnd($wpdb->last_error);
		}
		if($row_count == 0){
			return array() ;
		}else{
			return $wpdb->last_result;
		}
	}

	/**
	 * @desc Utility  method to execute query and returns the resulted rows as an array.
	 * If $enclose_in is specified, the result will be an array with result stored in a
	 * key named $enclose_in.
	 * @param $sql
	 * @param optional $enclose_in
	 * @return array
	 */
	private function SqlToArray($sql, $enclose_in = ''){

		$result_array = $this->Query($sql) ;
		if ($enclose_in != '' ){
			$temp = $result_array;
			unset($result_array) ;
			$result_array[$enclose_in] = $temp;
		}
		return $result_array ;
	}

	/**
	 * @desc Bulid an error object and output it.
	 * @param string $message
	 * @return nothing
	 */
	public function SetErrorAndEnd($message){
		$aBody['Status'] 	= 'Error' ;
		$aBody['Message'] 	= $message ;
		$response['body'] 	= (object)$aBody ;
		print $this->EncodeOutput($response) ;
		$this->LogIO( json_encode($response),'O' );
		die();

	}

	/**
	 * @desc Encode the output to a format understandable by the mobile client.
	 * @param $var
	 * @return string xml
	 */
	public function EncodeOutput($var, $vector_attributes=array()){
		# expeced output:

		/*
		 * <?xml version="1.0" encoding="utf-8"?>
		 * <vector id="authkeys" xmlns="http://kojax.ms/kbf">
		 * 	<object id="AuthKey">
		 * <int id="CharityID">1</int>
		 * <int id="Key">111</int>
		 * <string id="description">Charity</string>
		 * </object>
		 * ...
		 * </vector>
		 * <
		 *
		 * Array
		 (
		 */
		if (isset($var['Response'])){
			$var = $var['Response'];
		}
			
		$crlf 	= "\n";
		$xml 	= '' ;
		$xml 	.= '<?xml version="1.0" encoding="utf-8"?>';
		$keys 	= array_keys($var);
		foreach ($keys as $variable_name) {
			$variable = $var[$variable_name] ;
			if (gettype($variable) == 'array'){
				$attribute_string = $this->GetAttributeString($vector_attributes);
				# make a vector object
				$xml .= "$crlf<vector id=\"$variable_name\" $attribute_string xmlns=\"http://kojax.ms/kbf\">$crlf";
				$object_name = $variable_name ;
				if (substr($variable_name, strlen($variable_name)-1 ,1) == 's' ){
					$object_name = substr($variable_name,0,strlen($variable_name)-1);
				}
				foreach($variable as $row){
					$xml .= "<object id=\"$object_name\">" ;
					$properties = get_object_vars($row);
					foreach($properties as $property => $value){
						$xml .= "$crlf<string id=\"$property\">$value</string>" ;
					}
					$xml .= "$crlf</object>$crlf";
				}
				$xml .= '</vector>' ;
			}
			if (gettype($variable) == 'object'){
				$xml .= "$crlf<object id=\"$variable_name\" xmlns=\"http://kojax.ms/kbf\">";
				$properties = get_object_vars($variable);
				foreach($properties as $property => $value){
					$type = is_numeric($value) ? 'int' : 'string' ;
					$xml .= "$crlf<$type id=\"$property\">$value</$type>" ;
				}
				$xml .= "$crlf</object>$crlf";
			}

			return ($xml) ;
		}
	}

	/**
	 * @desc Special encoding function for the method GetDonationHistory since it
	 * need nested vectors in the outout.
	 * @param $result - array of row objects
	 * @return nothing
	 */

	public function EncodeOutputForDonationHistory($result){
		$crlf 				= "\n";
		$xml 				= '' ;
		$xml 				.= '<?xml version="1.0" encoding="utf-8"?>';
		$xml 				.= "$crlf<vector id=\"Donations\" xmlns=\"http://kojax.ms/kbf\">";
		$prev_donation_id 	= 0 ;
		foreach ($result as $row) {
			if ($prev_donation_id != $row->DonationId){
				if($prev_donation_id != 0){
					$xml .= "$crlf</vector>" ;
				}
				$xml .= "$crlf<vector id=\"Donation\">" ;
			}
			$xml 				.= "$crlf<object id=\"Note\">" ;
			$properties 		= get_object_vars($row);
			foreach($properties as $property => $value){
				$xml .= "$crlf<string id=\"$property\">$value</string>" ;
			}
			$xml 				.= "$crlf</object>" ;
			$prev_donation_id 	= $row->DonationId;
		}
		if($prev_donation_id != 0){
			$xml .= "$crlf</vector>" ;
		}
		$xml .= "$crlf</vector>" ;
		echo $xml ;
		exit;
	}

	/**
	 * @desc Build the xml item attribute string from the given array.
	 * @param Array $atrributes
	 * @return string
	 */
	private function GetAttributeString($atrributes){
		$string = '';
		foreach($atrributes as $name => $value){
			$string .= " $name=\"$value\"";
		}
		return $string;
	}
	/**
	 * @desc Read and return the posted http raw data.
	 * @return string
	 */
	public function GetPostedData(){
		return file_get_contents( 'php://input' );
	}

	/**
	 * @desc Switch to specified wordpress blog.
	 * @param $blog_id
	 * @return nothing
	 */
	private function SetBlogId($blog_id){
		global $wpdb ;
		$blog_id = (int) $blog_id ; # get_blog_details() requires the blog id of numeric type.
		$blog_details = get_blog_details($blog_id);
		# confirm the blog exist. throw error if not.
		if ( $blog_details === false){
			$this->SetErrorAndEnd("Blog for charity id $blog_id does not exist.");
		}
		wp_cache_flush();
		switch_to_blog($blog_id);
	}

	private function ChangeDonationImpactStatusToRecorded($donation_id){
		$sql = "UPDATE donation SET impactStatus = '3' WHERE donationID = '$donation_id' LIMIT 1 " ;
		$this->Query($sql);
	}
	private function ChangeDonationUpdateDate($donation_id, $do_not_update_if_recorded=false){
		# Update only if the update status 
		$impact_status_condition	=	$do_not_update_if_recorded	?	' AND impactStatus = 1 ' : '' ; # 1 = recorded
		$sql 						= "UPDATE donation SET updateDate = NOW() WHERE donationID = '$donation_id' $impact_status_condition LIMIT 1 " ;
		$this->Query($sql);
	}
	private function AddToDonationHistory($donation_id, $message){
		$sql ="INSERT INTO donationHistory SET 
					donationID 		= '$donation_id',
					action			= '$message',
					transactionDate = NOW(),
					modifiedBy		= 1
					" ;
		$this->Query($sql);
	} 

	/**
	 * @desc Checks the os and returns true if the requested os is Windows.
	 * @return bool
	 */
	private function OsIsWin(){
		if (isset($_GET['os']) AND $_GET['os'] != 'kojax'){
			return true;
		}else{
			return false ;
		}
	}
	private function DonationStatusIsPublished($donation_id){
		$sql 		= "SELECT impactStatus FROM donation WHERE donationID = '$donation_id' AND impactStatus = 4 LIMIT 1" ;
		$result 	= $this->SqlToArray($sql);
		if (isset($result[0])){
			return true ;
		}else{
			return false ;
		}
		
	}
	
	

	/*
	 private function CheckForInnoDb($table){
		$sql 	= 'SHOW TABLE STATUS WHERE NAME=\'donation\'' ;
		$result = $this->SqlToArray($sql);
		if (count($result) == 0 ){
		$this->SetErrorAndEnd("Table '$table' does not exist in the database."  ) ;
		}
		$engine_type = $result[0]['Engine'] ;
		if( $engine_type != 'InnoDB'){
		$this->SetErrorAndEnd("The database table '$table' is having storage type of $engine_type which does not support transactions."  ) ;
		}
		} */
	/**
	 * LogIO() - Writes logging info to a file.
	 *
	 *
	 * @param string $msg Information describing logging reason.
	 * @param string $io Whether input or output
	 * @return bool Always return true
	 */
	public function LogIO($msg, $io='I') {
		$fp = fopen("./log.log", "a+");
		$date = gmdate("Y-m-d H:i:s ");
		$iot = ($io == "I") ? " Input: " : " Output: ";
		fwrite($fp, "\n".$date.$iot.$msg);
		fclose($fp);
		return true;
	}


}

/**
 * Function for debugging purpose only.
 * @param $v
 * @return unknown_type
 */
function pr($v){
	header('Content-type: text/html');
	echo '<pre>';
	print_r($v);
	echo '</pre>';
}

/**
 * Function which works as hook to fix upload directory path. 
 * Reason: Wordpress retruns path to the primary blog upload location even after a call to swith to a charity 
 * blog has made. 
 * @param array contains Wordpress upload directory information.
 * @return Array with corrected path.
 */
function syi_upload_path_bug_fix($uploads){
	$BlogId 			= $_GET['CharityId'] ;
	$uploads['path'] 	= str_replace('/blogs.dir/1/files', "/blogs.dir/$BlogId/files", $uploads['path']);
	$uploads['basedir'] = str_replace('/blogs.dir/1/files', "/blogs.dir/$BlogId/files", $uploads['basedir']);
	return $uploads;
}
?>
