<style>
    #page_content_inner {
        padding-bottom:0px !important;
    }
    .uk-input-group-addon {
        padding-left: 10px;
        padding-right: 0;
    }
    .uk-input-group-addon a {
        color: black;
    }
    .uk-grid-margin{
        margin: 0;
    }
    .itemTable{
        margin-top: 15px;
    }
    .itemTable th{
    }
    .itemTable tfoot th{
    }
    .itemTable tfoot td{
        text-align: right;
    }
    .posactions .uk-width-medium-1-3 {
        /* padding: 0; */
    }
    .padding5 {
        padding-left: 5px;
    }
    .posactions .md-btn {
        width: 100%;
        margin: 0 auto 6px;
    }
    .itemTable tbody{
        min-height: 220px;
        /* display: block; */
    }
    .itemlist {
        max-height: 550px;
        overflow-y: auto;
        padding-right:20px;

    }
    .itemlist .md-card .md-card-head {
        height:auto !important;
    }
    .itemlist .heading_c {
        text-align: center;
    }
    .itemlist .uk-grid-margin {
        margin-top:15px;
    }
    .itemlist .productdiv {
        width: 23%;
        margin: 5px;
        float: left;
        cursor: pointer;
    }
    .itemlist2 {
        width:100%;

    }
    .itemlist2 .productdiv {
        width: 45%;
        margin: 5px;
        float: left;
        cursor: pointer;
    }
    .select2 {
        width: 100% !important;
    }
    .productdiv .md-card {
        box-shadow: none;
    }
    .productdiv .md-card .md-card-content {
        padding: 0;
    }
    .productdiv .md-card .md-card-content button{
        width: 100%;
        background: #77c74a;
        border: 1px solid;
        height:100px;
        color: white;
        font-size: 14px;
        cursor: pointer;
        border-radius: 4px;
    }
    .payment_summary {
        width: 100%;
        margin-top: 31px;
    }
    .payment_summary th {
        background: #b5003d;
        color:white;
        border: 1px solid;
    }
    .payment_summary .lefttext{
        text-align:left;
    }
    .payment_summary .righttext{
        text-align:right;
    }
