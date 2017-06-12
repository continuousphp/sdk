phpunit:
	./vendor/bin/phpunit -v -c tests/phpunit.xml

behat:
	./vendor/bin/behat -vv

test: phpunit behat	
