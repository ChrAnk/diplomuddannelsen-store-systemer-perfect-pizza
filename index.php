<?php
error_reporting(1);

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>

<html>
	<head>
		<title>Perfect Pizza</title>
	</head>
	<body>
		<h1>Perfect Pizza</h1>
		<section>
			<h2>Form</h2>
			<form action="services/receive-order/receive-order.php" method="post">
				<fieldset>
					<label>
						JSON
						<textarea name="data" rows="5" cols="100"></textarea>
					</label>

					<input type="submit" value="Submit">
				</fieldset>
			</form>
		</section>

		<section>
			<h2>Queries</h2>

			<table>
				<thead>
					<tr>
						<th>Method</th>
						<th>Send</th>
						<th>Return</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th>Subscribe (GET)</th>
						<td>
							<pre>
{
	"action": "subscribe",
	"method": "get"
}
							</pre>
						</td>
						<td rowspan="2">
							<pre>
{
	"rows": [int], // Number of affected rows
	"data": [guidv4] // GUID to be used the subsequent requests
}
							</pre>
						</td>
					</tr>
					
					<tr>
						<th>Subscribe (POST) (currently disabled)</th>
						<td>
							<pre>
{
	"action": "subscribe",
	"method": "post",
	"address": [string] // URL to which posts should be sent for each other
}
							</pre>
						</td>
					</tr>
				</tbody>
				<tbody>
					<tr>
						<th>Unsubscribe</th>
						<td>
							<pre>
{
	"action": "unsubscribe",
	"guid": [guidv4] // GUID to be unsubscribed
}
							</pre>
						</td>
						<td>
							<pre>
{
	"rows": [int], // Number of affected rows
}
							</pre>
						</td>
					</tr>
				</tbody>
				<tbody>
					<tr>
						<th>Create order</th>
						<td>
							<pre>
{
	"action": "create_order",
	"area": [int], // Table that created order
	"item": [int] // Item ordered
}
							</pre>
						</td>
						<td>
							<pre>
{
	"rows": [int], // Number of affected rows
}
							</pre>
						</td>
					</tr>
				</tbody>
				<tbody>
					<tr>
						<th>Get all orders</th>
						<td>
							<pre>
{
	"action": "get_orders",
	"guid": [guidv4], // GUID to confirm identity
	"area": [int], // Optional - limit to specific table
	"from_time" [timestamp] // Optional - limit to orders received after specified time
}
							</pre>
						</td>
						<td>
							<pre>
{
	"rows": [int], // Number of returned rows
	"data": [
		{
			"area": [int], // Table that created order
			"item": [int], // Item ordered
			"timestamp": [timestamp] // Timestamp order was received
		},
		...
	]
}
							</pre>
						</td>
					</tr>
				</tbody>
			</table>
		</section>
	</body>
</html>