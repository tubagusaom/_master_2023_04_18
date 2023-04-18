<div class="form-panel" style="margin-left: 20px;margin-top: 20px; margin-bottom: 30px;">
	<div class="x-panel-bwrap">
		<form id="myform">
			<table class="table-data">
				<tr>
					<td style="width: 150px;">Controller ID : </td>
					<td>
						<?php echo form_dropdown('controller_id', $controller, '', 'id="controller_id" class="easyui-combobox"  data-options="required: true"'); ?>
					</td>
				</tr>
				<tr>
					<td style="width: 150px;">Method ID : </td>
					<td>
						<select multiple name="method[]">
							  <?php foreach ($methods as $key => $value) {
							  	 echo '<option value="'.$key.'">'.$value.'</option>';
							  }
							  ?>

							</select>
					</td>
				</tr>
				<tr>
					<td style="width: 150px;">Role ID : </td>
					<td>
						<select multiple name="role[]">
							  <?php foreach ($roles as $key => $value) {
							  	 echo '<option value="'.$key.'">'.$value.'</option>';
							  }
							  ?>

							</select>
					</td>
				</tr>
				<tr>
					<td style="width: 150px;">Ajax : </td>
					<td>
						<?php echo form_dropdown('request_method', $ajax, '', 'id="request_method" class="easyui-combobox"  data-options="required: true"'); ?>
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>