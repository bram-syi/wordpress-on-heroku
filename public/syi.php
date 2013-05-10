<?php
/**
*Syi abstracts the handling and access of donation related information.
*/
class Syi{

/**
 * Returns the Gift details.
 *
 * @param string $giftId Id of the Gift 
 * @return array Gift details if the Gift with Id equals giftId exists, else null
 * @access public
 */
  function get_gift($giftId){
	global $wpdb;
	$sql = $wpdb->prepare("SELECT * FROM gift WHERE id = %d LIMIT 1", $giftId);
	return $wpdb->get_row($sql);
  }
    
  function get_gift_from_item($itemId){
	global $wpdb;
	$sql = $wpdb->prepare("SELECT g.*, item.amount FROM donationGifts item JOIN gift g ON g.id = item.giftId WHERE item.id = %d LIMIT 1", $itemId);
	return $wpdb->get_row($sql);
  }

/**
 * Returns the Donor details.
 *
 * @param string $donorId Id of the Gift 
 * @return array Donor details if the Donor with Id equals donorId exists, else null
 * @access public
 */
    function get_donor($donorId=null){
        if( $donorId==null )
            return null;
        $queryResult=mysql_query(sprintf("SELECT * FROM donationGiver WHERE ID = %d LIMIT 1",intval($donorId) ));
        return ( !mysql_error() && mysql_num_rows($queryResult) > 0 ) ? mysql_fetch_assoc($queryResult) :  null  ;
    }
    
/**
 * Returns the Donation details.
 *
 * @param string $donationId Id of the Gift 
 * @return array Donation details if the Donation with Id equals donationId exists, else null
 * @access public
 */
    function get_donation($donationId){
        if( $donationId==null )
            return null;
        $queryResult=mysql_query(sprintf("SELECT * FROM donation WHERE donationID = %d LIMIT 1",intval($donationId) ));
        return ( !mysql_error() && mysql_num_rows($queryResult) > 0 ) ? mysql_fetch_assoc($queryResult) :  null  ;
    }

/**
 * Returns the Impact Feedback Status.
 *
 * @param string $impactId Id of the impact status
 * @return string Name of the Impact
 * @access public
 */
    function getImpactStatusName($impactId){
        $resultSet=mysql_query("select feedback_status from impact_feedback_status where id = ".intval($impactId));
        if(mysql_num_rows($resultSet)>0){
            $result=mysql_fetch_assoc($resultSet);
            return $result['feedback_status'];
        }
        return null;
    }

/**
 * Returns the Money Transfer Status.
 *
 * @param string $moneyId Id of the impact status
 * @return string Name of the Money Transder Status
 * @access public
 */    
    function getMoneyStatusName($moneyId){
        $resultSet=mysql_query("select transfer_status from money_transfer_status where id = ".intval($moneyId));
        if(mysql_num_rows($resultSet)>0){
            $result=mysql_fetch_assoc($resultSet);
            return $result['transfer_status'];
        }
        return null;
    }
    
/**
 * Returns the Gift Distribution Status.
 *
 * @param string $distroId Id of the Gift Distribution Status
 * @return string Name of the Gift Distribution Status
 * @access public
 */
    function getDistributionStatusName($distroId){
        $resultSet=mysql_query("select distribution_status from item_distribution_status where id = ".intval($distroId));
        if(mysql_num_rows($resultSet)>0){
            $result=mysql_fetch_assoc($resultSet);
            return $result['distribution_status'];
        }
        return null;
    }    
    
/**
 * Returns IDs of all the Gifts donated as part of a single donation.
 *
 * @param string $donationID Id of the donation instance.
 * @return array Gift Ids
 * @access public
 */
    function get_giftsOfDonation($donationID){
        global $wpdb;

        if (empty($donationID))
	    return array();

        $results = $wpdb->get_col($wpdb->prepare("SELECT giftID FROM donationGifts WHERE matchingDonationAcctTrans = 0 AND donationID = %d", $donationID));
	return array_filter(array_map('intval', $results));
    }
    

/**
 * Returns IDs of all the donors who made the single donation.
 *
 * @param string $donationID Id of the donation instance.
 * @return array Donor Ids
 * @access public
 */
    function get_donorsOfDonation($donationID){
         global $wpdb;

        if( $donationID==null )
            return null;
        $queryResult= $wpdb->query($wpdb->prepare("SELECT d.donorID FROM donation d WHERE d.donationID =  %d", $donationID));
        if(mysql_error())
            return null;
        $donorsID=array();
        if(mysql_num_rows($queryResult)>0){
            while($donorID=mysql_fetch_assoc($queryResult)){
                $donorsID[]=$donorID['donorID'];
            }
        }
        return $donorsID;    
    }
    
}
?>
