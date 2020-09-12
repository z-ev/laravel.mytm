composer install \
&& composer run-script post-create-project-cmd \
&& chmod -R 777 bootstrap/ storage/ vendor/ \
&& php-fpm
