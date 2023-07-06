<style>
    .uk-open>.uk-dropdown,
    .uk-open>.uk-dropdown-blank {}
</style>
<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-content">
                <?php
                $attrib = ['data-toggle' => 'validator', 'role' => 'form', 'id' => 'stForm'];
                echo admin_form_open_multipart('reports/profitandlosspost', $attrib);
                ?>
                <input type="hidden" name="show_type" value="2">
                <div class="uk-grid">
                    <div class="uk-width-large-1-2">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Form Date</label>
                            <input class="md-input  label-fixed" type="text" name="start_date" data-uk-datepicker="{format:'YYYY-MM-DD'}" autocomplete="off" value="<?php echo $date_from; ?>" readonly>
                        </div>
                    </div>
                    <div class="uk-width-large-1-2">
                        <div class="md-input-wrapper md-input-filled">
                            <label>To Date</label>
                            <input class="md-input  label-fixed" type="text" name="end_date" data-uk-datepicker="{format:'YYYY-MM-DD'}" autocomplete="off" value="<?php echo $date_to; ?>" readonly>
                        </div>
                    </div>
                    <div class="uk-width-large-1-4" style="padding-top: 20px;">
                        <button type="submit" class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light">Submit</button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
        <div class="md-card">
            <div class="md-card-toolbar">
                <h3 class="md-card-toolbar-heading-text">Inventory Adjustment Summary</h3>
                <div class="md-card-toolbar-actions">
                    <i class="md-icon material-icons md-card-fullscreen-activate"></i>
                </div>
            </div>
            <div class="md-card-content">
                <div class="dt_colVis_buttons"></div>
                <table id="dt_tableExport" class="uk-table ">
                <thead>
                        <tr>
                            <th>ID</th>
                            <th>Item Name</th>
                            <th>SKU</th>
                            <th>Quantity Order</th>
                            <th>Quantity In</th>
                            <th>Quantity Out</th>
                            <th>Stock On Hand</th>
                            <th>Committed Stock</th>
                            <th>Available For Sale</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td>1</td>
                            <td>Product 1</td>
                            <td>BRD-0001-k990</td>
                            <td>3</td>
                            <td>1</td>
                            <td>3</td>
                            <td>2</td>
                            <td>1</td>
                            <td>2</td>
                            <td>
                                <a class="uk-button uk-button-danger">Delete</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- datatables -->
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
<!-- datatables buttons-->
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/datatables-buttons/js/dataTables.buttons.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>js/custom/datatables/buttons.uikit.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/jszip/dist/jszip.min.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/pdfmake/build/pdfmake.min.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/pdfmake/build/vfs_fonts.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/datatables-buttons/js/buttons.colVis.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/datatables-buttons/js/buttons.html5.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/datatables-buttons/js/buttons.print.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/datatables-fixedcolumns/dataTables.fixedColumns.min.js"></script>
<!-- datatables custom integration -->
<script src="<?php echo base_url('themes/v1/assets/'); ?>js/custom/datatables/datatables.uikit.min.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>js/datatable.js"></script>
<script>
    var csrfName = "<?php echo $this->security->get_csrf_token_name(); ?>",
        csrfHash = "<?php echo $this->security->get_csrf_hash(); ?>";
    var data = [];
    data[csrfName] = csrfHash;

    $(document).ready(function() {
        var table = $('#dt_tableExport').DataTable({
            dom: 'Bfrtip',
            "scrollX": true,
            "scrollCollapse": true,
            buttons: [
                'copy', 'csv', 'excel', {
                    extend: 'pdf',
        title: 'INVENTORY ADJUSTMENT',
        exportOptions: {
          columns: [0, 1, 2, 3]
        },
        customize: function(doc) {
          doc.content.splice(0, 1); // Remove the default title

          doc.styles.tableHeader.alignment = 'left';

          doc.content[0].table.widths = ['10%', '40%', '25%', '25%'];
          doc.content[0].table.body[0][0].text = '#';
          doc.content[0].table.body[0][1].text = 'Item & Description';
          doc.content[0].table.body[0][2].text = 'Quantity';
          doc.content[0].table.body[0][3].text = 'Adjusted Cost Price';

        }
     
                },"print"
            ]
        });
    })
</script>
<script>
    $(document).ready(function() {
        $('#dt_tableExport').DataTable();
    });
</script>