<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-toolbar">
                <div class="md-card-toolbar-actions">
                    <ul class="uk-tab" data-uk-tab="{connect:'#card_tabs',animation:'slide-horizontal'}">
                        <li class="uk-active"><a href="#">Detail</a></li>
                        <!-- <li><a href="#">Images</a></li>
                        <li><a href="#">Sales</a></li>
                        <li><a href="#">Purchases</a></li>
                        <li><a href="#">Transfer</a></li>
                        <li><a href="#">Store Integration</a></li> -->
                    </ul>
                </div>
                <!-- <h3 class="md-card-toolbar-heading-text">
                    <?php 
                        echo $product->name; 
                        if($product->status == 1){
                            echo " <span class='uk-badge uk-badge-success'>Active</span>";
                        }
                        else{
                            echo "<span class='uk-badge uk-badge-danger'>Deactive</span>";
                        }
                    ?>
                </h3> -->
            </div>
            <div class="md-card-content">
                <ul id="card_tabs" class="uk-switcher uk-margin">
                    <li class="tablecellwidth">
                        <div class="uk-grid" data-uk-grid-margin="">
                            <div class="uk-width-medium-2-10 uk-row-first">
                                <span class="uk-display-block uk-margin-small-top uk-text-large"><b>Basic Detail</b></span>
                            </div>
                            <div class="uk-width-medium-8-10">
                                <table class="uk-table">
                                    <tbody>
                                        <tr>
                                            <td><b>Product ID</b></td>
                                            <td class="uk-text-right"><?php echo $product->id ?></td>
                                            <td><b>Product Name</b></td>
                                            <td class="uk-text-right"><?php echo $product->name ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Group ID</b></td>
                                            <td class="uk-text-right"><?php echo $product->group_id ?></td>
                                            <td><b>Group Name</b></td>
                                            <td class="uk-text-right"><?php echo $product->group_name ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Barcode</b></td>
                                            <td class="uk-text-right"><?php echo $product->code ?></td>
                                            <td><b>Status</b></td>
                                            <td class="uk-text-right"><?php
                                                if($product->status == 1){
                                                    echo " <span class='uk-badge uk-badge-success'>Active</span>";
                                                }
                                                else{
                                                    echo "<span class='uk-badge uk-badge-danger'>Deactive</span>";
                                                }
                                            ?></td>


                                        </tr>
                                        <tr>
                                            <td><b>Category</b></td>
                                            <td class="uk-text-right"><?php echo $product->category ?></td>
                                            <td><b>Sub-Category</b></td>
                                            <td class="uk-text-right"><?php echo $product->subcategory ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Alert Quantity</b></td>
                                            <td class="uk-text-right"><?php echo $product->alert_quantity ?></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="uk-grid" data-uk-grid-margin="">
                            <div class="uk-width-medium-2-10 uk-row-first">
                                <span class="uk-display-block uk-margin-small-top uk-text-large"><b>Price Detail</b></span>
                            </div>
                            <div class="uk-width-medium-8-10">
                                <table class="uk-table">
                                    <tbody>
                                        <tr>
                                            <td><b>Cost</b></td>
                                            <td class="uk-text-right"><?php echo $product->cost ?></td>
                                            <td><b>MRP</b></td>
                                            <td class="uk-text-right"><?php echo $product->mrp ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="uk-grid" data-uk-grid-margin="">
                            <div class="uk-width-medium-2-10 uk-row-first">
                                <span class="uk-display-block uk-margin-small-top uk-text-large"><b>Suppliers</b></span>
                            </div>
                            <div class="uk-width-medium-8-10">
                                <table class="uk-table">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th class="uk-text-right">Supplier ID</th>
                                            <th></th>
                                            <th class="uk-text-right">Supplier Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            foreach($suppliers as $key => $row){
                                                ?>
                                                <tr>
                                                    <td><?php echo $key+1; ?></td>
                                                    <td class="uk-text-right"><?php echo $row->id ?></td>
                                                    <td></td>
                                                    <td class="uk-text-right"><?php echo $row->name ?></td>
                                                </tr>
                                                <?php
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="uk-grid" data-uk-grid-margin="">
                            <div class="uk-width-medium-2-10 uk-row-first">
                                <span class="uk-display-block uk-margin-small-top uk-text-large"><b>Stock Detail</b></span>
                            </div>
                            <div class="uk-width-medium-8-10">
                                <table class="uk-table">
                                    <thead>
                                        <tr>
                                            <th>Warehouse</th>
                                            <th class="uk-text-right">Available Stock</th>
                                            <th class="uk-text-right">Hold Stock</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $total_stock = 0;
                                            $total_hold_tock = 0;
                                            foreach($warehouses as $key => $row){
                                                $total_stock += $row->quantity;
                                                $total_hold_tock += 0;
                                                ?>
                                                <tr>
                                                    <td><b><?php echo $row->name; ?></b></td>
                                                    <td class="uk-text-right"><?php echo $row->quantity ?></td>
                                                    <td class="uk-text-right">0</td>
                                                    <td></td>
                                                </tr>
                                                <?php
                                            }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Total</th>
                                            <th class="uk-text-right"><?php echo $total_stock; ?></th>
                                            <th class="uk-text-right"><?php echo $total_hold_tock; ?></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="uk-grid" data-uk-grid-margin="">
                            <div class="uk-width-medium-2-10 uk-row-first">
                                <span class="uk-display-block uk-margin-small-top uk-text-large"><b>Product Detail</b></span>
                            </div>
                            <div class="uk-width-medium-8-10">
                                <?php echo $product->product_details ?>
                            </div>
                        </div>
                    </li>
                    <li>4Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque consequatur dolore doloribus ducimus est ex facere fugiat impedit ipsum iure laborum magni minus nam nostrum optio pariatur quisquam, sapiente ut?</li>
                    <li>5Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias aliquam animi aspernatur dolore dolorem doloribus eius fugiat id impedit ipsum nam nemo, nisi nulla obcaecati odio officiis placeat quasi quia repellat tempore veritatis vero, voluptas? Facilis incidunt odit quam similique.</li>
                    <li>6Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias amet atque beatae consequuntur delectus deleniti dolor eaque eligendi enim, et eum, exercitationem fugit, harum ipsam iure minus necessitatibus neque officiis optio quaerat quam quasi recusandae vitae voluptas voluptates? Ad aspernatur atque autem beatae, blanditiis cupiditate debitis doloribus et excepturi laborum magnam porro praesentium quae quaerat quisquam sapiente sint? Dicta, fugiat!</li>
                    <li>7Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias amet atque beatae consequuntur delectus deleniti dolor eaque eligendi enim, et eum, exercitationem fugit, harum ipsam iure minus necessitatibus neque officiis optio quaerat quam quasi recusandae vitae voluptas voluptates? Ad aspernatur atque autem beatae, blanditiis cupiditate debitis doloribus et excepturi laborum magnam porro praesentium quae quaerat quisquam sapiente sint? Dicta, fugiat!</li>
                    <li>8Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias amet atque beatae consequuntur delectus deleniti dolor eaque eligendi enim, et eum, exercitationem fugit, harum ipsam iure minus necessitatibus neque officiis optio quaerat quam quasi recusandae vitae voluptas voluptates? Ad aspernatur atque autem beatae, blanditiis cupiditate debitis doloribus et excepturi laborum magnam porro praesentium quae quaerat quisquam sapiente sint? Dicta, fugiat!</li>
                </ul>
            </div>
        </div>
    </div>
</div>