</style>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<div id="page_content_inner">
    <div class="uk-grid" data-uk-grid-margin data-uk-grid-match="{target:'.md-card'}">
        <div class="uk-width-medium-4-10">
            <div class="md-card">
                <div class="md-card-content">
                    <?php
                        $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'posform');
                        echo admin_form_open_multipart("#", $attrib);
                            
                    ?>
                        <div class="uk-grid" data-uk-grid-margin="">
                            <div class="uk-width-medium-1-1 uk-row-first">
                                <div class="uk-input-group">
                                    <div class="md-input-wrapper" style="margin-top: 0px;">
                                        <select id="customer_select" name="customer" class="md-input">
                                            <option value="" >Select...</option>
                                        </select>
    
                                        <span class="md-input-bar "></span>
                                    </div>
                                    <span class="uk-input-group-addon">
                                        <a href="#" id="addcustomer" ><i class="fa-solid fa-user-plus"></i></a>
                                    </span>
                                </div>
                            </div>
                            <div class="uk-width-medium-1-1 uk-row-first">
                                <div class="md-input-wrapper md-input-filled">
                                    <input type="text" name="product" id="searchproduct" class="md-input md-input-success label-fixed" placeholder="Enter product name or barcode">
                                </div>
                            </div>
                            <div class="uk-width-medium-1-1 uk-row-first">
                                <input type="hidden" name="discount" value="0" id="discount">
                                <input type="hidden" name="charges" value="0" id="charges">
                                <table class="uk-table itemTable">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Qty</th>
                                            <th>Total</th>
                                            <th><i class="fa-sharp fa-solid fa-trash"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Total Items</th>
                                            <td id="totalitem" >0.00</td>
                                            <th>Total</th>
                                            <td colspan="3" id="totalamount" >0.00</td>
                                        </tr>
                                        <tr>
                                            <th>Discount <i class="fa-solid fa-pen-to-square" id="openDiscountModel" style="cursor: pointer;font-size: 12px;color: #b5003d;" ></i></th>
                                            <td id="totaldiscount" >0.00</td>
                                            <th>Charges <i class="fa-solid fa-pen-to-square" id="openChargesModel" style="cursor: pointer;font-size: 12px;color: #b5003d;" ></i></th>
                                            <td colspan="3" id="totalcharges" >0.00</td>
                                        </tr>
                                        <tr>
                                            <th colspan="2" >Total Payable</th>
                                            <td colspan="3" id="totalpayable"  >0.00</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="uk-width-medium-1-1 uk-row-first posactions" style="margin-top: 15px;">
                                <div class="uk-grid" data-uk-grid-margin="">
                                    <div class="uk-width-medium-1-3 uk-row-first">
                                        <button class="md-btn md-btn-warning md-btn-wave-light waves-effect waves-button waves-light" type="button" id="holdButton" name="holdbill" value="holdbill" >Hold</button>
                                    </div>
                                    <div class="uk-width-medium-1-3 uk-row-first padding5">
                                        <button class="md-btn md-btn-information md-btn-wave-light waves-effect waves-button waves-light" type="button" id="printOrderButton" name="printorder" value="printorder" >Print Order</button>
                                    </div>
                                    <div class="uk-width-medium-1-3 uk-row-first padding5">
                                        <button class="md-btn md-btn-danger md-btn-wave-light waves-effect waves-button waves-light" type="button" id="cancelBtn" >Cancel</button>
                                    </div>
                                    <div class="uk-width-medium-1- uk-row-first">
                                        <button class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light" type="button" id="paymentbtn">Payment</button>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="payamountval" id="payamountval" >
                            <input type="hidden" name="paymethodval" id="paymethodval" >
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
        <div class="uk-width-medium-6-10">
            <div class="md-card">
                <div class="md-card-content">
                    <div class="itemlist" id="itemlist">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="sidebar_posmenu" class="sidebaroption">
    <div class="scroll-wrapper scrollbar-inner" style="position: relative;">
        <div class="scrollbar-inner scroll-content" style="height: auto; margin-bottom: 0px; margin-right: 0px; max-height: 335px;">
            <div class="sidebar_secondary_wrapper uk-margin-remove">
                <ul class="md-list md-list-addon list-posmenu" id="brands">
                    <li data-user="Helga Stiedemann">
                        <div class="md-list-addon-element">
                            <img class="md-user-image md-list-addon-avatar" src="<?php echo base_url('/themes/v1/assets/img/avatars/avatar_02_tn.png') ?>" alt="" />
                        </div>
                        <div class="md-list-content">
                            <span class="md-list-heading">Olpers</span>
                            <span class="uk-text-small uk-text-muted uk-text-truncate">No Of Products: 0</span>
                        </div>
                    </li>
                    <li data-user="Helga Stiedemann">
                        <div class="md-list-addon-element">
                            <img class="md-user-image md-list-addon-avatar" src="<?php echo base_url('/themes/v1/assets/img/avatars/avatar_02_tn.png') ?>" alt="" />
                        </div>
                        <div class="md-list-content">
                            <span class="md-list-heading">EBM</span>
                            <span class="uk-text-small uk-text-muted uk-text-truncate">No Of Products: 0</span>
                        </div>
                    </li>
                </ul>
                <ul class="md-list md-list-addon list-posmenu" id="categories">
                    <?php
                        foreach($categories as $category){
                        ?>
                            <li data-user="Helga Stiedemann" style="cursor: pointer;" class="fatchproduct" data-category="<?php echo $category->id ?>" >
                                <div class="md-list-addon-element">
                                    <img class="md-user-image md-list-addon-avatar" src="<?php echo base_url('/themes/v1/assets/img/no_image.png') ?>" alt="" />
                                </div>
                                <div class="md-list-content">
                                    <span class="md-list-heading"><?php echo $category->name ?></span>
                                    <span class="uk-text-small uk-text-muted uk-text-truncate">No Of Products: <?php echo $category->no_products ?></span>
                                </div>
                            </li>
                        <?php
                        }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="uk-modal" id="modal_addcustomer">
    <?php
        $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'addCustomerFrom');
        echo admin_form_open_multipart("#", $attrib);
    ?>
        <div class="uk-modal-dialog">
            <div class="uk-modal-header">
                <h3 class="uk-modal-title">Add New Customer</h3>
            </div>
            <div class="uk-modal-body">
                <div class="uk-grid">
                    <div class="uk-width-large-1-1">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Name <span class="red" >*</span></label>
                            <input type="hidden" name="formtype" value="pos" >
                            <input type="hidden" name="selling" value="mrp" >
                            <input type="hidden" name="company" value="POS" >
                            <input type="text" name="name" class="md-input md-input-success label-fixed" required>
                        </div>
                    </div>
                    <div class="uk-width-large-1-2">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Phone</label>
                            <input type="text" name="phone" class="md-input md-input-success label-fixed">
                        </div>
                    </div>
                    <div class="uk-width-large-1-2">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Email</label>
                            <input type="text" name="email" class="md-input md-input-success label-fixed">
                        </div>
                    </div>
                    <div class="uk-width-large-1-2">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Postal Code </label>
                            <input type="text" name="postal" class="md-input md-input-success label-fixed">
                        </div>
                    </div>
                    <div class="uk-width-large-1-2">
                        <div class="md-input-wrapper md-input-filled">
                            <label>City </label>
                            <input type="text" name="city" class="md-input md-input-success label-fixed">
                        </div>
                    </div>
                    <div class="uk-width-large-1-2">
                        <div class="md-input-wrapper md-input-filled">
                            <label>State </label>
                            <input type="text" name="state" class="md-input md-input-success label-fixed">
                        </div>
                    </div>
                    <div class="uk-width-large-1-2">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Country </label>
                            <input type="text" name="country" class="md-input md-input-success label-fixed">
                        </div>
                    </div>
                    <div class="uk-width-large-1-1">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Address </label>
                            <input type="text" name="address" class="md-input md-input-success label-fixed">
                        </div>
                    </div>
                </div>
            </div>
            <div class="uk-modal-footer uk-text-right">
                <button type="submit" class="md-btn md-btn-success md-btn-flat" id="csubmitbtn" >Submit</button>
                <button type="button" class="md-btn md-btn-flat uk-modal-close" >Close</button>
            </div>
        </div>
    <?php echo form_close(); ?>
