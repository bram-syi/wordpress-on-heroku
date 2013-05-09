-- missing IDs in a_gift that were in donationGifts
SELECT old_t.*
FROM donationGifts old_t
    LEFT JOIN a_gift new_t
        ON old_t.`ID` = new_t.`ID`
WHERE new_t.`ID` IS NULL;

-- copied IDs in a_gift that were in donationGifts
SELECT old_t.`ID`
FROM donationGifts old_t
    LEFT JOIN a_gift new_t
        ON old_t.`ID` = new_t.`ID`
WHERE new_t.`ID` IS NOT NULL;

-- new IDs in a_gift that were NOT in donationGifts
SELECT new_t.`ID`
FROM a_gift new_t
    LEFT JOIN donationGifts old_t
        ON new_t.`ID` = old_t.`ID`
WHERE old_t.`ID` IS NULL;

-- different rows between donationGifts and a_gift
SELECT old_t.`ID` AS id ,
  old_t.`event_id` AS `old.event_id`, new_t.`event_id` AS `new.event_id` ,
  old_t.`story` AS `old.story`, new_t.`story` AS `new.story` ,
  old_t.`towards_gift_id` AS `old.towards_gift_id(IGN)`, new_t.`towards_gift_id` AS `new.towards_gift_id(IGN)` ,
  old_t.`matchingDonationAcctTrans` AS `old.matchingDonationAcctTrans`, new_t.`matchingDonationAcctTrans` AS `new.matchingDonationAcctTrans` ,
  old_t.`tip` AS `old.tip`, new_t.`tip` AS `new.tip` ,
  old_t.`giftID` AS `old.giftID`, new_t.`giftID` AS `new.giftID` ,
  old_t.`amount` AS `old.amount`, new_t.`amount` AS `new.amount` ,
  old_t.`donationID` AS `old.donationID`, new_t.`donationID` AS `new.donationID` ,
  old_t.`blog_id` AS `old.blog_id`, new_t.`blog_id` AS `new.blog_id`
FROM donationGifts old_t
  LEFT JOIN a_gift new_t
    ON old_t.`ID` = new_t.`ID` AND (
      old_t.`event_id` != new_t.`event_id`OR
      old_t.`story` != new_t.`story`OR
--      old_t.`towards_gift_id` != new_t.`towards_gift_id`OR
      old_t.`matchingDonationAcctTrans` != new_t.`matchingDonationAcctTrans`OR
      old_t.`tip` != new_t.`tip`OR
      old_t.`giftID` != new_t.`giftID`OR
      old_t.`amount` != new_t.`amount`OR
      old_t.`blog_id` != new_t.`blog_id`
    )
WHERE new_t.`ID` IS NOT NULL
ORDER BY old_t.`ID` desc;

-- missing IDs in a_payment that were in payment
SELECT old_t.donationAcctTrans, old_t.donation, group_concat(dr.type2) AS summary, old_t.*
FROM payment old_t
  LEFT JOIN a_payment new_t
    ON old_t.`id` = new_t.`id`
  LEFT JOIN donation_report dr
    ON dr.payment_id = old_t.id
  INNER JOIN donation d
    ON old_t.donation = d.donationID
WHERE new_t.`id` IS NULL
  AND d.test = 0
GROUP BY old_t.id
ORDER BY id DESC
-- HAVING summary IS null -- 359 rows, these are payments that were never in donation_report
;

-- copied IDs in a_payment that were in payment
SELECT old_t.`id`
FROM payment old_t
    LEFT JOIN a_payment new_t
        ON old_t.`id` = new_t.`id`
WHERE new_t.`id` IS NOT NULL;

-- new IDs in a_payment that were NOT in payment
SELECT new_t.`id`
FROM a_payment new_t
    LEFT JOIN payment old_t
        ON new_t.`id` = old_t.`id`
WHERE old_t.`id` IS NULL;

