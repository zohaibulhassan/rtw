<style>
    .table thead tr th {
        text-align: left !important;
    }
    .tabledb input {

    }
</style>
                        
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-barcode"></i>Verification Report (<?= $store->name ?>)</h2>
        <span id="ajaxloading" style="float: right;line-height: 41px;padding-right: 38px;color: red;font-weight: bold;" ><b>0</b> Products Found. <strong style="display:none;" >Loading......</strong> </span>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12" id="headerdiv">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table tabledb">
                    <thead>
                        <tr>
                            <th>Store Product ID</th>
                            <th>Rhocom P.ID</th>
                            <th>Store Name</th>
                            <th>Rhocom Name</th>
                            <th>Type</th>
                            <th>Price Update In</th>
                            <th>Rhocom MRP</th>
                            <th>Ragular Price</th>
                            <th>MRP Status</th>
                            <th>Rhocom Selling Price</th>
                            <th>Store Selling Price</th>
                            <th>Selling Status</th>
                            <th>Qty Update In</th>
                            <th>Rhocom Qty</th>
                            <th>Store Hold Qty</th>
                            <th>Actual Qty</th>
                            <th>Store Qty</th>
                            <th>Stock Status</th>
                            <th>Store Status</th>
                            <th>Rhocom360 Status</th>
                            <th>Integration Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Store Product ID</th>
                            <th>Rhocom P.ID</th>
                            <th>Store Name</th>
                            <th>Rhocom Name</th>
                            <th>Type</th>
                            <th>Price Update In</th>
                            <th>Rhocom MRP</th>
                            <th>Ragular Price</th>
                            <th>MRP Status</th>
                            <th>Rhocom Selling Price</th>
                            <th>Store Selling Price</th>
                            <th>Selling Status</th>
                            <th>Qty Update In</th>
                            <th>Rhocom Qty</th>
                            <th>Store Hold Qty</th>
                            <th>Actual Qty</th>
                            <th>Store Qty</th>
                            <th>Stock Status</th>
                            <th>Store Status</th>
                            <th>Rhocom360 Status</th>
                            <th>Integration Status</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.22/b-1.6.4/b-flash-1.6.4/b-html5-1.6.4/b-print-1.6.4/sb-1.0.0/datatables.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/html2canvas.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready( function () {
        var i = 0;
        var sid = <?= $store->id ?>;
        var limit = 50;
        var products = [];
        var totalproduct =0;

        get_products();
        //Data Load
        function get_products(){
            $('#ajaxloading strong').show();
            i++;
            console.log(i);
            
            $.ajax({
                type: "get",
                data: {sid:sid,page:i,limit:limit},
                url: '<?= admin_url('stores/report6_ajax'); ?>',
                success: function(data) {
                    var obj = jQuery.parseJSON(data);
                    if(obj.codestatus == "ok"){
                        products = products.concat(obj.products);
                        totalproduct += obj.count;
                        $('#ajaxloading b').html(totalproduct);
                        if(obj.count>=limit){
                            get_products();
                            console.log('Send Again');
                        }
                        else{
                            console.log('Complete');
                            $('#ajaxloading strong').hide();
                            fullData();
                        }
                    }
                    else{
                        if (confirm("Something went wrong. Do you want to continues!") == false) {
                            $('#ajaxloading strong').hide();
                            fullData();
                        }
                        else{
                            i--;
                            get_products();
                        }
                    }
                }
            });
        }
        function fullData(){
            products.forEach(function(product) {
                var html = '';
                html += '<tr>';
                    html += '<td>'+product.store_product_id+'</td>';
                    html += '<td>'+product.rhocom_pid+'</td>';
                    html += '<td>'+product.name+'</td>';
                    html += '<td>'+product.rhocom_name+'</td>';
                    html += '<td>'+product.type+'</td>';
                    html += '<td>'+product.update_in+'</td>';
                    html += '<td>'+product.rhocom_mrp+'</td>';
                    html += '<td>'+product.regular_price+'</td>';
                    html += '<td>'+product.mrp_status+'</td>';
                    html += '<td>'+product.rhocom_selling_price+'</td>';
                    html += '<td>'+product.selling_price+'</td>';
                    html += '<td>'+product.selling_status+'</td>';
                    html += '<td>'+product.update_in_qty+'</td>';
                    html += '<td>'+product.rhocom_qty+'</td>';
                    html += '<td>'+product.store_hold_qty;
                    if(product.store_hold_qty > 0){
                        html += "<i class='fa fa-info-circle sodetail' data-so='"+product.so+"' style='margin-left: 6px;cursor: pointer;'></i>";
                    }
                    html += '</td>';
                    html += '<td>'+product.actual_qty+'</td>';
                    html += '<td>'+product.store_qty+'</td>';
                    html += '<td>'+product.stockstatus+'</td>';
                    html += '<td>'+product.storestatus+'</td>';
                    html += '<td>'+product.rhocomstatus+'</td>';
                    html += '<td>'+product.integrationstatus+'</td>';
                html += '</tr>';
                $('.tabledb tbody').append(html);
            });

            $(".tabledb tfoot th").each( function () {
                var title = $(this).text();
                $(this).html( "<input type='text' class='' placeholder='Search "+title+"' />" );
            });
            $('.tabledb').DataTable({
                dom: 'Bfrtip',
                scrollX: true,
                responsive: true,
                paging: true,
                pageLength: 25,
                buttons: [
                    {
                        extend: 'copy',
                    },
                    {
                        extend: 'csv',
                    },
                    {
                        extend: 'excel',
                    },
                ],
                initComplete: function () {
                    this.api().columns().every(function(){
                        var that = this;
                        $( "input", this.footer() ).on( "keyup change clear", function () {
                            if ( that.search() !== this.value ) {
                                that
                                .search(this.value)
                                .draw();
                            }
                        });
                    });
                }
            });
            setTimeout(function(){
                var windowwidth = $(window ).width();
                var sidewidth = $('.sidebar-con').width();
                var width = windowwidth-sidewidth-30;
                console.log(windowwidth+"px");
                console.log(sidewidth+"px");
                console.log(width+"px");
                $('.dataTables_scrollHeadInner').css('width',width+'px');
                $('.dataTables_scrollHeadInner table').css('width',width+'px');
                $('.tabledb').css('width',width+'px');
                width = width-40;
                $('.dataTables_scroll').css('width',width+'px');
                $('.sorting_asc').click();
            }, 500);
        }
        $(document).on('click','.sodetail',function(){
            var so = $(this).data('so');
            var html = '<table class="table" >';
                html += '<thead>';
                    html += '<tr>';
                        html += '<th>Sale Order No</th>';
                        html += '<th>Customer</th>';
                        html += '<th>Demand Qty</th>';
                        html += '<th>Complete Qty</th>';
                        html += '<th>Pending Qty</th>';
                    html += '</tr>';
                html += '</thead>';
                html += '<tbody>';
                so.forEach(function(s) {
                    html += '<tr>';
                        html += '<td>'+s.ref_no+'</td>';
                        html += '<td>'+s.company+'</td>';
                        html += '<td>'+s.quantity+'</td>';
                        html += '<td>'+s.complete_qty+'</td>';
                        var pending_qty = parseInt(s.quantity)-parseInt(s.complete_qty);
                        html += '<td>'+pending_qty+'</td>';
                    html += '</tr>';
                });
                html += '</tbody>';
            html += '</table>';
            swal.fire({
                title: 'Sale Orders List',
                html: html,
                width: 700
            })
            console.log(so);
        });
    });
</script>