</div>
<div class="uk-modal" id="modal_discount">
    <div class="uk-modal-dialog">
        <div class="uk-modal-header">
            <h3 class="uk-modal-title">Apply Discount</h3>
        </div>
        <div class="uk-modal-body">
            <div class="uk-grid">
                <div class="uk-width-large-1-1">
                    <div class="md-input-wrapper md-input-filled">
                        <label>Bulk Discounts</label>
                        <select name="bukdiscount" class="uk-width-1-1 select2" id="bukdiscount"  >
                            <option value="0">Custom Discount</option>
                            <?php
                                foreach($bulkdiscounts as $bulkdiscount){
                                    echo '<option value="'.$bulkdiscount->percentage.'%" >'.$bulkdiscount->discount_name.' ('.$bulkdiscount->discount_code.')</option>';
                                }
                            ?>

                        </select>
                    </div>
                </div>
                <div class="uk-width-large-1-1">
                    <div class="md-input-wrapper md-input-filled">
                        <label>Discount <span class="red" >*</span></label>
                        <input type="text" name="name" id="discountTxt" class="md-input md-input-success label-fixed" value="0" required >
                    </div>
                </div>
            </div>
        </div>
        <div class="uk-modal-footer uk-text-right">
            <button type="button" class="md-btn md-btn-success md-btn-flat discountBtn" >Submit</button>
            <button type="button" class="md-btn md-btn-flat uk-modal-close" >Close</button>
        </div>
    </div>
