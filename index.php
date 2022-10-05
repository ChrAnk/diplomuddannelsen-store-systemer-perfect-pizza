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
			<form action="services/receive-order.php" method="post">
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

			<section>
				<h3>Subscribe</h3>
				<h4>GET</h4>
				<pre>
{
"action": "subscribe",
"method": "get"
}
				</pre>

				<h4>POST</h4>
				<pre>
{
"action": "subscribe",
"method": "post",
"address": "https://example.com/"
}
				</pre>
			</section>

			<section>
				<h3>Unsubscribe</h3>
				<pre>
{
"action": "unsubscribe",
"guid": "6ccad857-e4fc-2606-70ec-83aa971db62f"
}
				</pre>
			</section>

			<section>
				<h3>Create order</h3>
				<pre>
{
"action": "create_order",
"area": 1,
"item": 2
}
				</pre>
			</section>
			
			<section>
				<h3>Get orders</h3>
				<h4>All orders</h4>
				<pre>
{
"action": "get_orders",
"guid": "6ccad857-e4fc-2606-70ec-83aa971db62f"
}
				</pre>
				
				<h4>All orders for table</h4>
				<pre>
{
"action": "get_orders",
"guid": "6ccad857-e4fc-2606-70ec-83aa971db62f",
"area": 1
}
				</pre>
				
				<h4>All orders after time</h4>
				<pre>
{
"action": "get_orders",
"guid": "6ccad857-e4fc-2606-70ec-83aa971db62f",
"from_time": "2022-01-02 01:01:01"
}
				</pre>
			</section>
		</section>
	</body>
</html>