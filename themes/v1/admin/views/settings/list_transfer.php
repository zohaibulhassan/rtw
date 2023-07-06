<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-toolbar">
                <h3 class="md-card-toolbar-heading-text">Transfer Adjustments</h3>
                <div class="md-card-toolbar-actions">
                    <i class="md-icon material-icons md-card-fullscreen-activate"></i>
                </div>
            </div>
            <div class="md-card-content">
                <div class="dt_colVis_buttons"></div>
                <table id="dt_tableExport" class="uk-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Transfer Order #</th>
                            <th>Reason</th>
                            <th>Status </th>
                            <th>Quantity Transferred</th>
                            <th>Source Warehouse</th>
                            <th>Destination Warehouse</th>
                            <th>Created Date</th>
                            <th>Last Modified BY</th>
                            <th>Last Modified Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <?php
                    foreach ($transfer_order as $row) {


                    ?>
                        <tr>
                            <td><?php echo $row->id; ?></td>
                            <td><?php echo $row->Date; ?></td>
                            <td><?php echo $row->Transfer_order; ?></td>
                            <td><?php echo $row->Reason; ?></td>
                            <td><?php echo $row->Status; ?></td>
                            <td><?php echo $row->Qty_Transfer; ?></td>
                            <td><?php echo $row->f_warehouse; ?></td>
                            <td><?php echo $row->t_warehouse; ?></td>
                            <td><?php echo $row->created_at; ?></td>
                            <td><?php echo $row->last_modified_by; ?></td>
                            <td><?php echo $row->last_modified_time; ?></td>
                            <td>
                                <a class="md-btn md-btn-danger md-btn-flat " title="Delete">Delete</a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>


                </table>
            </div>
        </div>
    </div>
</div>
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
        $('#dt_tableExport').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#dt_tableExport').DataTable();
    });
</script>