</div>
<div class="uk-modal" id="modal_payment">
    <div class="uk-modal-dialog">
        <div class="uk-modal-header">
            <h3 class="uk-modal-title">Payment</h3>
        </div>
        <div class="uk-modal-body">
            <div class="uk-grid">
                <div class="uk-width-large-1-2">
                    <div class="md-input-wrapper md-input-filled">
                        <label>Amount</label>
                        <input type="text" name="payamount" id="payamount" value="0" class="md-input md-input-success label-fixed" >
                    </div>
                </div>
                <div class="uk-width-large-1-2">
                    <div class="md-input-wrapper md-input-filled">
                        <label>Payment Method</label>
                        <select name="payment_method" class="uk-width-1-1 select2" id="payment_method"  >
                            <option value="cash">Cash</option>
                            <option value="CC">Credit Card</option>
                            <option value="cheque">Cheque</option>
                            <option value="gift_card">Gift Card</option>
                            <option value="stripe">Stripe</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="uk-width-large-1-1">
                    <table class="uk-table payment_summary" >
                        <tr>
                            <th class="lefttext" >Total Items</th>
                            <th class="righttext" id="tps_totalitem" >3</th>
                            <th class="lefttext" >Total Payable</th>
                            <th class="righttext" id="tps_totalamount" >0</th>
                        </tr>
                        <tr>
                            <th class="lefttext" >Total Paying</th>
                            <th class="righttext"  id="tps_payableabount" >0</th>
                            <th class="lefttext">Balance</th>
                            <th class="righttext" id="tps_balance" >0</th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="uk-modal-footer uk-text-right">
            <button type="button" class="md-btn md-btn-success md-btn-flat" id="submitBtn" >Submit</button>
            <button type="button" class="md-btn md-btn-flat uk-modal-close" >Close</button>
        </div>
    </div>
