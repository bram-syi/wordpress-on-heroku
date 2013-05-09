

-- Delete users
DELETE u from wp_users u WHERE u.ID > 1 AND u.ID != 14;
UPDATE donationGiver dg SET dg.user_id = NULL where dg.user_id != 14;
DELETE FROM wp_usermeta WHERE user_id > 1 AND user_id != 14;
UPDATE wp_sitemeta SET meta_value=2 WHERE meta_key='user_count';
DELETE FROM wp_bp_notifications;
DELETE FROM wp_bp_xprofile_data WHERE user_id > 1 AND user_id != 14;
DELETE FROM wp_signups;
DELETE FROM wp_user2group_rs;

-- Delete all donors and accounts
DELETE FROM donationGiver WHERE IFNULL(user_id,0) != 14;
DELETE FROM donationHistory;
DELETE da FROM donationAcct da
 LEFT JOIN donationGiver dg on da.owner=dg.id
 WHERE IFNULL(dg.user_id,0) != 14;
DELETE dat FROM donationAcctTrans dat 
 LEFT JOIN donationAcct da on dat.donationAcctId=da.id
 WHERE da.id IS NULL;
DELETE d FROM donation d
 LEFT JOIN donationGiver dg on dg.id=d.donorID
 WHERE IFNULL(dg.user_id,0) != 14;
-- (okay to leave donationGifts, so stories don't have a problem?)
DELETE d FROM donationGifts dg
 LEFT JOIN donation d on d.donationID=dg.donationID
 WHERE IFNULL(dg.story,0) = 0 AND d.donationID is NULL;

-- Delete all campaigns
DELETE theme FROM theme_data theme;
DELETE FROM campaigns;
DELETE FROM wp_1_posts WHERE post_type != 'page' AND post_type != 'attachment' AND post_type != 'promo';
DELETE pm FROM wp_1_postmeta pm LEFT JOIN wp_1_posts p on p.ID=pm.post_id WHERE pm.post_id IS NOT NULL;
UPDATE donationGifts SET event_id = NULL;
UPDATE donationAcct SET event_id = NULL;

-- Delete all blogs
DELETE FROM wp_registration_log WHERE blog_id > 1;

-- Clean up some wordpress stuff
DELETE FROM wp_sitecategories WHERE cat_ID > 10;
DELETE FROM wp_sitemeta WHERE meta_key LIKE '_site_transient%';
DELETE FROM wp_1_options WHERE option_name LIKE '_transient%';
DELETE FROM wp_1_options WHERE option_name LIKE '%_new_email';
DELETE FROM wp_1_comments;
DELETE FROM wp_1_commentmeta;

-- Clear all carts 
DELETE FROM cart;
DELETE FROM cartDebug;
DELETE FROM cartItem;
DELETE FROM cartItemDetails;

-- Clear random tables
DELETE FROM pledges;
DELETE FROM wp_invitations;
DELETE FROM unsubscribed;
DELETE FROM sitewideLog;
DELETE FROM views;
DELETE FROM featuredContent WHERE ID != 2;
DELETE FROM featuredGiftSet;
DELETE from redirects;

-- Clear new payments tables
DROP TABLE donation_report;
DELETE t FROM a_transaction t
 LEFT JOIN donationGiver dg on dg.id=t.donor_id
 WHERE IFNULL(dg.user_id,0) != 14;
DELETE d FROM a_donation d
 LEFT JOIN donationGiver dg on dg.id=d.donorID
 WHERE IFNULL(dg.user_id,0) != 14;
DELETE a FROM a_account a
 LEFT JOIN donationGiver dg on dg.id=a.owner
 WHERE IFNULL(dg.user_id,0) != 14;
DELETE g FROM a_gift g
 LEFT JOIN a_donation d on d.donationID=g.donationID
 WHERE d.donationID is NULL;
DELETE p FROM a_payment p
 LEFT JOIN a_donation d on p.donationID=d.donationID
 WHERE d.donationID is NULL;
