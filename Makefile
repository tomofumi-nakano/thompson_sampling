



rbeta-test.check: rbeta.php rbeta-test.php
	phpunit rbeta-test.php
	touch rbeta-test.check
