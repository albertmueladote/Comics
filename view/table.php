
<div class="container">
	<table>
		<thead>
			<tr>
				<?php
				foreach ($collection->getAttr() as $field => $value) {
					echo "<th>" . ucwords($field) . "</th>";
				}
				?>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($collections as $c) {
				echo "<tr id='" . $c['id'] . "'>";
				foreach ($collection->getAttr() as $field => $value) {
					echo "<td class='" . $field . "' id='" . $field ."_" . $c['id'] . "'>" . $c[$field] . "</td>";
				}
				echo "</tr>";
			}
			?>
		</tbody>
	</table>
</div>
