<nav id="top-bar" class="collapse top-bar-collapse">

		<ul class="nav navbar-nav pull-right">
			<li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;">
					<i class="fa fa-user"></i>
					<?= $this->tank_auth->get_username() ?>
		        	<span class="caret"></span>
		    	</a>

		    	<ul class="dropdown-menu" role="menu">
			        <li>
			        	<a href="<?= site_url('/auth/logout' ); ?>">
			        		<i class="fa fa-sign-out"></i> Logout
			        	</a>
			        </li>
		    	</ul>
		    </li>
		</ul>

	</nav> <!-- /#top-bar -->