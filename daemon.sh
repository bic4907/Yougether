#!/bin/bash

while true; do
	php artisan room:manage
	php artisan user:delete
	php artisan user:exit
	sleep 1
done
