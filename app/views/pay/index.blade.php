<div class="jumbotron">
  <div class="container">
    <span class="glyphicon glyphicon-list-alt"></span>
    <h2>{{ $module['name'] }}</h2>

    <form id="payment" name="payment" method="post" action="https://sci.interkassa.com/" enctype="utf-8">
    	<input type="hidden" name="ik_co_id" value="5370b755bf4efccb31ad6f90" />
		<input type="hidden" name="ik_pm_no" value="{{ $module['code'] }}" />
		<input id="module-price-internal" type="hidden" name="ik_am" value="{{ $module['price'] }}" />
		<input type="hidden" name="ik_cur" value="USD" />
		<input type="hidden" name="ik_desc" value="" />
		<input type="hidden" name="ik_sign" value="821nsSUuTIN/njMBN/cs8Q==">

	    <div class="box">
		    <div class="form-group">
		        <input id="final-customer-email" type="text" value="{{ $email }}" placeholder="Введите email">
			    <input id="final-customer-domain" type="text" value="{{ $domain }}" placeholder="Введите ваш домен">
		    </div>

		    <div class="form-group">
		    	<select name="module_types" id="final-module-type">
			    	@foreach ($module['types'] as $type)
			    		@if ($type['id'] == $module_type)
			    			<option selected="selected" 
			    		@else
			    			<option 
			    		@endif
			    			value="{{ $type['id'] }}" 
			    			data-price="{{ $type['price'] }}" 
		    			>{{ $type['name'] }}</option>
			    	@endforeach
		    	</select>
		    </div>
		    
		    <div class="alert alert-info form-group" id="total-price">
		    	Total price - <span>10$</span>
		    </div>

		    <button type="submit" class="btn btn-info">
		    	Оплатить
		    </button>
	    </div>
    </form>
  </div>
</div>


<script type="text/javascript" src="//code.jquery.com/jquery.min.js"></script>
<script type="text/javascript">
	$(document).on('ready', function() {
		$('#payment').on('submit', function(e) {
			var module_name = $('#final-module-name').html();
			var customer_email = $('#final-customer-email').val();
			var customer_domain = $('#final-customer-domain').val();
			var module_type = $('#final-module-type').val();

			var description = "{{ $module['pay_description'] }}";

			description = description.replace(/domain/i, customer_domain, description);
			description = description.replace(/email/i, customer_email, description);
			description = description.replace(/type/i, module_type, description);

			$(this).find('input[name=ik_desc]').val(description);

			$('#payment').submit();
			
			e.preventDefault();
		});

		$('#final-module-type').on('change', function() {
			var $this = $(this),
				price = $this.find('option:checked').data('price');

			$('#module-price-internal').val(price);
			$('#total-price span').html(price + '$');

			if (price <= 0) {
				$('#payment button[type=submit]').attr('disabled', true);
			} else {
				$('#payment button[type=submit]').attr('disabled', false);
			};

		}).trigger('change');
	});
</script>

<style type="text/css">
body {
    background: url(http://habrastorage.org/files/c9c/191/f22/c9c191f226c643eabcce6debfe76049d.jpg);
}

.jumbotron {
	text-align: center;
	width: 30rem;
	border-radius: 0.5rem;
	position: relative;
	margin: 4rem auto;
	background-color: #fff;
	padding: 2rem;
}

.container .glyphicon-list-alt {
	font-size: 10rem;
	margin-top: 3rem;
	color: #f96145;
}

input {
	width: 100%;
	margin-bottom: 1.4rem;
	padding: 1rem;
	background-color: #ecf2f4;
	border-radius: 0.2rem;
	border: none;
}
h2 {
	margin-bottom: 3rem;
	font-weight: bold;
	color: #ababab;
}
.btn {
	border-radius: 0.2rem;
}
.btn .glyphicon {
	font-size: 3rem;
	color: #fff;
}
.full-width {
	background-color: #8eb5e2;
	width: 100%;
	-webkit-border-top-right-radius: 0;
	-webkit-border-bottom-right-radius: 0;
	-moz-border-radius-topright: 0;
	-moz-border-radius-bottomright: 0;
	border-top-right-radius: 0;
	border-bottom-right-radius: 0;
}
</style>