-- different rows between payment and a_payment
SELECT * FROM (
  SELECT old_t.`id` AS id ,
  --  old_t.`provider` AS `old.provider`, new_t.`provider` AS `new.provider` ,
  --  old_t.`data` AS `old.data`, new_t.`data` AS `new.data` ,
    old_t.`dateTime` AS `old.dateTime`, new_t.`dateTime` AS `new.dateTime` ,
    old_t.`tip` AS `old.tip`, d.tip AS dtip, d.donationAmount_Total as dtotal,
    old_t.`gc_amount` AS `old.gc_amount`,
    old_t.`amount` AS `old.amount`, new_t.`amount` AS `new.amount`,

    old_t.`amount`+IFNULL(old_t.tip,0)-IFNULL(old_t.gc_amount,0) as new1,
    old_t.`amount`+IFNULL(d.tip,0)-IFNULL(old_t.gc_amount,0) as new2,
    old_t.`amount`-IFNULL(old_t.gc_amount,0) as new3,

    old_t.`notes` AS `old.notes`, new_t.`notes` AS `new.notes` ,
    old_t.`txnID` AS `old.txnID`, new_t.`txnID` AS `new.txnID` ,
    old_t.`raw` AS `old.raw`, new_t.`raw` AS `new.raw`
    ,group_concat(dr.type2) AS types
    ,group_concat(dr.id) AS dr_ids
    ,count(dr.id) AS dr_id_count
    , dr.total, dr.amount, dr.keep, dr.out, dr.card

  FROM payment old_t
    LEFT JOIN donation d on d.paymentID=old_t.id
    LEFT JOIN a_payment new_t
      ON old_t.`id` = new_t.`id`
    LEFT JOIN donation_report dr
      ON dr.payment_id = new_t.id
  WHERE new_t.`id` IS NOT NULL
    -- 21 payments that weren't actually payments, they were gift card purchases
--    AND NOT (dr.amount = 0 AND dr.card > 0)
    -- column-by-column differences
    AND (
      (old_t.`provider` != new_t.`provider` AND NOT ((old_t.provider=7 OR old_t.provider=0) AND new_t.provider=1)) OR
      old_t.`data` != new_t.`data`OR
      ABS(TIME_TO_SEC(TIMEDIFF(old_t.`dateTime`, new_t.`dateTime`))) > 60 OR (
        -- We used to store the same data so many different ways :(
        ABS(old_t.`amount` + IFNULL(old_t.tip,0) - IFNULL(old_t.gc_amount,0) - new_t.`amount`) > 0.02 AND
        ABS(old_t.`amount` + IFNULL(d.tip,0) - IFNULL(old_t.gc_amount,0) - new_t.`amount`) > 0.02 AND
        ABS(old_t.`amount` - IFNULL(old_t.gc_amount,0) - new_t.`amount`) > 0.02
        -- eg payment.id = 17493 (26 rows): one more way of calculating new_t.amount
        AND ABS(d.tip + d.donationAmount_Total - new_t.amount) > 0.02
      ) OR
      old_t.`notes` != new_t.`notes`OR
      old_t.`txnID` != new_t.`txnID`OR
      old_t.`raw` != new_t.`raw`
    )
  GROUP BY dr.payment_id
  ORDER BY old_t.id DESC
) AS temp
WHERE types NOT LIKE "%error%"

-- these are 45 payments
--  AND types LIKE "%discount%"

-- these are 21 payments
  AND dr_id_count = 1

-- missing IDs in a_account that were in donationAcct
SELECT old_t.*
FROM donationAcct old_t
    LEFT JOIN a_account new_t
      ON old_t.`id` = new_t.`id`
    LEFT JOIN donation_report dr
      ON old_t.id = dr.acct_id
WHERE new_t.`id` IS NULL
  AND dr.id IS NOT NULL
  OR old_t.id IN (25978,31901,31950,32010,33937,34049,35747,35862)
ORDER BY old_t.id DESC;

-- copied IDs in a_account that were in donationAcct
SELECT old_t.`id`
FROM donationAcct old_t
    LEFT JOIN a_account new_t
        ON old_t.`id` = new_t.`id`
WHERE new_t.`id` IS NOT NULL;

-- new IDs in a_account that were NOT in donationAcct
SELECT new_t.`id`
FROM a_account new_t
    LEFT JOIN donationAcct old_t
        ON new_t.`id` = old_t.`id`
WHERE old_t.`id` IS NULL;

-- different rows between donationAcct and a_account
SELECT old_t.`id` AS id ,
  old_t.`priority` AS `old.priority`, new_t.`priority` AS `new.priority` ,
  old_t.`dateUpdated` AS `old.dateUpdated`, new_t.`dateUpdated` AS `new.dateUpdated` ,
  old_t.`event_id` AS `old.event_id`, new_t.`event_id` AS `new.event_id` ,
  old_t.`use` AS `old.use`, new_t.`use` AS `new.use` ,
  old_t.`expired` AS `old.expired`, new_t.`expired` AS `new.expired` ,
  old_t.`dateCreated` AS `old.dateCreated`, new_t.`dateCreated` AS `new.dateCreated` ,
  old_t.`code` AS `old.code`, new_t.`code` AS `new.code` ,
  old_t.`owner` AS `old.owner`, new_t.`owner` AS `new.owner` ,
  old_t.`donationAcctTypeId` AS `old.donationAcctTypeId`, new_t.`donationAcctTypeId` AS `new.donationAcctTypeId` ,
  old_t.`note` AS `old.note`, new_t.`note` AS `new.note` ,
  old_t.`params` AS `old.params`, new_t.`params` AS `new.params` ,
  old_t.`balance` AS `old.balance`, new_t.`balance` AS `new.balance` ,
  old_t.`blogId` AS `old.blogId`, new_t.`blogId` AS `new.blogId`
FROM donationAcct old_t
  LEFT JOIN a_account new_t
    ON old_t.`id` = new_t.`id`
WHERE new_t.`id` IS NOT NULL
  AND (
    old_t.`priority` != new_t.`priority`OR
--      old_t.`dateUpdated` != new_t.`dateUpdated`OR
    old_t.`event_id` != new_t.`event_id`OR
--      old_t.`use` != new_t.`use`OR
    old_t.`expired` != new_t.`expired`OR
    old_t.`dateCreated` != new_t.`dateCreated`OR
    old_t.`code` != new_t.`code`OR
    old_t.`owner` != new_t.`owner`OR
    old_t.`donationAcctTypeId` != new_t.`donationAcctTypeId`OR
    old_t.`note` != new_t.`note`OR
    old_t.`params` != new_t.`params`OR
    old_t.`balance` != new_t.`balance`OR
    new_t.`blogId` != 0
  )
  AND ABS(new_t.balance - old_t.balance) > .02
ORDER BY old_t.id DESC

-- missing IDs in a_donation that were in donation
SELECT old_t.*
FROM donation old_t
    LEFT JOIN a_donation new_t
        ON old_t.`donationID` = new_t.`donationID`
WHERE new_t.`donationID` IS NULL;

-- copied IDs in a_donation that were in donation
SELECT old_t.`donationID`
FROM donation old_t
    LEFT JOIN a_donation new_t
        ON old_t.`donationID` = new_t.`donationID`
WHERE new_t.`donationID` IS NOT NULL;

-- new IDs in a_donation that were NOT in donation
SELECT new_t.`donationID`
FROM a_donation new_t
    LEFT JOIN donation old_t
        ON new_t.`donationID` = old_t.`donationID`
WHERE old_t.`donationID` IS NULL;

-- different rows between donation and a_donation
SELECT old_t.`donationID` AS id ,
  abs(old_t.donationAmount_Total + old_t.tip - new_t.donationAmount_Total) AS 'total_diff',
  old_t.`test` AS `old.test`, new_t.`test` AS `new.test` ,
  old_t.`tip` AS `old.tip`, new_t.`tip` AS `new.tip` ,
  old_t.`donorID` AS `old.donorID`, new_t.`donorID` AS `new.donorID` ,
  old_t.`donationAmount_Total` AS `old.donationAmount_Total`, old_t.tip AS `old.tip`, new_t.`donationAmount_Total` AS `new.donationAmount_Total` ,
  old_t.`anonymous` AS `old.anonymous`, new_t.`anonymous` AS `new.anonymous` ,
  old_t.`donationDate` AS `old.donationDate`, new_t.`donationDate` AS `new.donationDate`
FROM donation old_t
  LEFT JOIN a_donation new_t
    ON old_t.`donationID` = new_t.`donationID` AND (
      old_t.`test` != new_t.`test`OR
      old_t.`tip` != new_t.`tip`OR
      old_t.`donorID` != new_t.`donorID`OR
      old_t.`donationAmount_Total` + old_t.tip != new_t.`donationAmount_Total`OR
      old_t.`anonymous` != new_t.`anonymous`OR
      old_t.`donationDate` != new_t.`donationDate`
    )
WHERE new_t.`donationID` IS NOT NULL
  -- ~3,331 rows are within one dollar of the old value
  AND abs((old_t.donationAmount_Total + old_t.tip) - new_t.donationAmount_Total) > 1.0
order by abs(old_t.donationAmount_Total + old_t.tip - new_t.donationAmount_Total) DESC
;

