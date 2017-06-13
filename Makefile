phpunit:
	./vendor/bin/phpunit -v -c tests/phpunit.xml

behat:
	./vendor/bin/behat -vv

cs:
	./vendor/bin/phpcs

test: phpunit behat cs	
