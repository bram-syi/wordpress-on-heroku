DELETE FROM a_account;
INSERT INTO a_account
    SELECT id, balance, dateCreated, dateUpdated,
        0, -- a_account.type
        `code`, `owner`, donationAcctTypeId, note, `use`, priority, params, blogId, event_id, expired
    FROM donationAcct;

DELETE FROM a_donation;
INSERT INTO a_donation
    SELECT donationID, donationDate,
        0, -- a_donation.amount
        donationAmount_Total, tip, donorID, testData, anonymous
    FROM donation;

DELETE FROM a_gift;
INSERT INTO a_gift
    SELECT donationID,
        0, -- a_gift.trans_id
        giftID, amount, tip, towards_gift_id, blog_id, ID, story, matchingDonationAcctTrans, event_id
    FROM donationGifts;

DELETE FROM a_payment;
INSERT INTO a_payment
    SELECT id, donation, `dateTime`, amount, provider,
        0, -- a_payment.acct_id
        notes, raw, txnID, `data`
    FROM payment;

DELETE FROM a_transaction;
INSERT INTO a_transaction
    SELECT id,
        0,  -- a_transaction.donation_id
        0,  -- a_transaction.donor_id
        '', -- a_transaction.type
        0,  -- a_transaction.date
        amount,
        0,  -- a_transaction.card
        0,  -- a_transaction.tip
        0,  -- a_transaction.gift_id
        0,  -- a_transaction.fr_id
        0,  -- a_transaction.acct_id
        note,
        0   -- a_transaction.bundle_id
    FROM donationAcctTrans;
