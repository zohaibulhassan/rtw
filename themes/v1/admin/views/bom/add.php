<style>
    .uk-open>.uk-dropdown, .uk-open>.uk-dropdown-blank{

    }
    .dt_colVis_buttons {
        display:none;
    }
    .summarytable {}
    .summarytable table{
        width: 30%;
        float: right;
    }
    .summarytable tr{}
    .summarytable th{}
    .summarytable td{}
</style>
<div id="page_content">
    <div class="md-card">
    <div id="page_content_inner">
            <div class="md-card-toolbar">
                <h3 class="md-card-toolbar-heading-text">Create BOM</h3>
                <div class="md-card-toolbar-actions">
                    <i class="md-icon material-icons md-card-fullscreen-activate"></i>
                </div>
            </div>
            <div class="md-card-content">
         <span> Want to upload bom list in bulk from <a  id="upload-link">Upload Sheet</a></span>
                <?php
                    $attrib = ['data-toggle' => 'validator', 'role' => 'form', 'id' => 'submitFrom'];
         echo admin_form_open_multipart('#', $attrib);
         ?>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Product Group <span class="red" >*</span></label>
                                <select name="group" id="grouptxt" class="uk-width-1-1 select2" required>
                                    <?php
                                 foreach ($groups as $group) {
                                     ?>
                                            <option value="<?php echo $group->id; ?>"><?php echo $group->name; ?></option>
                                            <?php
                                 }
         ?>
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Product <span class="red" >*</span></label>
                                <select name="product" class="uk-width-1-1" required id="product_select">
                                    <option value="">Select Product</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-large-1-1">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Select Items </label>
                                <input type="text" name="material" id="searchproduct" class="md-input md-input-success label-fixed" placeholder="Enter Material Name or Barcode">
                                <div id="suggesstion-box"></div>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top:50px">
                        <div class="dt_colVis_buttons"></div>
                        <table class="uk-table"  style="width:100%" id="dt_tableExport">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Item Name</th>
                                    <th>Rate</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                    <th class="dt-no-export" >Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <div class="summarytable" >
                        <table class="uk-table uk-table-striped ">
                            <tbody>
                                <tr>
                                    <td style="width:50%" ><b>Total Items</b></td>
                                    <td style="width:50%"  id="totalitems">0</td>
                                </tr>
                                <tr>
                                    <td><b>Total Cost Items</b></td>
                                    <td id="totalnetamount">0</td>
                                    <input type="hidden" name="elc_amount" id="elc_amount" value="0" >
                                    <input type="hidden" name="efo_ammount" id="efo_ammount" value="0" >
                                </tr>
                            </tbody>
                        </table>
                        <div style="clear:both" ></div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-large-1-1">
                            <button class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light" id="submitbtn" type="submit" >Submit</button>
                            <button class="md-btn md-btn-danger md-btn-wave-light waves-effect waves-button waves-light" type="button" >Reset</button>
                        </div>
                    </div>
                
                <?php echo form_close(); ?>
                
                                    </div>
        </div>
    </div>
</div>
<input type="hidden" name="">



<!-- datatables -->



<div class="uk-modal" id="modal_extendpackage">
    <div class="uk-modal-dialog">
        <div class="uk-modal-header">
            <h3 class="uk-modal-title">Upload Bulk Bom</h3>
        </div>
        <div class="uk-modal-body">
            <div class="uk-grid">
            <?php
            $attrib = ['data-toggle' => 'validator', 'role' => 'form', 'id' => 'stForm'];
         echo admin_form_open_multipart('bill_of_materials/uploadsheet');
         ?>
                 <div class="md-input-wrapper md-input-filled">
                        <input type="file" class="uk-input" style="width: 190%;" id="file" name="file" >
                    </div>
                    <div class="uk-modal-footer uk-text-right">
                        <button type="submit" class="md-btn md-btn-success md-btn-flat submitbtn">Submit</button>
                    </div>
                    <?php echo form_close(); ?>
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

<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js" integrity="sha256-lSjKY0/srUM9BE3dPm+c4fBo1dky2v27Gdjm2uoZaL0=" crossorigin="anonymous"></script>



