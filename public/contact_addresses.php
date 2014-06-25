<?php

?>
<html>
<head>
	<title>Address Book: Contact Addresses</title>
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
</head>
<body>

<div class="container">

	<h1>
		Address for Contact: Chris
		<a href="contacts.php" class="btn btn-default pull-right">Back to Contacts</a>
	</h1>

	<table class="table table-striped">
		<tr>
			<th>Street</th>
			<th>City</th>
			<th>State</th>
			<th>Zip</th>
			<th>Actions</th>
		</tr>
		<tr>
			<td>123 Somewhere Dr.</td>
			<td>SA</td>
			<td>TX</td>
			<td>78205</td>
			<td>
				<button class="btn btn-small btn-danger btn-remove" data-address="1">Remove</button>
			</td>
		</tr>
		<tr>
			<td>234 Somewhere Dr.</td>
			<td>SA</td>
			<td>TX</td>
			<td>78205</td>
			<td>
				<button class="btn btn-small btn-danger btn-remove" data-address="1">Remove</button>
			</td>
		</tr>
	</table>

	<div class="clearfix"></div>

	<h2>Add New Address</h2>
	<form class="form-inline" role="form" action="contact_addresses.php?contact_id=1" method="POST">
		<div class="form-group">
			<label class="sr-only" for="street">Street</label>
			<input type="text" name="street" id="street" class="form-control" placeholder="Street">
		</div>
		<div class="form-group">
			<label class="sr-only" for="city">City</label>
			<input type="text" name="city" id="city" class="form-control" placeholder="City">
		</div>
		<div class="form-group">
			<label class="sr-only" for="state">State</label>
			<input type="text" name="state" id="state" class="form-control" placeholder="State">
		</div>
		<div class="form-group">
			<label class="sr-only" for="zip">Zip</label>
			<input type="text" name="zip" id="zip" class="form-control" placeholder="Zip">
		</div>
		<button type="submit" class="btn btn-default btn-success">Add New Address</button>
	</form>

	<h2>Add Existing Address</h2>
	<form class="form-inline" role="form" action="contact_addresses.php?contact_id=1" method="POST">
		<div class="form-group">
			<select class="form-control" name="address_id">
			  <option value="3">345 Somewhere Dr., SA, TX, 78205</option>
			  <option value="4">456 Somewhere Dr., SA, TX, 78205</option>
			  <option value="5">789 Somewhere Dr., SA, TX, 78205</option>
			</select>
		</div>
		<button type="submit" class="btn btn-default btn-success">Add Existing Address</button>
	</form>

</div>

<form id="remove-form" action="contact_addresses.php?contact_id=1" method="post">
	<input id="remove-id" type="hidden" name="remove" value="">
</form>

<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
 <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<script>

$('.btn-remove').click(function () {
	var addressId = $(this).data('address');
	if (confirm('Are you sure you want to remove address ' + addressId + '?')) {
		$('#remove-id').val(addressId);
		$('#remove-form').submit();
	}
});

</script>

</body>
</html>