<nav>
	<ul id="menu">
		<li><a href="/">Articles</a></li>
		<?php if(Auth::logged()):?>
		<li><a href="/user/logout">Logout</a></li>
		<?php else:?>
		<li><a href="/user/index">Register</a></li>		
		<li><a href="/user/authorization">Login</a></li>
		<?php endif;?>
	</ul>
</nav>