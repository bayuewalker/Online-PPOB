<!DOCTYPE html>
<html lang="en">
	<head>
		<title>ARDANA OnLiNe</title>
		@include('general.head')
	</head>

  	<body>

		@include('general.navbar-guest')
		<div class="col-md-12 merchant-welcome">
			<div id="welcome-text">
				<h2>Are You A Merchant</h2>
			</div>
			<div class="container welcome-button">
				<div class="row">
					<a href="merchant/register"><button type="button" class="btn btn-success">Register</button></a>
					<a href="merchant/login"><button type="button" class="btn btn-success">Login</button></a>
				</div>
			</div>
		</div>
	  	@include('general.bottom-scripts')
	  	<script src="<?= asset('app/controllers/cpns.js') ?>"></script>
	</body>
</html>