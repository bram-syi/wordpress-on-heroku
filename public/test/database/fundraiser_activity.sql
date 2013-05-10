(SELECT d.donationID,
    donor.firstName as firstName,
    dg.amount * COUNT(DISTINCT dg.ID) as raised,
    dg.tip * COUNT(DISTINCT dg.ID) as tip,
    IF(tg.id IS NULL, 
        CONCAT('gave ', g.displayName), 
        CONCAT('gave $',FORMAT(dg.amount,2),' for ',tg.displayName)) AS activity, 
    COUNT(DISTINCT dg.ID) AS qty, 
    d.donationDate AS date, 
    donor.user_id, 
    donor.ID as donorID, 
    0 as datid, 
    NULL as matched 
FROM 		  donationGifts 	dg
    JOIN 	  donation 			d 		ON d.donationID = dg.donationID
    JOIN 	  donationGiver 	donor 	ON donor.ID = d.donorID
    JOIN 	  payment 			p 		ON d.paymentID = p.id
    LEFT JOIN gift 				g 		ON dg.giftID = g.ID 
    LEFT JOIN gift 				tg 		ON (g.towards_gift_id=tg.id 
        									AND g.varAmount=1 AND g.unitAmount=1) 
    LEFT JOIN donationAcctTrans dat 	ON dat.paymentID = p.id AND p.provider = 5
    LEFT JOIN donationAcct 		da 		ON dat.donationAcctId = da.id 
WHERE IFNULL(d.test,0) = 0
    AND dg.event_id = 5979
    AND dg.matchingDonationAcctTrans=0
    AND (da.id IS NULL 
        OR (da.donationAcctTypeId != 4 AND da.event_id != dg.event_id)) 
    AND d.donationAmount_Total > 0
GROUP BY d.donationID, g.ID, dg.matchingDonationAcctTrans
) UNION (
SELECT d.donationID AS donationID,
    donor.firstName,
    IFNULL(dat.amount, 0) * (p1.amount/(p1.amount+p1.tip)) as raised,
    IFNULL(dat.amount, 0) * (p1.tip/(p1.amount+p1.tip)) as tip,
    '' AS activity, 
    1 AS qty, 
    dat.dateInserted AS date,
    donor.user_id, 
    donor.ID as donorID,
    dat.ID as datid,
    daMatch.id as matched 
FROM 		  donationAcct 		da
    JOIN      donationAcctTrans dat 	ON dat.donationAcctId = da.id AND dat.amount > 0
    LEFT JOIN payment           p1  	ON p1.id=dat.paymentID
    LEFT JOIN donation          d   	ON d.paymentID=p1.ID
    JOIN      donationGiver     donor 	ON da.owner = donor.ID
    LEFT JOIN wp_1_posts 		wp 		ON wp.ID = da.event_id 
    LEFT JOIN donationAcctTrans dat2 	ON dat2.paymentID=p1.id AND dat2.donationAcctId != da.id AND dat2.amount < 0
    LEFT JOIN donationAcct 		da2 	ON dat2.donationAcctId = da2.id
    LEFT JOIN donationAcct 		daMatch ON dat2.donationAcctId = daMatch.id AND daMatch.donationAcctTypeId=4
WHERE IFNULL(d.test,0) = 0
    AND da.event_id = 5979
    AND d.donationID > 0 
    AND NOT (IFNULL(da2.donationAcctTypeId,0) = 7 AND da2.event_id = da.event_id) 
    AND da.donationAcctTypeId > 2
)
-- ORDER BY date DESC LIMIT 80
