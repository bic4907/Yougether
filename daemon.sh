#!/bin/bash

while true; do
	php artisan room:manage
	php artisan user:delete
	sleep 1
done
