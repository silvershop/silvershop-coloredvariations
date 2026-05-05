<p>Available colors</p>

<% loop Colors %>
	<div class="silvershop-swatch" style="background-color: #$Color;">
		$Value
	</div>
	<div>
		<% if ColorImages %>
			<% loop ColorImages %>
			    $Me
			<% end_loop %>
		<% end_if %>
	</div>
<% end_loop %>