<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-toolbar">
                <h3 class="md-card-toolbar-heading-text">New Product </h3>
            </div>
            <div class="md-card-content" >
                <?php
                    $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'productFrom');
                    echo admin_form_open_multipart("#", $attrib);
                ?>
                    <div class="uk-grid">
                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Name <span class="red" >*</span></label>
                                <input type="text" name="name" class="md-input md-input-success label-fixed" required>
                            </div>
                        </div>
                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Product Group</label>
                                <select name="group" class="uk-width-1-1 select2">
                                    <option value="">Select Group</option>
                                    <?php
                                        foreach($groups as $row){
                                            echo '<option value="'.$row->id.'" ';
                                            echo ' >'.$row->text.'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Barcode <span class="red" >*</span></label>
                                <input type="text" name="barcode" class="md-input md-input-success label-fixed" required>
                            </div>
                        </div>
                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Category <span class="red" >*</span></label>
                                <select name="category" class="uk-width-1-1 select2" required id="category_select">
                                    <option value="">Select Category</option>
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Sub-Category </label>
                                <select name="subcategory" class="uk-width-1-1 select2" id="subcategory_select" >
                                    <option value="">Select Sub Category</option>
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Product Unit <span class="red" >*</span></label>
                                <select name="unit" class="uk-width-1-1 select2" required>
                                    <option value="">Select Unit</option>
                                    <?php
                                        foreach($units as $row){
                                            echo '<option value="'.$row->id.'" ';
                                            echo ' >'.$row->text.'</option>';
                                        }
                                    ?>
    
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Supplier 1 <span class="red" >*</span></label>
                                <select name="supplier1" class="uk-width-1-1 select2" required>
                                    <option value="">Select Supplier</option>
                                    <?php
                                        foreach($suppliers as $row){
                                            echo '<option value="'.$row->id.'" ';
                                            echo ' >'.$row->text.'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Supplier 2</label>
                                <select name="supplier2" class="uk-width-1-1 select2">
                                    <option value="">Select Supplier</option>
                                    <?php
                                        foreach($suppliers as $row){
                                            echo '<option value="'.$row->id.'" ';
                                            echo ' >'.$row->text.'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Supplier 3</label>
                                <select name="supplier3" class="uk-width-1-1 select2">
                                    <option value="">Select Supplier</option>
                                    <?php
                                        foreach($suppliers as $row){
                                            echo '<option value="'.$row->id.'" ';
                                            echo ' >'.$row->text.'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Cost <span class="red" >*</span></label>
                                <input type="number" name="cost" class="md-input md-input-success label-fixed" required>
                            </div>
                        </div>
                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <label>MRP </label>
                                <input type="number" name="mrp" class="md-input md-input-success label-fixed" value="0" >
                            </div>
                        </div>
                        <input type="hidden" name="texmethod" value="0">
                        <input type="hidden" name="producttax" value="0">
                        <input type="hidden" name="fed_tax" value="0">
                        <input type="hidden" name="ratax_sale" value="0">
                        <input type="hidden" name="nratax_sale" value="0">
                        <input type="hidden" name="ratax_purchase" value="0">
                        <input type="hidden" name="si_dicount" value="0">
                        <input type="hidden" name="t_discount" value="0">
                        <input type="hidden" name="c_discount" value="0">
                        <input type="hidden" name="hold_qty" value="0">
                        <input type="hidden" name="sold_days" value="0">
                        <input type="hidden" name="se_expiry" value="0">
                        <input type="hidden" name="brnad" value="0">
                        <input type="hidden" name="weight" value="0">
                        <input type="hidden" name="supplier4" value="0">
                        <input type="hidden" name="supplier5" value="0">
                        <input type="hidden" name="consignment" value="0">
                        <input type="hidden" name="dropship" value="0">
                        <input type="hidden" name="crossdock" value="0">
                        <input type="hidden" name="packsize" value="1">
                        <input type="hidden" name="cartonsize" value="1">
                        <input type="hidden" name="companycode" value="">
                        <input type="hidden" name="hsncode" value="">
                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Alert Qty <span class="red" >*</span></label>
                                <input type="number" name="alertqty" class="md-input md-input-success label-fixed" required value="0" >
                            </div>
                        </div>
                        <div class="uk-width-large-1-1" style="margin-top:20px">
                            <label>Product Detail</label>
                        </div>
                        <div class="uk-width-large-1-1">
                            <textarea name="detail" class="md-input no_autosize" id="editor" style="min-height:250px" ></textarea>
                        </div>
                        <div class="uk-width-large-1-1" style="padding-top: 20px;">
                            <button type="submit" class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light" id="submitbtn" >Submit</button>
                            <a href="<?php echo base_url('admin/products'); ?>" class="md-btn md-btn-danger md-btn-wave-light waves-effect waves-button waves-light" >Cancel</a>
                        </div>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<!-- CK Editor 5 -->
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/ckeditor5/ckeditor.js"></script>
<script>
    ClassicEditor
    .create( document.querySelector( '#editor' ),{
        toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ],
        shouldNotGroupWhenFull: true
    })
    .then( editor => {
        window.editor = editor;
    })
    .catch( error => {
        console.error( error );
    });

</script>

<script>
    $(document).ready(function(){
        $('.select2').select2();
        var category = 1;
        $('#category_select').select2({
            ajax: {
                url: '<?php echo base_url("admin/general/categories"); ?>',
                dataType: 'json',
            },
            formatResult: function (data, term) {
                return data;
            },
        });
        $('#category_select').change(function(){
            category = $(this).val();
            console.log(category);
        });
        $('#subcategory_select').select2({
            ajax: {
                url: '<?php echo base_url("admin/general/subcategories"); ?>',
                dataType: 'json',
                data: function (params) {
                    console.log();
                    var queryParameters = {
                        term: params.term,
                        category: $('#category_select').val()
                    }
                    return queryParameters;
                }
            },
            formatResult: function (data, term) {
                console.log($('#category_select').val());
                return data;
            },
        });

    });

    $('#productFrom').submit(function(e){
        e.preventDefault();
        $('#submitbtn').prop('disabled', true);
        $.ajax({
            url: '<?php echo base_url('admin/products/insert_submit'); ?>',
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
                    $('#productFrom')[0].reset();
                    window.location.href = "<?php echo base_url('admin/products'); ?>";
                }
                else{
                    toastr.error(obj.message);
                }
                $('#submitbtn').prop('disabled', false);
            }
        });
    });
</script>

