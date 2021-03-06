


# php
rbeta-test.check: rbeta.php rbeta-test.php
	phpunit rbeta-test.php
	touch rbeta-test.check
rbeta-test-speed.check: rbeta.php rbeta-test-speed.php
	phpunit rbeta-test-speed.php
	touch rbeta-test-speed.check

thompson-test.check: thompson.php thompson-test.php
	phpunit thompson-test.php
	touch thompson-test.check

# ruby
rbeta.rb.check: rbeta.rb rbeta-test.rb
	ruby -I . -d rbeta-test.rb
	touch rbeta.rb.check

rbeta.rb.speed.check: rbeta.rb rbeta-test-speed.rb
	ruby -I . -d rbeta-test-speed.rb

thompson.rb.check: thompson.rb thompson-test.rb
	ruby -I . -d thompson-test.rb

