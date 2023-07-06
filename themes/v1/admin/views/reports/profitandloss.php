<style>
    .uk-open>.uk-dropdown,
    .uk-open>.uk-dropdown-blank {}
</style>
<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-content">
                <?php
                $attrib = ['data-toggle' => 'validator', 'role' => 'form', 'id' => 'stForm'];
                echo admin_form_open_multipart('reports/profitandlosspost', $attrib);
                ?>
                <input type="hidden" name="show_type" value="2">
                <div class="uk-grid">
                    <div class="uk-width-large-1-2">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Form Date</label>
                            <input class="md-input  label-fixed" type="text" name="start_date"
                                data-uk-datepicker="{format:'YYYY-MM-DD'}" autocomplete="off"
                                value="<?php echo $date_from; ?>" readonly>
                        </div>
                    </div>
                    <div class="uk-width-large-1-2">
                        <div class="md-input-wrapper md-input-filled">
                            <label>To Date</label>
                            <input class="md-input  label-fixed" type="text" name="end_date"
                                data-uk-datepicker="{format:'YYYY-MM-DD'}" autocomplete="off"
                                value="<?php echo $date_to; ?>" readonly>
                        </div>
                    </div>
                    <div class="uk-width-large-1-4" style="padding-top: 20px;">
                        <button type="submit"
                            class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light">Submit</button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
        <div class="md-card">
            <div class="md-card-toolbar">
                <h3 class="md-card-toolbar-heading-text">Batch Wise Report</h3>
                <div class="md-card-toolbar-actions">
                    <i class="md-icon material-icons md-card-fullscreen-activate"></i>
                </div>
            </div>
            <div class="md-card-content">
                <div class="dt_colVis_buttons"></div>
                <table id="dt_tableExport" class="uk-table ">
                    <thead>
                        <tr>
                            <!-- <th>ID</th> -->
                            <th>Discount_Item</th>
                            <th>Discount_Bill</th>
                            <th>Sales</th>
                            <th>Shipping</th>
                            <th>Operating Income</th>
                            <th>Over Head Expense</th>
                            <th>Material Cost</th>
                            <th>Total Goods Sold</th>
                            <th>Gross Profit</th>
                            <th>Expense</th>
                            <th>Operating Profit</th>
                            <th>Net Profit and Loss</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <!-- <td>1</td> -->
                            <td>
                                <?php echo $this->data['Discount_Item']; ?>
                            </td>
                            <td>
                                <?php echo $this->data['Discount_Bill']; ?>
                            </td>
                            <td>
                                <?php echo $this->data['sales']; ?>
                            </td>
                            <td>
                                <?php echo $this->data['shipping']; ?>
                            </td>
                            <td>
                                <?php echo $this->data['operating_income']; ?>
                            </td>
                            <td>
                                <?php echo $this->data['overheadexpense']; ?>
                            </td>
                            <td>
                                <?php echo $this->data['material_cost']; ?>
                            </td>
                            <td>
                                <?php echo $this->data['total_goods_sold']; ?>
                            </td>
                            <td>
                                <?php echo $this->data['grossprofit']; ?>
                            </td>
                            <td>
                                <?php echo $this->data['sumofexpanceamount']; ?>
                            </td>
                            <td>0</td>
                            <td>0</td>
                        </tr>

                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

<!-- datatables -->
<script
    src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
<!-- datatables buttons-->
<script
    src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/datatables-buttons/js/dataTables.buttons.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>js/custom/datatables/buttons.uikit.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/jszip/dist/jszip.min.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/pdfmake/build/pdfmake.min.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/pdfmake/build/vfs_fonts.js"></script>
<script
    src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/datatables-buttons/js/buttons.colVis.js"></script>
<script
    src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/datatables-buttons/js/buttons.html5.js"></script>
<script
    src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/datatables-buttons/js/buttons.print.js"></script>
<script
    src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/datatables-fixedcolumns/dataTables.fixedColumns.min.js"></script>
<!-- datatables custom integration -->
<script src="<?php echo base_url('themes/v1/assets/'); ?>js/custom/datatables/datatables.uikit.min.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>js/datatable.js"></script>
<script>
    var csrfName = "<?php echo $this->security->get_csrf_token_name(); ?>",
        csrfHash = "<?php echo $this->security->get_csrf_hash(); ?>";
    var data = [];
    data[csrfName] = csrfHash;

    $(document).ready(function() {
  var table = $('#dt_tableExport').DataTable({
    dom: 'Bfrtip',
    "scrollX": true,
    "scrollCollapse": true,
    buttons: [
     "print","csv","copy", {
        extend: 'excel',
        title : "PNL Report : Rhocom 360",
        action: function(e, dt, button, config) {
          var headers = [];
          dt.columns().every(function() {
            headers.push(this.header().textContent.trim());
          });

          var data = dt.rows().data().toArray();
          var transposedData = transposeData([headers].concat(data)); // Include headers in the data array
          var excelData = generateExcelData(transposedData);

          var blob = new Blob([excelData], { type: 'application/vnd.ms-excel' });
          saveAs(blob, 'transposed_data.xls');
        }
      }
    ]
  });
});

function transposeData(data) {
  var transposed = [];

  for (var col = 0; col < data[0].length; col++) {
    transposed[col] = [];
    for (var row = 0; row < data.length; row++) {
      transposed[col][row] = data[row][col];
    }
  }

  return transposed;
}

function generateExcelData(data, title) {
  var excelData = '';

  // Add the title
  excelData += '<b> Profit and Loss Report : Rhocom 360 </b>\n\n';

  for (var row = 0; row < data.length; row++) {
    if (
      data[row][0] === 'Operating Income' ||
      data[row][0] === 'Total Goods Sold' ||
      data[row][0] === 'Operating Profit' ||
      data[row][0] === 'Net Profit and Loss'
    ) {
      excelData += '\n'; // Add an empty row above the specified headings
      excelData += '<b>' + data[row][0] + '</b>\t'; // Apply bold formatting to the heading
    } else {
      excelData += data[row][0] + '\t';
    }

    for (var col = 1; col < data[row].length; col++) {
      var cellData = data[row][col];
      excelData += cellData + '\t';
    }
    excelData += '\n';
  }

  return excelData;
}




</script>
<script>
    $(document).ready(function () {
        $('#dt_tableExport').DataTable();
    });
</script>