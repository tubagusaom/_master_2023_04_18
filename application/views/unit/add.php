<div class="form-panel" style="margin-left: 20px;margin-top: 20px; margin-bottom: 30px;">
    <div class="x-panel-bwrap">
        <form id="myform">
            <table class="table-data">
                <tr>
                    <td style="width: 80px;">Kode Unit : </td>
                    <td>
                        <input id="id_unit_kompetensi" name="id_unit_kompetensi" style="width: 280px;" class="easyui-textbox" data-options="required: true">
                    </td>
                </tr>
                <tr>
                    <td style="width: 80px;">Unit Kompetensi : </td>
                    <td>
                        <input id="unit_kompetensi" name="unit_kompetensi" style="width: 280px;" class="easyui-textbox" data-options="required: true">
                </tr>
                 <tr>
                    <td style="width: 80px;">Translate of Unit Competency : </td>
                    <td><input id="translate" name="translate" style="width: 280px;" class="easyui-textbox" data-options="required: true">
                    </td>
                </tr>
                <tr>
                    <td style="width: 100px;">Skema : </td>
                    <td>
                        <?php echo form_dropdown('id_skema', $skema, '', 'id="id_skema" class="easyui-combobox"  data-options="required: true" style="width:300px"'); ?>
                    </td>
                </tr>

            </table>
        </form>
    </div>
</div>