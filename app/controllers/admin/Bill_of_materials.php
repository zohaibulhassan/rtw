<?php defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class Bill_of_materials extends MY_Controller
{
    public function __construct(){
        parent::__construct();
        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            $this->sma->md('login');
        }
        if ($this->Customer) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        $this->lang->admin_load('purchases', $this->Settings->user_language);
        $this->load->library('form_validation');
        $this->load->admin_model('general_model');
    }
    public function index($warehouse_id = null){

        $this->data['warehouses'] = $this->general_model->GetAllWarehouses();
        $this->data['suppliers'] = $this->general_model->GetAllSuppliers();


        $this->data['warehouse'] = $this->input->get('warehouse');        
        $this->data['supplier'] = $this->input->get('supplier');    


        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => 'Bill of Materials'));
        $meta = array('page_title' => 'Bill of Materials', 'bc' => $bc);
        $this->page_construct2('bom/index', $meta, $this->data);


    }
    public function get_lists(){
        // Count Total Rows
        $this->db->from('boms');
        $totalq = $this->db->get();
        $this->runquery_bom('yes');
        $query = $this->db->get();
        $recordsFiltered = $query->num_rows();
        $this->runquery_bom();
        if($_POST['length'] != -1){
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        $rows = $query->result();

        $data = array();
        foreach($rows as $row){
            $button = '<a class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light md-btn-mini" href="'.base_url("admin/bill_of_materials/view/".$row->id).'" >Materials List</a>';
            $button .= '<a class="md-btn md-btn-warning md-btn-wave-light waves-effect waves-button waves-light md-btn-mini" href="'.base_url("admin/bill_of_materials/edit/".$row->id).'" >Edit</a>';
            $button .= '<a class="md-btn md-btn-danger md-btn-wave-light waves-effect waves-button waves-light md-btn-mini deletebtn" data-id="'.$row->id.'" >Delete</a>';
            $data[] = array(
                $row->id,
                $row->date,
                $row->product_id,
                $row->product_name,
                $row->material_cost,
                $button
            );
        }
        // $output = array(
        //     "draw" => $_POST['draw'],
        //     "recordsTotal" => 0,
        //     "recordsFiltered" => 0,
        //     "data" => $data,
        // );
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $totalq->num_rows(),
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );
        // Output to JSON format
        echo json_encode($output);
    }
    public function runquery_bom($onlycoun = "no"){
        $column_search = array(
            'boms.id',
            'boms.product_name',
            'boms.product_id',
        );
        //Get Data
        if($onlycoun == "yes"){
            $this->db->select('boms.id as id');
        }
        else{
            $this->db->select('
                boms.*,
            ');
        }
        $this->db->from('boms');
        $this->db->join('users as u', 'u.id = boms.created_by', 'left');
        $i = 0;
        // loop searchable columns 
        if($onlycoun != "yes"){
            foreach($column_search as $item){
                // if datatable send POST for search
                if($_POST['search']['value']){
                    // first loop
                    if($i===0){
                        // open bracket
                        $this->db->group_start();
                        $this->db->like($item, $_POST['search']['value']);
                    }else{
                        $this->db->or_like($item, $_POST['search']['value']);
                    }
                    // last loop
                    if(count($column_search) - 1 == $i){
                        // close bracket
                        $this->db->group_end();
                    }
                }
                $i++;
            }
        }
        if($onlycoun != "yes"){
            $this->db->order_by($_POST['order']['0']['column']+1, $_POST['order']['0']['dir']);
        }
    }
    public function view($id = null){
        if($id != ""){
            $this->db->select('
                boms.*,
                CONCAT(u.first_name," ",u.last_name) as created_by
            ');
            $this->db->from('boms');
            $this->db->join('users as u', 'u.id = boms.created_by', 'left');
            $this->db->where('boms.id',$id);
            $q  = $this->db->get();
            if($q->num_rows() > 0){
                $this->data['bom'] = $q->result()[0];
                $this->db->select('
                    bom_items.*,
                    p.name as product_name,
                    p.code as product_code
                ');
                $this->db->from('bom_items');
                $this->db->join('products as p', 'p.id = bom_items.material_id', 'left');
                $this->db->where('bom_items.bom_id',$id);
                $qi  = $this->db->get();
                $this->data['items'] = $qi->result();
                $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('bill_of_materials'), 'page' => 'Bill of Materials'), array('link' => '#', 'page' => lang('view')));
                $meta = array('page_title' => 'BOM Detail', 'bc' => $bc);
                $this->page_construct2('bom/view', $meta, $this->data);
            }
            else{
                // redirect($_SERVER['HTTP_REFERER']);
            }
        }
        else{
            // redirect($_SERVER['HTTP_REFERER']);
        }
    }

    

    public function get_items(){
        // Count Total Rows
        $this->db->from('purchase_items');
        $totalq = $this->db->get();
        $this->runquery_items('yes');
        $query = $this->db->get();
        $recordsFiltered = $query->num_rows();
        $this->runquery_items();
        $query = $this->db->get();
        $rows = $query->result();

        $data = array();
        $sno = 0;
        foreach($rows as $row){
            $sno++;
            $button = "";
            if($row->quantity == $row->quantity_balance){
                $button .= '<button class="md-btn md-btn-warning md-btn-wave-light waves-effect waves-button waves-light md-btn-mini itemedit" type="button" data-id="'.$row->id.'" data-product="'.$row->product_id.'" data-qty="'.$row->quantity.'" data-panem="'.$row->product_name.'" >Edit</button>';
                $button .= '<button class="md-btn md-btn-danger md-btn-wave-light waves-effect waves-button waves-light md-btn-mini itemdelete" type="button" data-id="'.$row->id.'" >Delete</button>';
            }
            $data[] = array(
                $sno,
                $row->product_id,
                $row->product_name,
                $row->barcode,
                $row->quantity,
                $row->net_unit_cost,
                // $row->expiry,
                // $row->batch,
                $row->adv_tax,
                $row->subtotal,
                $button
            );
        }
        $output = array(
            "data" => $data,
        );
        // Output to JSON format
        echo json_encode($output);
    }
    public function runquery_items($onlycoun = "no"){
        $id = $this->input->post('id');
        //Get Data
        if($onlycoun == "yes"){
            $this->db->select('p_items.id as id');
        }
        else{
            $this->db->select('
                bom_items.*,
                products.id as product_id,
                products.name as product_name,
                products.code as barcode,
            ');
        }
        $this->db->from('bom_items as b_items');
        $this->db->join('products as products', 'products.id = b_items.material_id', 'left');
        $this->db->where('b_items.bom_id',$id);
    }
    public function add(){
        $this->data['groups'] = $this->db->select('id,name')->from('product_groups')->get()->result();
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => 'Bill of Materials'));
        $meta = array('page_title' => 'Bill of Materials', 'bc' => $bc);
        $this->page_construct2('bom/add', $meta, $this->data);
    }
    public function edit($id){
        if($id != ""){
            $this->db->select('
                boms.*,
                CONCAT(u.first_name," ",u.last_name) as created_by
            ');
            $this->db->from('boms');
            $this->db->join('users as u', 'u.id = boms.created_by', 'left');
            $this->db->where('boms.id',$id);
            $q  = $this->db->get();
            if($q->num_rows() > 0){
                $this->data['bom'] = $q->result()[0];
                $this->db->select('
                    0 as alert_quantity,
                    "" as batch,
                    1 carton_size,
                    p.code as code,
                    p.company_code as company_code,
                    bom_items.rate as cost,
                    "" as expiry,
                    0 as fed_tax,
                    "" as formulas,
                    p.id as id,
                    p.mrp as mrp,
                    p.name as name,
                    p.price as price,
                    0 as product_tax,
                    bom_items.quantity as quantity,
                    1 as tax_method,
                    "" as tax_name,
                    0 as tax_rate,
                    1 as tax_type
                ');
                $this->db->from('bom_items');
                $this->db->join('products as p', 'p.id = bom_items.material_id', 'left');
                $this->db->where('bom_items.bom_id',$id);
                $qi  = $this->db->get();
                $this->data['items'] = $qi->result();

                $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => 'Bill of Materials'));
                $meta = array('page_title' => 'Bill of Materials', 'bc' => $bc);
                $this->page_construct2('bom/edit', $meta, $this->data);
            }
            else{
                // redirect($_SERVER['HTTP_REFERER']);
            }
        }
        else{
            // redirect($_SERVER['HTTP_REFERER']);
        }




    }

    public function product($id){
        $this->db->select('products.*,units.code as unit_code');
        $this->db->from('products');
        $this->db->join('units','units.id = products.unit','left');
        $this->db->where('products.id',$id);
        $q = $this->db->get();
        if($q->num_rows() > 0){
            return $q->result()[0];
        }
        else{
            return false;
        }
    }
    public function submit(){

        $sendvalue['status'] = false;
        $sendvalue['message'] = '';

        $fg_product = $this->input->post('product');
        $fg_product_data = $this->getProductByID($fg_product);




        $insert['date'] = date('Y-m-d H:i:s');
        $insert['product_id'] = $fg_product_data->id;
        $insert['product_name'] = $fg_product_data->name;
        $insert['estimated_labour_cost'] = $this->input->post('elc_amount');
        $insert['estimiated_factory_cost'] = $this->input->post('efo_ammount');

        $insert['material_cost'] = 0;
        $insert['total_cost'] = 0;
        $insertitems = array();
        $pids = $this->input->post('product_id');
        $qty = $this->input->post('qty');
        foreach($pids as $key => $pid){
            $materialdata = $this->getProductByID($pid);
            $insertitem['bom_id'] = "";
            $insertitem['material_id'] = $materialdata->id;
            $insertitem['quantity'] = $qty[$key];
            $insertitem['rate'] = $materialdata->cost;
            $insertitem['total'] = $materialdata->cost*$qty[$key];
            $insert['material_cost'] += $insertitem['total'];
            $insertitems[] = $insertitem;
        }

        $insert['total_cost'] = $insert['material_cost']+$insert['estimated_labour_cost']+$insert['estimiated_factory_cost'];

        $this->db->insert('boms',$insert);
        $insert_id = $this->db->insert_id();
        foreach($insertitems as $insertitem){
            $insertitem['bom_id'] = $insert_id;
            $this->db->insert('bom_items',$insertitem);
        }
        $sendvalue['status'] = true;
        $sendvalue['message'] = 'Bill of Material Generated';
        echo json_encode($sendvalue);


    }
    public function update(){

        $sendvalue['status'] = false;
        $sendvalue['message'] = '';

        $bom_id = $this->input->post('bom_id');
        $fg_product = $this->input->post('product');
        $fg_product_data = $this->getProductByID($fg_product);




        $set['date'] = date('Y-m-d H:i:s');
        $set['product_id'] = $fg_product_data->id;
        $set['product_name'] = $fg_product_data->name;
        $set['estimated_labour_cost'] = $this->input->post('elc_amount');
        $set['estimiated_factory_cost'] = $this->input->post('efo_ammount');

        $set['material_cost'] = 0;
        $set['total_cost'] = 0;
        $insertitems = array();
        $pids = $this->input->post('product_id');
        $qty = $this->input->post('qty');
        foreach($pids as $key => $pid){
            $materialdata = $this->getProductByID($pid);
            $insertitem['bom_id'] = "";
            $insertitem['material_id'] = $materialdata->id;
            $insertitem['quantity'] = $qty[$key];
            $insertitem['rate'] = $materialdata->cost;
            $insertitem['total'] = $materialdata->cost*$qty[$key];
            $set['material_cost'] += $insertitem['total'];
            $insertitems[] = $insertitem;
        }
        $set['total_cost'] = $set['material_cost']+$set['estimated_labour_cost']+$set['estimiated_factory_cost'];
        $this->db->set($set);
        $this->db->where('id',$bom_id);
        $this->db->update('boms');
        $this->db->delete('bom_items', array('bom_id' => $bom_id));
        foreach($insertitems as $insertitem){
            $insertitem['bom_id'] = $bom_id;
            $this->db->insert('bom_items',$insertitem);
        }
        $sendvalue['status'] = true;
        $sendvalue['message'] = 'Bill of Material Updated';
        echo json_encode($sendvalue);


    }


    public function getProductByID($id){
        $this->db->select('id,code,name,cost');
        $this->db->from('products');
        $this->db->where('id',$id);
        $q = $this->db->get();
        return $q->result()[0];
    }





    public function excel(){
   
        $spreadsheet = new Spreadsheet();

        // Get the active worksheet
        $sheet = $spreadsheet->getActiveSheet();
    
        // Set the column headers
        $sheet->setCellValue('A1', 'Product Name');
        $sheet->setCellValue('B1', 'Material');
        $sheet->setCellValue('C1', 'Quantity');
    
        // Fetch the data from the database
        $this->db->select('b.product_name AS "Product Name", p.name AS "Material", bi.quantity AS "Quantity"');
        $this->db->from('sma_bom_items bi');
        $this->db->join('sma_boms b', 'b.id = bi.bom_id');
        $this->db->join('sma_products p', 'p.id = bi.material_id');
        $query = $this->db->get();

     
        $data  = $query->result_array();
     
        // Set the data in the worksheet
        $row = 2;
        foreach ($data as $row_data) {
            $sheet->setCellValue('A' . $row, $row_data['Product Name']);
            $sheet->setCellValue('B' . $row, $row_data['Material']);
            $sheet->setCellValue('C' . $row, $row_data['Quantity']);
            $row++;
        }
    
        // Create a new Xlsx Writer
        $writer = new Xlsx($spreadsheet);
    
        // Set the appropriate headers for the download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="product_materials.xlsx"');
        header('Cache-Control: max-age=0');
    
        // Save the Spreadsheet to output (browser download)
        $writer->save('php://output');

     }

     public function attachment()
     { 
        $data = $this->input->$_FILES('attachment');
        print_r($data);
        exit;


        if ($this->input->server('REQUEST_METHOD') == 'POST')
		{		
				$fileUpload = [];
				$hasFileUploaded = FALSE;

				$filePrefrence = [

					'upload_path' => './uploads/',
					'allowed_types' => 'jpg|jpeg|png|gif',
					'encrypt_name' => TRUE
				];

				$this->upload->initialize($filePrefrence);

				if (! $this->upload->do_upload('brand_img')) {
					$data['error'] = $this->upload->display_errors();
				}
				else{
					$fileUpload = $this->upload->data();
					$hasFileUploaded = TRUE;
				}

				if ($hasFileUploaded) {

						$options = [
							'create_date' => $this->input->post('create_date'),
							'title' => $this->input->post('title'),
							'slug' => $this->input->post('slug'),
							'status' => 'DEACTIVE',
							'brand_img' => $fileUpload['file_name'],
							'meta_description' => $this->input->post('meta_description'),
							'meta_keyword' => $this->input->post('meta_keyword')

						];
						$this->brand->create($options);
						redirect('/admin/brand/','refresh');

				}
			}


		$data['title'] = 'Create Brand';
		$data['mainContent'] = 'admin/brand/add';
		$this->load->view('admin/layout/master',$data);
	}

    public function uploadsheet() {
   
        // Check if a file was uploaded
        if (isset($_FILES['file'])) {
            $fileTmpPath = $_FILES['file']['tmp_name'];
            $spreadsheet = IOFactory::load($fileTmpPath);
            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();
            
            $highestColumn = $worksheet->getHighestColumn();

            for ($row = 1; $row <= $highestRow; $row++) {
                // Fetch the data from the current row
                $rowData = $worksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, false);
                $productName = $rowData[0][0];
                $this->db->select('*');
                $this->db->from('sma_products');
                $this->db->where('name', $productName);
                $query = $this->db->get();
                $result = $query->result_array();
                $productid =  $result[0]['id'];
$this->data['result'] = $result;    
                            //     echo "<pre>";
            //    print_r($result[0]);
                
            }
            // echo "<pre>";
            // print_r($rowData);
            // exit;
           
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => 'Bill of Materials'));
            $meta = array('page_title' => 'Bill of Materials', 'bc' => $bc);
            $this->page_construct2('bom/index', $meta,$this->data );
        
        }


    }

    public function delete(){
        $senddata['status'] = false;
        $senddata['message'] = "Try again!";
        $id = $this->input->post('id');
        $reason = $this->input->post('reason');
        if($this->data['Owner']){
            if($reason != ""){
                $this->db->delete('bom_items', array('bom_id' => $id));
                $this->db->delete('boms', array('id' => $id));
                $senddata['status'] = true;
                $senddata['message'] = "Bill of material delete successfully!";
            }
            else{
                $senddata['message'] = "Enter Reason!";
            }
        }
        else{
            $senddata['message'] = "Permission Denied!";
        }
        echo json_encode($senddata);



        
    }


   
}