</div>
<div class="uk-modal" id="modal_similarproduct">
    <div class="uk-modal-dialog">
        <div class="uk-modal-header">
            <h3 class="uk-modal-title">This Product Out of Stock. Simpilar Products</h3>
        </div>
        <div class="uk-modal-body">
            <div class="uk-grid">
                <div class="itemlist2">
                    <!-- <div class="productdiv" data-barcode="P00001"><div class="md-card md-card-hover-img"><div class="md-card-content"><button>GLUCOBAY 100MG TAB 30 S</button></div></div></div> -->
                </div>
            </div>
        </div>
        <div class="uk-modal-footer uk-text-right">
            <button type="button" class="md-btn md-btn-flat uk-modal-close" >Close</button>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js" integrity="sha256-lSjKY0/srUM9BE3dPm+c4fBo1dky2v27Gdjm2uoZaL0=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function(){
        $('.select2').select2();
        $('#addcustomer').click(function(){
            UIkit.modal('#modal_addcustomer').show();
        });
        $('#paymentbtn').click(function(){
            UIkit.modal('#modal_payment').show();
        });
        $('#openDiscountModel').click(function(){
            UIkit.modal('#modal_discount').show();
        });
        $('#openChargesModel').click(function(){
            UIkit.modal('#modal_charges').show();
        });
        $('.chargesBtn').click(function(){
            $('#charges').val($('#charegeTxt').val());
            loaditems();
            UIkit.modal('#modal_charges').hide();
        });
        $('.discountBtn').click(function(){
            $('#discount').val($('#discountTxt').val());
            loaditems();
            UIkit.modal('#modal_discount').hide();
        });
        $('#bukdiscount').change(function(){
            $('#discountTxt').val($(this).val());
        });
        $('#customer_select').select2({
            ajax: {
                url: '<?php echo base_url("admin/general/customers"); ?>',
                dataType: 'json',
            },
            formatResult: function (data, term) {
                return data;
            },
        });
        $('#addCustomerFrom').submit(function(e){
            e.preventDefault();
            $('#csubmitbtn').prop('disabled', true);
            $.ajax({
                url: '<?php echo base_url('admin/customers/insert'); ?>',
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
                        UIkit.modal('#modal_addcustomer').hide();

                    }
                    else{
                        toastr.error(obj.message);
                        $('#csubmitbtn').prop('disabled', false);
                    }
                }
            });
        });
        $("#searchproduct").autocomplete({
            source: function (request, response) {
                $.ajax({
                    type: 'get',
                    url: '<?php echo base_url('admin/general/searching_products_pos'); ?>',
                    dataType: "json",
                    data: {
                        term: request.term
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
                select_product(ui.item.item_id);
            }
        });
        $(document).on('click','.productdiv',function(){
            var code = $(this).data('pid');
            select_product(code);

        });
        function select_product(id){
            console.log(id);
            var warehouse_id = <?php echo $warehouse_id; ?>;
            $.ajax({
                type: 'get',
                url: '<?php echo base_url('admin/general/select_products'); ?>',
                data: {id: id,warehouse_id:warehouse_id},
                success: function (data) {
                    var obj = jQuery.parseJSON(data);
                    if(obj.codestatus){
                        var items = localStorage.getItem('pos_items');
                        if(items == null){
                            items = [obj.products];
                            console.log(items);
                            var balanceqty = parseInt(obj.products.balance_qty);
                            console.log(balanceqty);
                            if(balanceqty == 0){
                                similarProduct(items[0].formulas);
                                UIkit.modal('#modal_similarproduct').show();
                            }
                            else{
                                localStorage.setItem('pos_items',JSON.stringify(items));
                            }
                        }
                        else{
                            var getitems = JSON.parse(localStorage.getItem('pos_items'));
                            getitems.push(obj.products);

                            console.log(getitems);
                            var balanceqty = parseInt(obj.products.balance_qty);
                            console.log(balanceqty);
                            if(balanceqty == 0){
                                similarProduct(getitems[0].formulas);
                                UIkit.modal('#modal_similarproduct').show();
                            }
                            else{
                                localStorage.setItem('pos_items', JSON.stringify(getitems));
                            }
                        }
                        // $("#dt_tableExport").DataTable().destroy();
                        loaditems();

                        $('#searchproduct').val('');
                    }
                }
            });



        }
        function loaditems(){
            var getitems = JSON.parse(localStorage.getItem('pos_items'));
            var html = "";
            var totalnetamount = 0;
            var totalptax = 0;
            var totalitems = 0;
            $.each(getitems, function(index) {
                var item = this;
                // total = (parseFloat(item.cost)+parseFloat(item.fed_tax)+parseFloat(item.product_tax))*parseFloat(item.quantity);
                total = parseFloat(item.mrp)*parseFloat(item.quantity);
                total = parseFloat(total).toFixed(4);
                var total_tax = parseFloat(item.product_tax)*parseFloat(item.quantity);
                total_tax = parseFloat(total_tax).toFixed(4);
                totalitems += parseFloat(item.quantity);
                totalptax += parseFloat(total_tax);
                totalnetamount += parseFloat(total);
                html += "<tr>";
                    html += "<td>"+item.name;
                    html += "<input type='hidden' name='product_id[]' value='"+item.id+"' >";
                    html += "</td>";
                    html += "<td>"+item.mrp+"</td>";
                    html += "<td><input type='text' class='itemqty' name='qty[]' data-index='"+index+"' value='"+item.quantity+"'></td>";
                    html += "<td>"+total+"</td>";
                    html += "<td>";
                        html += "<i class='fa-sharp fa-solid fa-trash itemremove' data-index='"+index+"' style='color:red;cursor: pointer;font-size: 12px;'></i>";
                    html += "</td>";
                html += "</tr>";
            });
            $('.itemTable tbody').html(html);
            var discount  = $('#discount').val();
            if (discount.indexOf('%') != -1) {
                discount = discount.replace("%", "");
                discount  = parseFloat(discount);
                discount = totalnetamount/100*discount;
            }
            else{
                discount  = parseFloat(discount);
            }

            var charges  = parseFloat($('#charges').val());
            $('#totalamount').html(totalnetamount.toFixed(4));
            $('#totaldiscount').html(discount);
            $('#totalcharges').html(charges);
            $('#totalitem').html(totalitems);
            var payable = totalnetamount+charges-discount;
            $('#totalpayable').html(payable.toFixed(4));

            $('#tps_totalitem').html(totalitems);
            $('#payamount').val(payable.toFixed(4));
            $('#tps_totalamount').html(payable.toFixed(4));
            $('#tps_payableabount').html(payable.toFixed(4));



        }
        loaditems();
        $(document).on('change','.itemqty',function(){
            var qty = $(this).val();
            if(qty == ""){
                $(this).val(0);
                qty = 0;
            }
            var getitems = JSON.parse(localStorage.getItem('pos_items'));
            var index = $(this).data('index');
            if (qty.indexOf('**') != -1) {
                qty = qty.replace("**", "");
                qty = qty*getitems[index].carton_size;
                qty = qty*getitems[index].carton_size;
            }
            else if (qty.indexOf('*') != -1) {
                qty = qty.replace("*", "");
                qty = qty*getitems[index].pack_size;
                qty = qty*getitems[index].pack_size;
            }
            getitems[index].quantity = qty;
            localStorage.setItem('pos_items', JSON.stringify(getitems));
            loaditems();
        });
        $(document).on('click','.itemremove',function(){
            var index = parseInt($(this).data('index'));
            var getitems = JSON.parse(localStorage.getItem('pos_items'));
            getitems.splice(index,1)
            localStorage.setItem('pos_items', JSON.stringify(getitems));
            loaditems();
        });
        var $sidebar_posmenu = $("#sidebar_posmenu"),
            $sidebar_posmenu_toggle = $(".sidebar_posmenu_toggle");

        altair_posmenu_sidebar = {
            init: function () {
                $sidebar_posmenu.length &&
                ($sidebar_posmenu_toggle.removeClass("sidebar_secondary_check"),
                $sidebar_posmenu_toggle.on("click", function (e) {
                    var typetoggle = $(this).data('type');
                    $('.list-posmenu').hide();
                    $('#'+typetoggle).show();

                    e.preventDefault(), $body.hasClass("sidebar_secondary_active") ? altair_posmenu_sidebar.hide_sidebar() : altair_posmenu_sidebar.show_sidebar();
                }),
                $document.on("click keydown", function (e) {
                    $body.hasClass("sidebar_secondary_persisten") ||
                        !$body.hasClass("sidebar_secondary_active") ||
                        (($(e.target).closest($sidebar_posmenu).length || $(e.target).closest($sidebar_posmenu_toggle).length) && 27 != e.which) ||
                        altair_posmenu_sidebar.hide_sidebar();
                }),
                $body.hasClass("sidebar_secondary_active") && altair_posmenu_sidebar.hide_sidebar(),
                altair_helpers.custom_scrollbar($sidebar_posmenu),
                altair_posmenu_sidebar.chat_sidebar());
            },
            hide_sidebar: function () {
                $body.removeClass("sidebar_secondary_active");
            },
            show_sidebar: function () {
                $body.addClass("sidebar_secondary_active");
            },
            chat_sidebar: function () {
                $sidebar_posmenu.find(".md-list.chat_users").length &&
                ($(".md-list.chat_users")
                    .children("li")
                    .on("click", function () {
                        $(".md-list.chat_users").velocity("transition.slideRightBigOut", {
                            duration: 280,
                            easing: easing_swiftOut,
                            complete: function () {
                                $sidebar_posmenu
                                    .find(".chat_box_wrapper")
                                    .addClass("chat_box_active")
                                    .velocity("transition.slideRightBigIn", {
                                        duration: 280,
                                        easing: easing_swiftOut,
                                        begin: function () {
                                            $sidebar_posmenu.addClass("chat_sidebar");
                                        },
                                    });
                            },
                        });
                    }),
                    $sidebar_posmenu.find(".chat_sidebar_close").on("click", function () {
                        $sidebar_posmenu
                        .find(".chat_box_wrapper")
                        .removeClass("chat_box_active")
                        .velocity("transition.slideRightBigOut", {
                            duration: 280,
                            easing: easing_swiftOut,
                            complete: function () {
                                $sidebar_posmenu.removeClass("chat_sidebar"), $(".md-list.chat_users").velocity("transition.slideRightBigIn", { duration: 280, easing: easing_swiftOut });
                            },
                        });
                    }),
                    $sidebar_posmenu.find(".uk-tab").length &&
                    $sidebar_posmenu.find(".uk-tab").on("change.uk.tab", function (e, i, a) {
                        $(i).hasClass("chat_sidebar_tab") && $sidebar_posmenu.find(".chat_box_wrapper").hasClass("chat_box_active") ? $sidebar_posmenu.addClass("chat_sidebar") : $sidebar_posmenu.removeClass("chat_sidebar");
                    })
                );
            },
        };
        altair_posmenu_sidebar.init();
        function loadProduct(type="category",category=0,brand=0){
            $('#csubmitbtn').prop('disabled', true);
            $.ajax({
                url: '<?php echo base_url('admin/pos/productlist'); ?>',
                type: 'GET',
                data: {type:type,category:category,brand:brand},
                success: function(data) {
                    var obj = jQuery.parseJSON(data);
                    if(obj.status){
                        $('#itemlist').html(obj.html);
                        $('.sidebar_posmenu_toggle').click();

                    }
                }
            });
        }
        loadProduct();
        $('.fatchproduct').click(function(){
            var cate = $(this).data('category');            
            loadProduct("category",cate);
        });
        function similarProduct(formulas){
            $.ajax({
                url: '<?php echo base_url('admin/pos/similarformula'); ?>',
                type: 'GET',
                data: {formulas:formulas},
                success: function(data) {
                    var obj = jQuery.parseJSON(data);
                    if(obj.status){
                        $('.itemlist2').html(obj.html);
                        console.log(2);
                    }
                    else{
                        console.log(1);
                    }
                }
            });
        }
        $('#cancelBtn').click(function(){
            localStorage.removeItem("pos_items");
            // location.reload();
            loaditems();
        });
        $('#submitBtn').click(function(){
            $('#payamountval').val($('#payamount').val());
            $('#paymethodval').val($('#payment_method').val());
            let myform = document.getElementById("posform");
            let data = new FormData(myform);

            $.ajax({
                url: '<?php echo base_url('admin/pos/submit'); ?>',
                type: 'POST',
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    var obj = jQuery.parseJSON(data);
                    if(obj.status){
                        toastr.success(obj.message);
                        UIkit.modal('#modal_payment').hide();
                        $('#cancelBtn').click();

                    }
                    else{
                        toastr.error(obj.message);
                    }
                }
            });
        });
        $('#payamount').change(function(){
            var amountval = $(this).val();
            var tps_totalamount = $('#tps_totalamount').html();
            $('#tps_payableabount').html(amountval);
            $('#tps_balance').html(tps_totalamount-amountval);
        });
        $('#printOrderButton').click(function(){
            let myform = document.getElementById("posform");
            let data = new FormData(myform );
            $.ajax({
                url: '<?php echo base_url('admin/pos/print_bill'); ?>',
                type: 'POST',
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    var obj = jQuery.parseJSON(data);
                    if(obj.status){
                    }
                    else{
                        toastr.error(obj.message);
                    }
                    // $('#submitbtn').prop('disabled', false);
                }
            });
        });
        $('#holdButton').click(function(){
            let myform = document.getElementById("posform");
            let data = new FormData(myform );
            $.ajax({
                url: '<?php echo base_url('admin/pos/hold_bill'); ?>',
                type: 'POST',
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    var obj = jQuery.parseJSON(data);
                    if(obj.status){
                        toastr.success(obj.message);
                        $('#cancelBtn').click();

                    }
                    else{
                        toastr.error(obj.message);
                    }
                }
            });
        });





    });
</script>