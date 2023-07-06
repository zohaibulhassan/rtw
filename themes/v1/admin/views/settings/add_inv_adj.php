<style>
    .uk-open>.uk-dropdown,
    .uk-open>.uk-dropdown-blank {}

    .dt_colVis_buttons {
        display: none;
    }

    .summarytable {}

    .summarytable table {
        width: 30%;
        float: right;
    }

    .summarytable tr {}

    .summarytable th {}

    .summarytable td {}
</style>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-toolbar">
                <h3 class="md-card-toolbar-heading-text">Add Inventory Adjustment</h3>
                <div class="md-card-toolbar-actions">
                    <i class="md-icon material-icons md-card-fullscreen-activate"></i>
                </div>
            </div>
            <div class="md-card-content">


                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-large-1-3 container card card-body ">
                        <div class="md-input-wrapper md-input-filled">
                            <h5>Type</h5>
                            <!-- <br> -->
                            <!-- <input class="md-input label-fixed" type="text" name="date" data-uk-datepicker="{format:'YYYY-MM-DD'}" autocomplete="off" value="<?php echo isset($_POST['date']) ? $_POST['date'] : date('Y-m-d'); ?>" readonly required> -->
                            <input type="radio" class="md-input label-fixed" name="type" id="" value="">Quantity Adjustment
                            <input type="radio" class="md-input label-fixed" name="type" id="" value="">Value Adjustment
                        </div>
                    </div>


                    <div class="uk-width-large-1-3">
                        <div class="md-input-wrapper md-input-filled">
                            <label>User Name</label>
                            <input class="md-input label-fixed" type="text" name="date" data-uk-datepicker="{format:'YYYY-MM-DD'}" autocomplete="off" value="<?php echo isset($_POST['date']) ? $_POST['date'] : date('Y-m-d'); ?>" readonly required>
                        </div>
                    </div>




                    <div class="uk-width-large-1-3">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Reason <span class="red">*</span></label>
                            <select name="" id="" class="uk-width-1-1 select2">
                                <option value="Stolen Goods">Stolen Goods</option>
                                <option value="Damaged Quantity">Damaged Quantity</option>
                                <option value="Missing">Missing</option>
                            </select>
                        </div>
                    </div>



                    <div class="uk-width-large-1-1">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Description <span class="red">*</span></label>
                            <textarea cols="30" rows="4" class="md-input autosized" style="overflow-x: hidden; overflow-wrap: break-word; height: 121px;" required name="note"></textarea>
                        </div>

                    </div>
                    <br>


                    <div class="uk-width-large-1-3">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Warehouse *</label>
                            <div class="panel panel-warning">
                                <div class="panel-body" style="padding: 5px;">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <select name="warehosue" id="warehosue_id" class="uk-width-1-1 select2" required>
                                                <?php
                                                foreach ($this->data['warehouses'] as $warehouse) { ?>
                                                    <option value="<?php echo $warehouse->id; ?>">
                                                        <?php echo $warehouse->name; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-large-1-1">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Select Products </label>
                            <input type="text" name="products" id="searchproduct" class="md-input md-input-success label-fixed" placeholder="Enter Product Name or Barcode">
                            <div id="suggesstion-box"></div>
                        </div>
                    </div>
                </div>
                <div style="margin-top:50px">
                    <div class="dt_colVis_buttons"></div>
                    <table class="uk-table" style="width:100%" id="dt_tableExport">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Quanitity Avaliable </th>
                                <th>Quanitity In hand </th>
                                <th>Quanitity Adjusted</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-large-1-1">
                        <button class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light" id="submitbtn" type="submit">Submit</button>
                        <button class="md-btn md-btn-danger md-btn-wave-light waves-effect waves-button waves-light" type="button">Reset</button>
                    </div>
                </div>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
</div>

<input type="hidden" name="">
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

<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js" integrity="sha256-lSjKY0/srUM9BE3dPm+c4fBo1dky2v27Gdjm2uoZaL0=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {

        $('#resetBtn').click(function() {
            localStorage.removeItem("po_items");
            $("#dt_tableExport").DataTable().destroy();
            loaditems();
        });
        $('#alertQtybtn').click(function() {
            $("#alertQtybtn").prop('disabled', true);
            $.ajax({
                type: 'get',
                url: '<?= admin_url('purchases/alertqty'); ?>',
                data: {
                    supplier_id: $("#supplier_id").val(),
                    warehouse_id: $("#warehosue_id").val()
                },
                success: function(data) {
                    localStorage.setItem('po_items', data);
                    // localStorage.setItem('po_items',JSON.stringify(data));
                    $("#alertQtybtn").prop('disabled', false);
                    $("#dt_tableExport").DataTable().destroy();
                    loaditems();

                },
                error: function(jqXHR, textStatus) {
                    var errorStatus = jqXHR.status;
                    $("#alertQtybtn").prop('disabled', false);
                }
            });
        });


        $("#searchproduct").autocomplete({
            source: function(request, response) {
                var supplier_id = $('#warehosue_id').val();
                console.log(supplier_id);
                $.ajax({
                    type: 'get',
                    url: '<?php echo base_url('admin/general/searching_products3'); ?>',
                    dataType: "json",
                    data: {
                        supplier_id: supplier_id,
                    },
                    success: function(data) {
                        $(this).removeClass('ui-autocomplete-loading');
                        response(data);
                    }
                });
            },
            minLength: 1,
            autoFocus: false,
            delay: 250,
            response: function(event, ui) {
                if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                    $(this).removeClass('ui-autocomplete-loading');
                    $(this).val('');
                } else if (ui.content.length == 1 && ui.content[0].id != 0) {
                    ui.item = ui.content[0];
                    $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                    $(this).autocomplete('close');
                    $(this).removeClass('ui-autocomplete-loading');
                } else if (ui.content.length == 1 && ui.content[0].id == 0) {
                    $(this).removeClass('ui-autocomplete-loading');
                    $(this).val('');
                }
            },
            select: function(event, ui) {
                event.preventDefault();
                var warehouse_id = $('#warehosue_id').val();
                $.ajax({
                    type: 'get',
                    url: '<?php echo base_url('admin/general/select_products2'); ?>',
                    data: {
                        id: ui.item.item_id,
                        warehouse_id: warehouse_id
                    },
                    success: function(data) {
                        var obj = jQuery.parseJSON(data);
                        if (obj.codestatus) {

                            var items = localStorage.getItem('po_items');
                            if (items == null) {
                                items = [obj.products];
                                localStorage.setItem('po_items', JSON.stringify(items));
                                console.log(localStorage.getItem('po_items'));
                            } else {
                                var getitems = JSON.parse(localStorage.getItem('po_items'));
                                getitems.push(obj.products);
                                localStorage.setItem('po_items', JSON.stringify(getitems));
                            }
                            $("#dt_tableExport").DataTable().destroy();
                            loaditems();

                            $('#searchproduct').val('');
                        }
                    }
                });
            }
        });

        function loaditems() {
            var getitems = JSON.parse(localStorage.getItem('po_items'));
            var html = "";
            var totalnetamount = 0;
            var totalptax = 0;
            var totalitems = 0;
            $.each(getitems, function(index) {
                var item = this;
                total = (parseFloat(item.cost) + parseFloat(item.fed_tax) + parseFloat(item.product_tax)) * parseFloat(item.quantity);
                total = parseFloat(total).toFixed(4);
                var total_tax = parseFloat(item.product_tax) * parseFloat(item.quantity);
                total_tax = parseFloat(total_tax).toFixed(4);


                totalitems += parseFloat(item.quantity);
                totalptax += parseFloat(total_tax);
                totalnetamount += parseFloat(total);

                html += "<tr>";
                html += "<td>" + (index + 1);
                html += "<input type='hidden' name='product_id[]' value='" + item.id + "' >";
                html += "</td>";
                html += "<td>" + item.name + "</td>";
                html += "<td>" + item.cost + "</td>";
                html += "<td>" + item.mrp + "</td>";
                html += "<td>" + item.balance_qty + "</td>";
                html += "<td>" + item.alert_quantity + "</td>";
                html += "<td><input type='text' class='itemqty' name='qty[]' data-index='" + index + "' value='" + item.quantity + "'></td>";
                html += "<td>" + item.fed_tax + "</td>";
                html += "<td>" + total_tax + "</td>";
                html += "<td>" + total + "</td>";
                html += "<td>";
                html += "<a class='md-btn md-btn-danger md-btn-wave-light waves-effect waves-button waves-light md-btn-mini itemremove' data-index='" + index + "' >Remove</a>";
                html += "</td>";
                html += "</tr>";
            });
            // if(html == ""){
            //     $("#supplier_id").prop("disabled", false);
            // }
            // else{
            //     $("#supplier_id").prop("disabled", true);
            // }
            $('#totalnetamount').html(totalnetamount.toFixed(4));
            $('#totalptax').html(totalptax.toFixed(4));
            $('#totalitems').html(totalitems);
            $('#dt_tableExport tbody').html(html);
            $('#dt_tableExport').DataTable({
                fixedColumns: {
                    left: 0,
                    right: 2
                },
                scrollX: true,
                searching: false,
                paging: false

            });
        }
        loaditems();
        $(document).on('change', '.itemqty', function() {
            var qty = $(this).val();
            var getitems = JSON.parse(localStorage.getItem('po_items'));
            var index = $(this).data('index');
            if (qty.indexOf('*') != -1) {
                qty = qty.replace("*", "");
                qty = qty * getitems[index].pack_size;
            } else if (qty.indexOf('^') != -1) {
                qty = qty.replace("^", "");
                qty = qty * getitems[index].carton_size;
            }
            getitems[index].quantity = qty;
            localStorage.setItem('po_items', JSON.stringify(getitems));
            // console.log(localStorage.getItem('po_items'));
            $("#dt_tableExport").DataTable().destroy();
            loaditems();
        });
        $(document).on('click', '.itemremove', function() {
            var index = parseInt($(this).data('index'));
            var getitems = JSON.parse(localStorage.getItem('po_items'));
            getitems.splice(index, 1)
            localStorage.setItem('po_items', JSON.stringify(getitems));
            $("#dt_tableExport").DataTable().destroy();
            loaditems();
        });
        $('#submitFrom').submit(function(e) {
            e.preventDefault();
            $('#submitbtn').prop('disabled', true);
            $.ajax({
                url: '<?php echo base_url('admin/purchaseorder/submit'); ?>',
                type: 'POST',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    var obj = jQuery.parseJSON(data);
                    if (obj.status) {
                        toastr.success(obj.message);
                        $('#submitFrom')[0].reset();
                        localStorage.removeItem("po_items");
                        window.location.href = "<?php echo base_url('admin/purchaseorder/view/'); ?>" + obj.purchase_id;
                    } else {
                        toastr.error(obj.message);
                    }
                    $('#submitbtn').prop('disabled', false);
                }
            });
        });
    });
</script>