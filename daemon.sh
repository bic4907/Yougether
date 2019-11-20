#!/bin/bash

while true; do
	php artisan room:manage
	php artisan user:delete
	php artisan user:exit
	php artisan room:delete
	sleep 1
done
