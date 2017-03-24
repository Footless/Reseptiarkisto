#!/bin/bash

source config/environment.sh

echo "Lisätään testidata..."

ssh $USERNAME@users.cs.helsinki.fi "
cd htdocs/$PROJECT_FOLDER/sql
psql < add_test_data.sql
psql < add_ings.sql
psql < add_macros.sql

exit"

echo "Valmis!"
