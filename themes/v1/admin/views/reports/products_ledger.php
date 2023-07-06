<style>
    .uk-open>.uk-dropdown,
    .uk-open>.uk-dropdown-blank {}
</style>
<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-toolbar">
                <div class="md-card-toolbar-actions">
                    <i class="md-icon material-icons md-card-toggle" style="opacity: 1; transform: scale(1);"></i>
                </div>
                <h3 class="md-card-toolbar-heading-text">Filters </h3>
            </div>
            <div class="md-card-content">
                <form action="<?php echo base_url('admin/reports/products_ledger'); ?>" method="get">
                    <input type="hidden" name="show_type" value="2">
                    <div class="uk-grid">
                        <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <?= lang("warehouse", "customers"); ?>
                                <?php
                                $whl[""] = lang('select') . ' ' . lang('warehouse');
                                foreach ($warehouses as $warehouse) {
                                    $whl[$warehouse->id] = $warehouse->name;
                                }
                                echo form_dropdown('warehouse', $whl, $swarehouse, 'class="form-control input-tip select" id="warehouse" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("warehouse") . '" style="width:100%;"');
                                ?>
                            </div>
                        </div>

                        <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Form Date</label>
                                <input class="md-input  label-fixed" type="text" name="start_date" data-uk-datepicker="{format:'YYYY-MM-DD'}" autocomplete="off" value="<?php echo $date_from ?>" readonly>
                            </div>
                        </div>
                        <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <label>To Date</label>
                                <input class="md-input  label-fixed" type="text" name="end_date" data-uk-datepicker="{format:'YYYY-MM-DD'}" autocomplete="off" value="<?php echo $date_to ?>" readonly>
                            </div>
                        </div>



                        <div class="uk-width-large-1-4" style="padding-top: 20px;">
                            <button type="submit" class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="md-card">
            <div class="md-card-toolbar">
                <h3 class="md-card-toolbar-heading-text">Product Ledger Report</h3>
                <div class="md-card-toolbar-actions">
                    <i class="md-icon material-icons md-card-fullscreen-activate"></i>
                </div>
            </div>
            <div class="md-card-content">
                <div class="dt_colVis_buttons"></div>
                <table id="dt_tableExport" class="uk-table ">
                <thead>
                            <tr>
                                <th>Sr #</th>
                                <th>Type</th>
                                <th>Reference #</th>
                                <th>PO #</th>
                                <th>Date</th>
                                <th>Product ID</th>
                                <th>Batch #</th>
                                <th>Customer / Supplier / Warehouse</th>
                                <th>Quantity</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            $data = array();
                            foreach ($products as $val) {
                            ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= $val['type'] ?></td>
                                    <td><?= $val['ref'] ?></td>
                                    <td><?= $val['po'] ?></td>
                                    <td><?= $val['date'] ?></td>
                                    <td><?= $val['product_id'] ?></td>
                                    <td><?= $val['batch'] ?></td>
                                    <td><?= $val['customer_supplier'] ?></td>
                                    <td><?= $val['qty'] ?></td>
                                    <td><?= $val['balance'] ?></td>
                                </tr>
                            <?php $i++;
                            } ?>
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