<script>
    $(document).ready(function(){
        $('.select2').select2();
        $('#product_select').select2({
            ajax: {
                url: '<?php echo base_url('admin/general/products'); ?>',
                dataType: 'json',
                data: function (params) {
                    var queryParameters = {
                        term: params.term,
                        group: $('#grouptxt').val()
                    }
                    return queryParameters;
                }
            },
            formatResult: function (data, term) {
                return data;
            },
        });
        $("#searchproduct").autocomplete({
            source: function (request, response) {
                var supplier = $('#supplier').val();
                $.ajax({
                    type: 'get',
                    url: '<?php echo base_url('admin/general/searching_products'); ?>',
                    dataType: "json",
                    data: {
                        term: request.term,
                        supplier_id:supplier
                    },
                    success: function (data) {
                        $(this).removeClass('ui-autocomplete-loading');
                        response(data);
                    }
                });
            },
            minLength: 1,
            autoFocus: false,
            delay: 250,
            response: function (event, ui) {
                if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                    $(this).removeClass('ui-autocomplete-loading');
                    $(this).val('');
                }
                else if (ui.content.length == 1 && ui.content[0].id != 0) {
                    ui.item = ui.content[0];
                    $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                    $(this).autocomplete('close');
                    $(this).removeClass('ui-autocomplete-loading');
                }
                else if (ui.content.length == 1 && ui.content[0].id == 0) {
                    $(this).removeClass('ui-autocomplete-loading');
                    $(this).val('');
                }
            },
            select: function (event, ui) {
                event.preventDefault();
                $.ajax({
                    type: 'get',
                    url: '<?php echo base_url('admin/general/select_products2'); ?>',
                    data: {id: ui.item.item_id},
                    success: function (data) {
                        var obj = jQuery.parseJSON(data);
                        if(obj.codestatus){

                            var items = localStorage.getItem('bom_items');
                            if(items == null){
                                items = [obj.products];
                                localStorage.setItem('bom_items',JSON.stringify(items));
                            }
                            else{
                                var getitems = JSON.parse(localStorage.getItem('bom_items'));
                                getitems.push(obj.products);
                                localStorage.setItem('bom_items', JSON.stringify(getitems));
                            }
                            $("#dt_tableExport").DataTable().destroy();
                            loaditems();

                            $('#searchproduct').val('');
                        }
                    }
                });
            }
        });
        function loaditems(){
            var getitems = JSON.parse(localStorage.getItem('bom_items'));
            var html = "";
            var totalnetamount = 0;
            var totalptax = 0;
            var totalitems = 0;
            $("#supplier").prop("disabled", false);
            $.each(getitems, function(index) {
                var item = this;
                total = (parseFloat(item.cost)+parseFloat(item.fed_tax)+parseFloat(item.product_tax))*parseFloat(item.quantity);
                total = parseFloat(total).toFixed(4);
                var total_tax = parseFloat(item.product_tax)*parseFloat(item.quantity);
                total_tax = parseFloat(total_tax).toFixed(4);


                totalitems += parseFloat(item.quantity);
                totalptax += parseFloat(total_tax);
                totalnetamount += parseFloat(total);

                html += "<tr>";
                    html += "<td>"+(index+1);
                    html += "<input type='hidden' name='product_id[]' value='"+item.id+"' >";
                    html += "</td>";
                    html += "<td>"+item.name+"</td>";
                    html += "<td>"+item.cost+"</td>";
                    html += "<td><input type='number' class='itemqty' name='qty[]' data-index='"+index+"' value='"+item.quantity+"'></td>";
                    html += "<td>"+total+"</td>";
                    html += "<td>";
                        html += "<a class='md-btn md-btn-danger md-btn-wave-light waves-effect waves-button waves-light md-btn-mini itemremove' data-index='"+index+"' >Remove</a>";
                    html += "</td>";
                html += "</tr>";
                $("#supplier").prop("disabled", true);
            });
            $('#totalnetamount').html(totalnetamount.toFixed(4));
            $('#totalitems').html(totalitems);
            $('#dt_tableExport tbody').html(html);
            $('#dt_tableExport').DataTable({
                fixedColumns:   {left: 0,right: 2},
                scrollX: true,
                searching:false,
                paging :false

            });
            loadTotal();
        }
        loaditems();
        $(document).on('change','.itemqty',function(){
            var qty = $(this).val();
            if(qty == ""){
                $(this).val(0);
                qty = 0;
            }
            var getitems = JSON.parse(localStorage.getItem('bom_items'));
            var index = $(this).data('index');
            if (qty.indexOf('*') != -1) {
                qty = qty.replace("*", "");
                qty = qty*getitems[index].carton_size;
            }
            getitems[index].quantity = qty;
            localStorage.setItem('bom_items', JSON.stringify(getitems));
            $("#dt_tableExport").DataTable().destroy();
            loaditems();
        });
        $(document).on('click','.itemremove',function(){
            var index = parseInt($(this).data('index'));
            var getitems = JSON.parse(localStorage.getItem('bom_items'));
            getitems.splice(index,1)
            localStorage.setItem('bom_items', JSON.stringify(getitems));
            $("#dt_tableExport").DataTable().destroy();
            loaditems();
        });
        $('#submitFrom').submit(function(e){
            e.preventDefault();
            $('#submitbtn').prop('disabled', true);
            $.ajax({
                url: '<?php echo base_url('admin/bill_of_materials/submit'); ?>',
                type: 'POST',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    var obj = jQuery.parseJSON(data);
                    if(obj.status){
                        toastr.success(obj.message);
                        $('#submitFrom')[0].reset();
                        localStorage.removeItem("bom_items");
                        window.location.href = "<?php echo base_url('admin/bill_of_materials'); ?>";
                    }
                    else{
                        toastr.error(obj.message);
                    }
                    $('#submitbtn').prop('disabled', false);
                }
            });
        });
        function loadTotal(){
            var tma = $('#totalnetamount').html();
            var elc = $('#elc_amount').val();
            var efo = $('#efo_ammount').val();
            tma = parseFloat(tma);
            elc = parseFloat(elc);
            efo = parseFloat(efo);
            $('#totalcostamount').html(tma+elc+efo);
        }

        $('#elc_amount').change(function(){
            loadTotal();
        });
        $('#efo_ammount').change(function(){
            loadTotal();
        });
    });

    $(document).ready(function () {
            $('#upload-link').click(function (e) {
                e.preventDefault(); // Prevent the default link behavior
      
                UIkit.modal('#modal_extendpackage').show(); // Open the full body modal
            });
        });

</script>

