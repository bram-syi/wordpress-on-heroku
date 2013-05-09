-- define('ACCT_TYPE_INTERNAL', 1);
-- define('ACCT_TYPE_GENERAL', 2);
-- define('ACCT_TYPE_GIFT', 3);
-- define('ACCT_TYPE_MATCHING', 4);
-- define('ACCT_TYPE_DISCOUNT', 5);
-- define('ACCT_TYPE_OPEN_CODE', 6);
-- define('ACCT_TYPE_GIVE_ANY', 7);
-- $PAYMENT_METHODS = array(
--   'unknown', // 0
--   'PAYPAL',  // 1
--   'CC', // 2
--   'GOOGLE', // 3
--   'AMAZON', // 4
--   'GC', // 5
--   'MATCHING', // 6
--   'PAYPAL', // 7
--   'SP', // 8
--   'RECURLY', // 9
--   'XFER', // 10
--   'CASH/CHECK' // 11
-- );

-- cleanup
DROP TABLE IF EXISTS t_donors, t_pratham, t_education_success, t_others;

-- t_donors: 9369 (not unique email addresses)
CREATE TEMPORARY TABLE t_donors
SELECT donor.email AS 'donor_email'
    ,CONCAT(donor.firstName, ' ', donor.lastName) AS 'donor_name'
    ,donor.share_email AS 'share_email'
    -- looking first by user_id, and then via donorID, get the donor type
    -- if the donor type is NULL or 0, then they are normal
    -- otherwise they are special
    ,IF(info1.donorType IS NULL, IFNULL(info2.donorType,0), info1.donorType) AS 'donor_type'
    ,GROUP_CONCAT(SUBSTRING_INDEX(blog.domain, '.', 1)) AS 'campaign'
-- if you group by campaign, use the line below instead of the line above
--    ,SUBSTRING_INDEX(blog.domain, '.', 1) AS 'campaign'
    ,MAX(donation.donationDate) AS 'most_recent'
    ,SUM(IF(IFNULL(accountType.id,0) = 7,donationGifts.amount,0)) AS 'allocates'
    ,SUM(IF(IFNULL(accountType.id,0) != 7,donationGifts.amount,0)) AS 'directs'
FROM donationGifts
    LEFT JOIN wp_blogs blog                 ON blog.blog_id = donationGifts.blog_id
    LEFT JOIN donation                      ON donation.donationID = donationGifts.donationID
    LEFT JOIN payment                       ON payment.id = donation.paymentID
    LEFT JOIN donationAcctTrans trans       ON trans.paymentID = payment.id
    LEFT JOIN donationAcct account          ON account.id = trans.donationAcctId
    LEFT JOIN donationAcctType accountType  ON accountType.id = account.donationAcctTypeId
    LEFT JOIN donationGiver donor           ON donor.id = IF(IFNULL(accountType.id,0) = 7, account.donorID, donation.donorID)
    LEFT JOIN donorInfo info1               ON info1.user_id = donor.user_id
    LEFT JOIN donorInfo info2               ON info2.donorID = donor.id
GROUP BY donor_email
-- GROUP BY donor_email, campaign
ORDER BY most_recent DESC
;

-- t_pratham: 388 addresses
CREATE TEMPORARY TABLE t_pratham
SELECT *
FROM t_donors
WHERE campaign = 'pratham'
    AND most_recent < '2012-06-01'
;

-- t_education_success: 587 addresses
CREATE TEMPORARY TABLE t_education_success
SELECT *
FROM t_donors
WHERE campaign IN ('jsmt','sss','jagriti','ppes')
    AND donor_email NOT IN (SELECT donor_email FROM t_pratham)
;

-- t_others: 4825 addresses
CREATE    TEMPORARY TABLE t_others
SELECT *
FROM t_donors
WHERE donor_email NOT IN (SELECT donor_email FROM t_pratham)
    AND donor_email NOT IN (SELECT donor_email FROM t_education_success)
;
