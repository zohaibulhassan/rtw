<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-toolbar">
                <h3 class="md-card-toolbar-heading-text">Inventory Adjustments</h3>
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
                            <th>Reference</th>
                            <th>Reason</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Type</th>
                            <th>User Name</th>
                            <th>Created At</th>
                            <th>Last Modified Name</th>
                            <th>Last Modified Date</th>
                            <th>Warehouse ID</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($inventory_adjustments as $row) {


                        ?>
                            <tr>
                                <td><?php echo $row->id; ?></td>
                                <td><?php echo $row->reference; ?></td>
                                <td><?php echo $row->reason; ?></td>
                                <td><?php echo $row->description; ?></td>
                                <td><?php echo $row->status; ?></td>
                                <td><?php echo $row->type; ?></td>
                                <td><?php echo $row->first_name; ?></td>
                                <td><?php echo $row->created_at; ?></td>
                                <td><?php echo $row->last_modified_name; ?></td>
                                <td><?php echo $row->last_modified_date; ?></td>
                                <td><?php echo $row->name; ?></td>
                                <td><?php echo $row->date; ?></td>
                                <td>
                                    <a class="md-btn md-btn-danger md-btn-flat " title="Delete">Delete</a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>

                    </tbody>
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
            scrollX:true,
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