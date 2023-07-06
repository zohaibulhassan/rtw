<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-toolbar">
                <div class="md-card-toolbar-actions">
                    <i class="md-icon material-icons md-card-toggle" style="opacity: 1; transform: scale(1);"></i>
                </div>
                <h3 class="md-card-toolbar-heading-text">Filters </h3>
            </div>
            <div class="md-card-content" >
                <form action="<?php echo base_url('admin/products'); ?>" method="get">
                    <div class="uk-grid">
                        <div class="uk-width-large-1-4">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Supplier</label>
                                <select name="supplier" class="uk-width-1-1" id="supplier_select">
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-large-1-4">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Category/Sub Category</label>
                                <select name="category" class="uk-width-1-1" id="category_select">
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-large-1-4">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Product Group</label>
                                <select name="group" class="uk-width-1-1" id="group_select">
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-large-1-4">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Status</label>
                                <select name="status" class="uk-width-1-1 select2"  >
                                    <option value="">ALL</option>
                                    <option value="1">Active</option>
                                    <option value="0">Deactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-large-1-4" style="padding-top: 20px;">
                            <button type="submit" class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light" >Submit</button>
                            <a href="<?php echo base_url('admin/products'); ?>" class="md-btn md-btn-danger md-btn-wave-light waves-effect waves-button waves-light" >Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="md-card">
            <div class="md-card-toolbar">
                <h3 class="md-card-toolbar-heading-text">Products</h3>
                <div class="md-card-toolbar-actions">
                    <i class="md-icon fa-solid fa-expand md-card-fullscreen-activate toolbar_fixed"></i>
                    <div class="md-card-dropdown" data-uk-dropdown="{pos:'bottom-right',mode:'click'}">
                        <i class="md-icon material-icons">&#xE5D4;</i>
                        <div class="uk-dropdown uk-dropdown-small">
                            <ul class="uk-nav">
                                <li><a href="<?php echo base_url('admin/products/add'); ?>"> Add Product</a></li>
                                <li><a href="#" class="addbulkproducts"> Add Bulk Product</a></li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
            <div class="md-card-content">
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-large-1-1">
                        <div class="dt_colVis_buttons"></div>
                        <table id="productsTable" class="uk-table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Barcode</th>
                                    <th>Name</th>
                                    <th>Cost</th>
                                    <th>MRP</th>
                                    <th>Alert Quantity</th>
                                    <th>Unit</th>
                                    <th>Category</th>
                                    <th>Sub Category</th>
                                    <th>Available Stock</th>
                                    <th>Group ID</th>
                                    <th>Group Name</th>
                                    <th>Status</th>
                                    <th class="dt-no-export" >Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="uk-modal" id="modal_newbulkitem">
    <?php
        $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'addbulk');
        echo admin_form_open_multipart(base_url('admin/products/add_bulk_products'), $attrib);
    ?>
        <div class="uk-modal-dialog">
            <div class="uk-modal-header">
                <h3 class="uk-modal-title">Add New Bulk Products</h3>
            </div>
            <div class="uk-modal-body">
                <div class="uk-grid">
                    <div class="uk-width-large-1-1">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Bulk File <span class="red" >*</span></label>
                            <br>
                            <input type="file" name="itemsfile" class="md-input md-input-success label-fixed" required >
                        </div>
                        <span>Download Sample File <a href="<?php echo base_url('samples/sample_add_products.csv');?>" download >Click Here</a></span>
                    </div>
                </div>
            </div>
            <div class="uk-modal-footer uk-text-right">
                <button type="submit" class="md-btn md-btn-success md-btn-flat" >Submit</button>
                <button type="button" class="md-btn md-btn-flat uk-modal-close" >Close</button>
            </div>
        </div>
    <?php echo form_close(); ?>
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
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/datatables-buttons/js/buttons.colVis.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/datatables-fixedcolumns/dataTables.fixedColumns.min.js"></script>

