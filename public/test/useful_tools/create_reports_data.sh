#!/bin/bash

mysqldump -h mysql.seeyourimpact.org -u syidb -pnischal1999 impactdb_dev1 donation cart donationGiver donationAcct donationAcctTrans donationGifts donationStory donorInfo gift payment invitation | mysql -h mysql.seeyourimpact.org -u syidb -pnischal1999 impactdb_reports
