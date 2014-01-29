<p>Available colors</p>

<% loop Colors %>
	<div class="swatch" style="background-color: #$Color;">
		$Value
	</div>
	<div>
		<% if Images %>
			<% loop Images %>
			    $Me
			<% end_loop %>
		<% end_if %>
	</div>
<% end_loop %>