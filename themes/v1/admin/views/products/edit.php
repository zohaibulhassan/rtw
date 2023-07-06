<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-toolbar">
                <h3 class="md-card-toolbar-heading-text">Edit Product </h3>
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
                                <input type="hidden" name="product_id" value="<?php echo $product->id ?>" >
                                <input type="text" name="name" class="md-input md-input-success label-fixed"  value="<?php echo $product->name ?>" >
                            </div>
                        </div>
                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Product Group <span class="red" >*</span></label>
                                <select name="group" class="uk-width-1-1 select2">
                                    <option value="">Select Group</option>
                                    <?php
                                        foreach($groups as $row){
                                            echo '<option value="'.$row->id.'" ';
                                            if($row->id == $product->group_id){
                                                echo 'selected';
                                            }
                                            echo ' >'.$row->text.'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Barcode <span class="red" >*</span></label>
                                <input type="text" name="barcode" class="md-input md-input-success label-fixed"  value="<?php echo $product->code ?>" >
                            </div>
                        </div>
                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Category <span class="red" >*</span></label>
                                <select name="category" class="uk-width-1-1 select2" >
                                    <option value="">Select Category</option>
                                    <?php
                                        foreach($categories as $row){
                                            echo '<option value="'.$row->id.'" ';
                                            if($row->id == $product->category_id){
                                                echo 'selected';
                                            }
                                            echo ' >'.$row->text.'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Sub Category</label>
                                <select name="subcategory" class="uk-width-1-1 select2">
                                    <option value="0">Select Sub Category</option>
                                    <?php
                                        foreach($subcategories as $row){
                                            echo '<option value="'.$row->id.'" ';
                                            if($row->id == $product->subcategory_id){
                                                echo 'selected';
                                            }
                                            echo ' >'.$row->text.'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Product Unit <span class="red" >*</span></label>
                                <select name="unit" class="uk-width-1-1 select2" >
                                    <option value="">Select Unit</option>
                                    <?php
                                        foreach($units as $row){
                                            echo '<option value="'.$row->id.'" ';
                                            if($row->id == $product->unit){
                                                echo 'selected';
                                            }
                                            echo ' >'.$row->text.'</option>';
                                        }
                                    ?>
    
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Supplier 1 <span class="red" >*</span></label>
                                <select name="supplier1" class="uk-width-1-1 select2" >
                                    <option value="">Select Supplier</option>
                                    <?php
                                        foreach($suppliers as $row){
                                            echo '<option value="'.$row->id.'" ';
                                            if($row->id == $product->supplier1){
                                                echo 'selected';
                                            }
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
                                            if($row->id == $product->supplier2){
                                                echo 'selected';
                                            }
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
                                            if($row->id == $product->supplier3){
                                                echo 'selected';
                                            }
                                            echo ' >'.$row->text.'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Cost <span class="red" >*</span></label>
                                <input type="number" name="cost" class="md-input md-input-success label-fixed"  value="<?php echo $product->cost ?>" >
                            </div>
                        </div>
                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <label>MRP </label>
                                <input type="number" name="mrp" class="md-input md-input-success label-fixed"  value="<?php echo $product->mrp ?>" >
                            </div>
                        </div>
                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Alert Quantity <span class="red" >*</span></label>
                                <input type="number" name="alertqty" class="md-input md-input-success label-fixed"   value="<?php echo $product->alert_quantity ?>" >
                            </div>
                        </div>
                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Status <span class="red" >*</span></label>
                                <select name="status" class="uk-width-1-1 select2"  >
                                    <option value="1" <?php if($product->status == 1 ){ echo 'selected'; } ?> >Active</option>
                                    <option value="0" <?php if($product->status == 0 ){ echo 'selected'; } ?> >Deactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-large-1-1">
                            <label>Product Detail</label>
                        </div>
                        <div class="uk-width-large-1-1">
                            <textarea name="detail" class="md-input no_autosize" id="editor" style="min-height:250px" ><?php echo $product->product_details ?></textarea>
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
                        <input type="hidden" name="packsize" value="1">
                        <input type="hidden" name="cartonsize" value="1">
                        <input type="hidden" name="companycode" value="">
                        <input type="hidden" name="hsncode" value="">
                        <input type="hidden" name="consignment" value="0">
                        <input type="hidden" name="dropship" value="0">
                        <input type="hidden" name="crossdock" value="0">

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
    });
    $('#productFrom').submit(function(e){
        e.preventDefault();
        $('#submitbtn').prop('disabled', true);
        $.ajax({
            url: '<?php echo base_url('admin/products/update_submit'); ?>',
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

