<?php

if (empty($_SERVER['HTTP_REFERER']))
{
	die();
}

// Store customer email for one day
if (isset($_GET['customer_email']))
{
	setcookie('customer_email', $_GET['customer_email'], time() + 60*60*24);
}


error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';
?>

<!DOCTYPE html>
<html>
<head>
	<title>Pay</title>

	<style type="text/css">
		html, body {
			height: 100%;
			overflow: hidden;
		}
		body.loading::after {
			position: absolute;
			content: "";
			display: block;
			left: 0;
			right: 0;
			top: 0;
			bottom: 0;
			width: auto;
			height: auto;
			background: #fff url(public/preloader.gif) no-repeat center center;
		}
	</style>
</head>
<body>

	<?php 

		// Get domain from where request came
		$parse = parse_url($_SERVER['HTTP_REFERER']);
		$domain = $parse['host'];

		// Here we are going to parse module code
		$module_code = empty($_GET['module_code']) ? 'undefined' : $_GET['module_code'];

		$moduleInfo = Module::where('code', $module_code)->first();
		
		if ( ! $moduleInfo)
		{
			// Handle error
		}
	?>

	<form id="payment" name="payment" method="post" action="https://sci.interkassa.com/" enctype="utf-8">
		<input type="hidden" name="ik_co_id" value="5370b755bf4efccb31ad6f90" />
		<input type="hidden" name="ik_pm_no" value="<?php echo $moduleInfo->code ?>" />
		<input type="hidden" name="ik_am" value="<?php echo $moduleInfo->price ?>" />
		<input type="hidden" name="ik_cur" value="USD" />
		<input type="hidden" name="ik_desc" value="" />
		<input type="hidden" name="ik_sign" value="821nsSUuTIN/njMBN/cs8Q==">

		<h1 id="final-module-name">Модуль - <?php echo $moduleInfo->name . ' ' . $moduleInfo->price ?>$</h1>
		<h3>Ваш email</h3>
		<input id="final-customer-email" type="text" value="<?php echo $_COOKIE['customer_email'] ?>" placeholder="Введите email">
		<h3>Домен</h3>
		<input id="final-customer-domain" type="text" value="<?php echo $domain ?>" placeholder="Введите ваш домен">

		<input type="submit" value="Оплатить">
	</form>


	<script type="text/javascript" src="//code.jquery.com/jquery.min.js"></script>
	<script type="text/javascript">
		$(document).on('ready', function() {
			$('#payment').on('submit', function(e) {
				var module_name = $('#final-module-name').html();
				var customer_email = $('#final-customer-email').val();
				var customer_domain = $('#final-customer-domain').val();

				var description = "<?php echo $moduleInfo->pay_description ?>";

				description = description.replace(/{{module_name}}/i, module_name, description);
				description = description.replace(/{{email}}/i, customer_email, description);
				description = description.replace(/{{domain}}/i, customer_domain, description);

				$(this).find('input[name=ik_desc]').val(description);

				$('#payment').submit();

				e.preventDefault();
			});
		});
	</script>

</body>
</html>