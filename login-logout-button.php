<?php if (check_if_logged_in($userID) == 1 ): ?>
	<nav role="navigation" class="w-nav-menu"><form action="logout.php" method="get"> <input type="submit" class="w-button hero-button" value="logout"/></form></nav>
<?php  else: ?>
	<nav role="navigation" class="w-nav-menu"><a href="login.php" class="w-button hero-button">login</a> </nav>
<?php  endif; ?>
