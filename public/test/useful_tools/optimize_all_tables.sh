#!/bin/bash

mysqlcheck -h mysql.seeyourimpact.org -u syidb -p --auto-repair --check --optimize --all-databases