<!-- datatables custom integration -->
<script src="<?php echo base_url('themes/v1/assets/'); ?>js/custom/datatables/datatables.uikit.min.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>js/datatable.js"></script>
<script>
    var csrfName = "<?php echo $this->security->get_csrf_token_name(); ?>",
        csrfHash = "<?php echo $this->security->get_csrf_hash(); ?>";
    var data = {
        [csrfName]:csrfHash,
        warehouse: "<?php echo $warehouse_id ?>",
        supplier: "<?php echo $supplier ?>",
        category: "<?php echo $category ?>",
        group: "<?php echo $group ?>",
        status: "<?php echo $status ?>"
    };
    $.DataTableInit({
        selector:'#productsTable',
        url:"<?= admin_url('products/get_list'); ?>",
        data:data,
        aaSorting: [[1, "desc"]],
        columnDefs: [
            { 
                "targets": 0,
                "orderable": false
            },
            { 
                "targets": 13,
                "orderable": false
            }
        ],
        fixedColumns:   {left: 0,right: 2},
        scrollX: true
    });
    $(document).on('click','.addbulkproducts',function(){
        UIkit.modal('#modal_newbulkitem').show();
    });
</script>

<script>
    $(document).ready(function(){
        $('.select2').select2();
        $('#warehouse_select').select2({
            ajax: {
                url: '<?php echo base_url("admin/general/warehouses"); ?>',
                dataType: 'json',
            },
            formatResult: function (data, term) {
                console.log(data);
                console.log(term);
                return data;
            },
        });
        $('#supplier_select').select2({
            ajax: {
                url: '<?php echo base_url("admin/general/suppliers"); ?>',
                dataType: 'json',
            },
            formatResult: function (data, term) {
                console.log(data);
                console.log(term);
                return data;
            },
        });
        $('#brand_select').select2({
            ajax: {
                url: '<?php echo base_url("admin/general/brands"); ?>',
                dataType: 'json',
            },
            formatResult: function (data, term) {
                console.log(data);
                console.log(term);
                return data;
            },
        });
        $('#category_select').select2({
            ajax: {
                url: '<?php echo base_url("admin/general/categories"); ?>',
                dataType: 'json',
            },
            formatResult: function (data, term) {
                console.log(data);
                console.log(term);
                return data;
            },
        });
        $('#group_select').select2({
            ajax: {
                url: '<?php echo base_url("admin/general/groups"); ?>',
                dataType: 'json',
            },
            formatResult: function (data, term) {
                console.log(data);
                console.log(term);
                return data;
            },
        });
        $(document).on('click','.deleteproduct',function(){
            var id = $(this).data('id');
            Swal.fire({
                title: "Do you want to delete this product. Please Enter Reason",
                input: "text",
                showCancelButton: true,
                confirmButtonColor: "#e53935",
                confirmButtonText: "Delete",
                cancelButtonText: "Cancel",
                buttonsStyling: true
            }).then(function (res) {
                if(res.isConfirmed){
                    Swal.fire({
                        title: 'Deleting Product!',
                        showCancelButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                            $.ajax({
                                url: '<?php echo base_url("admin/products/delete"); ?>',
                                type: 'POST',
                                data: {[csrfName]:csrfHash,id:id,reason:res.value},
                                success: function(data) {
                                    var obj = jQuery.parseJSON(data);
                                    swal.close()
                                    if(obj.status){
                                        toastr.success(obj.message);
                                        $('#productsTable').DataTable().ajax.reload()
                                    }
                                    else{
                                        toastr.error(obj.message);
                                    }
                                    
                                }
                            });
                        }
                    });
                }
            })
        });
        $('#addbulk').submit(function(e){
            e.preventDefault();
            $('.submitbtn').prop('disabled', true);
            $.ajax({
                url: '<?php echo base_url('admin/products/insert_bulk'); ?>',
                type: 'POST',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    var obj = jQuery.parseJSON(data);
                    console.log(obj);
                    if(obj.status){
                        toastr.success(obj.message);
                        location.reload();
                        UIkit.modal('#modal_newbulkitem').hide();
                    }
                    else{
                        toastr.error(obj.message);
                    }
                    $('.submitbtn').prop('disabled', false);
                }
            });
        });



    });
</script>
