#!/usr/bin/env bash

today=`date '+%Y_%m_%d__%H_%M_%S'`;
filename="dump-$today.sql"

docker exec $1 pg_dump -U laradock your_project_local > ${filename}
