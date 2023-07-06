<?php defined('BASEPATH') or exit('No direct script access allowed');



class Purchases extends MY_Controller
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
        $this->load->admin_model('purchases_model');
        $this->load->admin_model('sales_model');
        $this->load->admin_model('sales_model');
        $this->digital_upload_path = 'files/';
        $this->upload_path = 'assets/uploads/';
        $this->thumbs_path = 'assets/uploads/thumbs/';
        $this->image_types = 'gif|jpg|jpeg|png|tif';
        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
        $this->allowed_file_size = '1024';
        $this->data['logo'] = true;
    }

    //  New
    public function expenses($id = null){
        $this->sma->checkPermissions();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('expenses')));
        $meta = array('page_title' => lang('expenses'), 'bc' => $bc);
        $this->page_construct2('purchases/expenses', $meta, $this->data);
    }
    public function get_expenses(){
        // Count Total Rows
        $this->db->from('expenses');
        $totalq = $this->db->get();
        $this->runquery('yes');
        $query = $this->db->get();
        $recordsFiltered = $query->num_rows();
        $this->runquery();
        if($_POST['length'] != -1){
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        $rows = $query->result();

        
        $data = array();
        foreach($rows as $row){
            $button = '<a href="'.base_url('admin/purchases/edit_expense/'.$row->id).'" class="md-btn md-btn-warning md-btn-wave-light waves-effect waves-button waves-light md-btn-mini" >Edit</a>';
            $button .= '<button class="md-btn md-btn-danger md-btn-wave-light waves-effect waves-button waves-light md-btn-mini deletebtn" data-id="'.$row->id.'" >Delete</button>';
            $data[] = array(
                $row->id,
                $row->date,
                $row->reference,
                ucfirst($row->etype),
                $row->warehouse,
                $row->category,
                $row->companyname,
                $row->amount,
                ucfirst($row->pay_method),
                $row->wallet,
                $row->cheeque_no,
                $row->transaction_no,
                $row->pay_order_no,
                $row->note,
                $row->created_by,
                $button
            );
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $totalq->num_rows(),
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );
        // Output to JSON format
        echo json_encode($output);
    }
    public function runquery($onlycoun = "no"){

        $column_order = array(
            'expenses.id',
            'expenses.date',
            'expenses.reference',
            'expenses.etype',
            'warehouses.name',
            'ec.name',
            'oc.companyname',
            'expenses.amount',
            'expenses.pay_method',
            'wallets.title',
            'expenses.cheeque_no',
            'expenses.transaction_no',
            'expenses.pay_order_no',
            'expenses.note',
            'users.first_name'
        );
        $column_search = array(
            'expenses.id',
            'expenses.date',
            'expenses.reference',
            'expenses.etype',
            'warehouses.name',
            'ec.name',
            'oc.companyname',
            'expenses.amount',
            'expenses.pay_method',
            'wallets.title',
            'expenses.cheeque_no',
            'expenses.transaction_no',
            'expenses.pay_order_no',
            'expenses.note',
            'users.first_name',
            'users.last_name'
        );
        //Get Data
        if($onlycoun == "yes"){
            $this->db->select('expenses.id as id');
        }
        else{
            $this->db->select('
                expenses.id,
                expenses.date,
                expenses.reference,
                expenses.etype,
                warehouses.name as warehouse,
                ec.name as category,
                oc.companyname,
                expenses.amount,
                expenses.pay_method,
                wallets.title as wallet,
                expenses.cheeque_no,
                expenses.transaction_no,
                expenses.pay_order_no,
                expenses.note,
                CONCAT(users.first_name," ",users.last_name) as created_by,
            ');
        }
        $this->db->from('expenses as expenses');
        $this->db->join('warehouses','warehouses.id = expenses.warehouse_id','left');
        $this->db->join('expense_categories as ec','ec.id = expenses.category_id','left');
        $this->db->join('own_companies as oc','oc.id = expenses.own_company','left');
        $this->db->join('users as users','users.id = expenses.created_by','left');
        $this->db->join('wallets as wallets','wallets.id = expenses.wallet_id','left');
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
            $this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }
    }
    public function add_expense(){
        $this->sma->checkPermissions('expenses', true);
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

        $this->data['companies'] = $this->general_model->GetAllOwnCompanies();       
        $this->data['wallets'] = $this->general_model->GetAllWallets();       
        $this->data['categories'] = $this->general_model->GetAllExpenseCategories();       
        $this->data['warehouses'] = $this->general_model->GetAllWarehouses();       

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('system_settings/add_expense'), 'page' => lang('Add Expense')), array('link' => '#', 'page' => lang('Add Expense')));
        $meta = array('page_title' => lang('Add Expense'), 'bc' => $bc);
        $this->page_construct2('purchases/add_expense', $meta, $this->data);
    }
    public function insert_expense(){
        $senddata['status'] = false;
        $senddata['message'] = "Try Again!";
        $date = $this->input->post('date'); // Required
        $reference = $this->input->post('reference_no'); // Required
        $category = $this->input->post('category'); // Required
        $location = $this->input->post('location'); // Required
        $owncompany = $this->input->post('owncompany'); // Required
        $type = $this->input->post('type'); // Required
        $purchases = $this->input->post('purchases');
        $sales = $this->input->post('sales');
        $suppliers = $this->input->post('suppliers');
        $customers = $this->input->post('customers');
        $paymentmethod = $this->input->post('paymentmethod'); // Required
        $wallet = $this->input->post('wallet');
        $transferno = $this->input->post('transferno');
        $cheque = $this->input->post('cheque');
        $payorder = $this->input->post('payorder');
        $amount = $this->input->post('amount'); // Required
        $note = $this->input->post('note'); // Required

        if($date == ""){
            $senddata['message'] = "Please Select Date";
        }
        else if($reference == ""){
            $senddata['message'] = "Please Enter Reference No";
        }
        else if($category == ""){
            $senddata['message'] = "Please Select Category";
        }
        else if($location == ""){
            $senddata['message'] = "Please Select Location";
        }
        else if($owncompany == ""){
            $senddata['message'] = "Please Select Own Company";
        }
        else if($type == ""){
            $senddata['message'] = "Please Select Expense Type";
        }
        else if($type == "inbound" && (count($purchases) == 0 || count($suppliers) == 0)){
            $senddata['message'] = "Please Select Purchases or Suppliers";
        }
        else if($type == "outbound" && (count($sales) == 0 || count($suppliers) == 0 || count($customers) == 0)){
            $senddata['message'] = "Please Select Purchases or Suppliers or Customers";
        }
        else if($paymentmethod == ""){
            $senddata['message'] = "Please Select Payment Method";
        }
        else if($paymentmethod == "cash" && $wallet == ""){
            $senddata['message'] = "Please Select Wallet";
        }
        else if($paymentmethod == "onlinetransfer" && $transferno == ""){
            $senddata['message'] = "Please Enter Transfer No";
        }
        else if($paymentmethod == "cheque" && $cheque == ""){
            $senddata['message'] = "Please Enter Cheque No";
        }
        else if($paymentmethod == "payorder" && $payorder == ""){
            $senddata['message'] = "Please Enter Pay Order";
        }
        else if($amount == ""){
            $senddata['message'] = "Please Enter Amount";
        }
        else if($note == ""){
            $senddata['message'] = "Please Enter Note";
        }
        else{
            $insertdata['date'] = $date.' '.date('H:i:s');
            $insertdata['reference'] = $reference;
            $insertdata['amount'] = $amount;
            $insertdata['note'] = $note;
            $insertdata['created_by'] = $this->session->userdata('user_id');;
            $insertdata['category_id'] = $category;
            $insertdata['warehouse_id'] = $location;
            $insertdata['etype'] = $type;
            $insertdata['purchases'] = json_encode($purchases);
            $insertdata['sales'] = json_encode($sales);
            $insertdata['suppliers'] = json_encode($suppliers);
            $insertdata['customers'] = json_encode($customers);
            $insertdata['own_company'] = $owncompany;
            $insertdata['pay_method'] = $paymentmethod;
            $insertdata['wallet_id'] = $wallet;
            $insertdata['cheeque_no'] = $cheque;
            $insertdata['transaction_no'] = $transferno;
            $insertdata['pay_order_no'] = $payorder;
            if($paymentmethod == "cash"){
                $this->db->from('wallets');
                $this->db->where('id',$wallet);
                $wq = $this->db->get();
                if($wq->num_rows() > 0){
                    $wdata = $wq->result()[0];
                    if($wdata->amount >= $amount){
                        $this->db->set('amount', 'amount-'.$amount, FALSE);
                        $this->db->where('id', $wallet);
                        $this->db->update('wallets');
                        $this->db->insert('expenses',$insertdata);
                        $senddata['message'] = "Expense Add Successfully";
                        $senddata['status'] = true;
                    }
                    else{
                        $senddata['message'] = "Insufficient amount in your wallet";
                        
                    }
                }
                else{
                    $senddata['message'] = "Invalid Wallet";
                }
            }
            else{
                $this->db->insert('expenses',$insertdata);
                $senddata['message'] = "Expense Add Successfully";
                $senddata['status'] = true;
            }
        }
        echo json_encode($senddata);
    }
    public function edit_expense($id = null){
        $this->sma->checkPermissions('expenses', true);
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        if($id != ""){
            $this->data['companies'] = $this->general_model->GetAllOwnCompanies();       
            $this->data['wallets'] = $this->general_model->GetAllWallets();       
            $this->data['categories'] = $this->general_model->GetAllExpenseCategories();       
            $this->data['warehouses'] = $this->general_model->GetAllWarehouses();

            $this->db->from('expenses');
            $this->db->where('id',$id);
            $q = $this->db->get();
            if($q->num_rows() > 0){
                $this->data['expense'] = $q->result()[0];
                $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('system_settings/edit_expense'), 'page' => lang('Edit Expense')), array('link' => '#', 'page' => lang('Edit Expense')));
                $meta = array('page_title' => lang('Edit Expense'), 'bc' => $bc);
                $this->page_construct2('purchases/edit_expense', $meta, $this->data);
            }
            else{
                redirect(base_url('admin/purchases/expenses'));
            }
        }
        else{
            redirect(base_url('admin/purchases/expenses'));
        }
    }
    public function update_expense(){
        $senddata['status'] = false;
        $senddata['message'] = "Try Again!";
        $id = $this->input->post('id'); // Required
        $date = $this->input->post('date'); // Required
        $reference = $this->input->post('reference_no'); // Required
        $category = $this->input->post('category'); // Required
        $location = $this->input->post('location'); // Required
        $owncompany = $this->input->post('owncompany'); // Required
        $type = $this->input->post('type'); // Required
        $purchases = $this->input->post('purchases');
        $sales = $this->input->post('sales');
        $suppliers = $this->input->post('suppliers');
        $customers = $this->input->post('customers');
        $paymentmethod = $this->input->post('paymentmethod'); // Required
        $wallet = $this->input->post('wallet');
        $transferno = $this->input->post('transferno');
        $cheque = $this->input->post('cheque');
        $payorder = $this->input->post('payorder');
        $amount = $this->input->post('amount'); // Required
        $note = $this->input->post('note'); // Required

        if($date == ""){
            $senddata['message'] = "Please Select Date";
        }
        else if($reference == ""){
            $senddata['message'] = "Please Enter Reference No";
        }
        else if($category == ""){
            $senddata['message'] = "Please Select Category";
        }
        else if($location == ""){
            $senddata['message'] = "Please Select Location";
        }
        else if($owncompany == ""){
            $senddata['message'] = "Please Select Own Company";
        }
        else if($type == ""){
            $senddata['message'] = "Please Select Expense Type";
        }
        else if($type == "inbound" && (count($purchases) == 0 || count($suppliers) == 0)){
            $senddata['message'] = "Please Select Purchases or Suppliers";
        }
        else if($type == "outbound" && (count($sales) == 0 || count($suppliers) == 0 || count($customers) == 0)){
            $senddata['message'] = "Please Select Purchases or Suppliers or Customers";
        }
        else if($paymentmethod == ""){
            $senddata['message'] = "Please Select Payment Method";
        }
        else if($paymentmethod == "cash" && $wallet == ""){
            $senddata['message'] = "Please Select Wallet";
        }
        else if($paymentmethod == "onlinetransfer" && $transferno == ""){
            $senddata['message'] = "Please Enter Transfer No";
        }
        else if($paymentmethod == "cheque" && $cheque == ""){
            $senddata['message'] = "Please Enter Cheque No";
        }
        else if($paymentmethod == "payorder" && $payorder == ""){
            $senddata['message'] = "Please Enter Pay Order";
        }
        else if($amount == ""){
            $senddata['message'] = "Please Enter Amount";
        }
        else if($note == ""){
            $senddata['message'] = "Please Enter Note";
        }
        else{
            $setdata['date'] = $date.' '.date('H:i:s');
            $setdata['amount'] = $amount;
            $setdata['note'] = $note;
            $setdata['category_id'] = $category;
            $setdata['warehouse_id'] = $location;
            $setdata['purchases'] = json_encode($purchases);
            $setdata['sales'] = json_encode($sales);
            $setdata['suppliers'] = json_encode($suppliers);
            $setdata['customers'] = json_encode($customers);
            $setdata['own_company'] = $owncompany;
            $setdata['cheeque_no'] = $cheque;
            $setdata['transaction_no'] = $transferno;
            $setdata['pay_order_no'] = $payorder;

            $this->db->from('expenses');
            $this->db->where('id',$id);
            $q = $this->db->get();
            if($q->num_rows() > 0){
                $expense = $q->result()[0];
                if($paymentmethod == "cash"){
                    $this->db->from('wallets');
                    $this->db->where('id',$wallet);
                    $wq = $this->db->get();
                    if($wq->num_rows() > 0){
                        $wdata = $wq->result()[0];
                        $balanceamount = $wdata->amount+$expense->amount;
                        if($balanceamount >= $amount){

                            $this->db->set('amount', 'amount+'.$expense->amount, FALSE);
                            $this->db->where('id', $wallet);
                            $this->db->update('wallets');

                            $this->db->set('amount', 'amount-'.$amount, FALSE);
                            $this->db->where('id', $wallet);
                            $this->db->update('wallets');

                            $this->db->set($setdata);
                            $this->db->where('id',$id);
                            $this->db->update('expenses');
                            
                            $senddata['message'] = "Expense Update Successfully";
                            $senddata['status'] = true;

                        }
                        else{
                            $senddata['message'] = "Insufficient amount in your wallet";
                            
                        }
                    }
                    else{
                        $senddata['message'] = "Invalid Wallet";
                    }
                }
                else{
                    $this->db->set($setdata);
                    $this->db->where('id',$id);
                    $this->db->update('expenses');
                    $senddata['message'] = "Expense Update Successfully";
                    $senddata['status'] = true;
                }
            }
            else{
                $senddata['message'] = "Expense not found";
            }
        }
        echo json_encode($senddata);
    }
    public function delete_expense(){
        $senddata['status'] = false;
        $senddata['message'] = "Try again!";
        $id = $this->input->post('id');
        $reason = $this->input->post('reason');
        if($this->data['Owner'] || $this->data['GP']['purchases_delete_expense'] ){
            if($reason != ""){
                $this->db->from('expenses');
                $this->db->where('id',$id);
                $q = $this->db->get();
                if($q->num_rows() > 0){
                    $expense = $q->result()[0];

                    $this->db->set('amount', 'amount+'.$expense->amount, FALSE);
                    $this->db->where('id', $expense->wallet_id);
                    $this->db->update('wallets');

                    $this->db->delete('expenses', array('id' => $id));
                    $senddata['status'] = true;
                    $senddata['message'] = "Expenses delete successfully!";
                }
                else{
                    $senddata['message'] = "Expense not found";
                    }
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
    public function index($warehouse_id = null){

        $this->data['warehouses'] = $this->general_model->GetAllWarehouses();
        $this->data['suppliers'] = $this->general_model->GetAllSuppliers();


        $this->data['warehouse'] = $this->input->get('warehouse');        
        $this->data['supplier'] = $this->input->get('supplier');    


        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('purchases')));
        $meta = array('page_title' => lang('purchases'), 'bc' => $bc);
        $this->page_construct2('purchases/index', $meta, $this->data);


    }
    public function get_lists(){
        // Count Total Rows
        $this->db->from('purchases');
        $totalq = $this->db->get();
        $this->runquery_po('yes');
        $query = $this->db->get();
        $recordsFiltered = $query->num_rows();
        $this->runquery_po();
        if($_POST['length'] != -1){
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        $rows = $query->result();

        $data = array();
        foreach($rows as $row){
            $button = '<a class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light md-btn-mini" href="'.base_url("admin/purchases/view/".$row->id).'" >Detail</a>';
            $data[] = array(
                $row->id,
                $row->date,
                $row->reference_no,
                $row->supplier,
                $row->own_company,
                $row->warehouse,
                $row->grand_total,
                $row->paid,
                $row->grand_total-$row->paid,
                $row->payment_status,
                $row->created_by,
                $button
            );
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $totalq->num_rows(),
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );
        // Output to JSON format
        echo json_encode($output);
    }
    public function runquery_po($onlycoun = "no"){
        $column_search = array(
            'purchases.id',
            'purchases.date',
            'purchases.reference_no',
            'supplier.name',
            'oc.companyname',
            'w.name',
            'purchases.grand_total',
            'purchases.paid',
            'purchases.payment_status',
            'u.first_name',
            'u.last_name'
        );
        //Get Data
        if($onlycoun == "yes"){
            $this->db->select('purchases.id as id');
        }
        else{
            $this->db->select('
                purchases.id,
                purchases.date,
                purchases.reference_no,
                supplier.name as supplier,
                oc.companyname as own_company,
                w.name as warehouse,
                purchases.grand_total,
                purchases.paid,
                purchases.payment_status,
                CONCAT(u.first_name," ",u.last_name) as created_by
            ');
        }
        $this->db->from('purchases');
        $this->db->join('companies as supplier', 'supplier.id = purchases.supplier_id', 'left');
        $this->db->join('warehouses as w', 'w.id = purchases.warehouse_id', 'left');
        $this->db->join('own_companies as oc', 'oc.id = purchases.own_company', 'left');
        $this->db->join('users as u', 'u.id = purchases.created_by', 'left');
        if(isset($_POST['supplier'])){
            if($_POST['supplier'] != "all" && $_POST['supplier'] != ""){
                $this->db->where('purchases.supplier_id',$_POST['supplier']);
            }
        }
        if(isset($_POST['warehouse'])){
            if($_POST['warehouse'] != "all" && $_POST['warehouse'] != ""){
                $this->db->where('purchases.warehouse_id',$_POST['warehouse']);
            }
        }
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
                supplier.*,
                purchases.*,
                warehouses.name as warehosue_name,
                warehouses.id as warehosue_id,
                warehouses.phone as warehosue_phone,
                warehouses.email as warehosue_email,
                CONCAT(u.first_name," ",u.last_name) as created_by
            ');
            $this->db->from('purchases');
            $this->db->join('own_companies as own_companies','own_companies.id = purchases.own_company','left');
            $this->db->join('companies as supplier','supplier.id = purchases.supplier_id','left');
            $this->db->join('warehouses','warehouses.id = purchases.warehouse_id','left');
            $this->db->join('users as u', 'u.id = purchases.created_by', 'left');
            $this->db->where('purchases.id',$id);
            $q  = $this->db->get();
            if($q->num_rows() > 0){
                $this->data['purchase'] = $q->result()[0];
                $this->data['payments'] = $this->db->select('*')->from('payments')->where('purchase_id',$id)->get()->result();
                $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('purchases'), 'page' => lang('purchases')), array('link' => '#', 'page' => lang('view')));
                $meta = array('page_title' => lang('view_purchase_details'), 'bc' => $bc);
                $this->page_construct2('purchases/view', $meta, $this->data);
        

            }
            else{
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
        else{
            redirect($_SERVER['HTTP_REFERER']);
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
                $button .= '<button class="md-btn md-btn-warning md-btn-wave-light waves-effect waves-button waves-light md-btn-mini itemedit" type="button" data-id="'.$row->id.'" data-product="'.$row->product_id.'" data-expiry="'.$row->expiry.'" data-batch="'.$row->batch.'" data-qty="'.$row->quantity.'" data-panem="'.$row->product_name.'" >Edit</button>';
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
                $row->batch,
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
                p_items.*,
                products.id as product_id,
                products.name as product_name,
                products.code as barcode,
            ');
        }
        $this->db->from('purchase_items as p_items');
        $this->db->join('products as products', 'products.id = p_items.product_id', 'left');
        $this->db->where('p_items.purchase_id',$id);
    }
    public function add(){
        $this->data['owncompanies'] = $this->general_model->GetAllOwnCompanies();
        $this->data['suppliers'] = $this->general_model->GetAllSuppliers();
        $this->data['warehouses'] = $this->general_model->GetAllWarehouses();
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => 'Purchases'));
        $meta = array('page_title' => 'Purchases', 'bc' => $bc);
        $this->page_construct2('purchases/add', $meta, $this->data);
    }
    public function product($id){
        $this->db->select('
            products.*,
            units.code as unit_code,
            tax_rates.name as tax_name,
            tax_rates.rate as tax_rate,
            tax_rates.type as tax_type
        ');
        $this->db->from('products');
        $this->db->join('units','units.id = products.unit','left');
        $this->db->join('tax_rates','tax_rates.id = products.tax_rate','left');
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
        // echo '<pre>';
        // print_r($_POST);
        // exit();
        $sendvalue['status'] = false;
        $sendvalue['message'] = '';
        $reference = $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('po');
        $check_reference_already_exits = $this->purchases_model->check_reference_already_exits($reference);
        if ($check_reference_already_exits) {
            $sendvalue['message'] = "Invoie Number Already Exist" . ' (' . $reference . ')';
        }
        $date = date('Y-m-d H:i:s');
        $warehouse_id = $this->input->post('warehouse');
        $supplier_id = $this->input->post('supplier_id');
        $own_company = $this->input->post('own_company');
        $status = "received";
        $shipping = $this->input->post('shipping') ? $this->input->post('shipping') : 0;
        $supplier_details = $this->site->getCompanyByID($supplier_id);
        $supplier = $supplier_details->company != '-'  ? $supplier_details->company : $supplier_details->name;
        $note = $this->input->post('note');
        $payment_term = $this->input->post('payment_term');
        if($payment_term == ""){
            $payment_term = 1;
        }
        $due_date = $payment_term ? date('Y-m-d', strtotime('+' . $payment_term . ' days', strtotime($date))) : null;
        $total = 0;
        $product_tax = 0;
        $adv_tax_total = 0;
        $product_discount = 0;

        $product_id = $_POST['product_id'];
        $qty = $_POST['qty'];
        $batch = $_POST['batch'];
        $batch = '';
        // $expiry = $_POST['expiry'];
        $expiry = '';
        $gst_data = [];
        $items = array();
        foreach($product_id as $key => $pid){
            $product = $this->product($_POST['product_id'][$key]);
            if($product){
                $item_code = $product->code;
                $batch_number = $_POST['batch'][$key];
                // $batch_number = '';
                $check_batch_exists = $this->purchases_model->check_batch($item_code, $batch_number);
                if ($check_batch_exists &&  $status == "received") {
                    $sendvalue['message'] = "Batch Already Exist" . ' (' . $item_code . ')';
                    echo json_encode($sendvalue);
                    exit();
                }
                $discount_one = 0;
                $discount_two =  0;
                $discount_three =  0;
                $fed_tax =  0;
                $gst_tax =  $_POST['product_tax'][$key];
                $further_tax =  0;
                $adv_tax =  0;
                $item_net_cost = $this->sma->formatDecimal($product->cost);
                $item_quantity = $_POST['qty'][$key];
                $item_unit_quantity = $_POST['qty'][$key];
                $item_discount = 0;
                // $item_expiry = $_POST['expiry'][$key];
                $item_expiry = '';
                $item_option = null;
                $item_tax_rate = $_POST['product_tax'][$key];
                $unit_cost = $this->sma->formatDecimal($product->cost);
                $real_unit_cost = $this->sma->formatDecimal($product->cost);
                $item_unit = $product->unit;
                if (isset($item_code) && isset($real_unit_cost) && isset($unit_cost) && isset($item_quantity)) {
                    // $product_details = $this->purchases_model->getProductByCode($item_code);
                    // if ($item_expiry) {
                    //     $today = date('Y-m-d', time());
                    //     $new_item_expiry = substr($item_expiry, 0, 10);
                    //     $item_expiry_convert = substr($new_item_expiry, -4) . "-" . substr($new_item_expiry, 3, 2) . "-" . substr($new_item_expiry, 0, 2);
                    //     if (strtotime($item_expiry_convert) <= strtotime($today)) {
                    //         $sendvalue['message'] = lang('product_expiry_date_issue') . ' (' . $product->name . ')';
                    //         echo json_encode($sendvalue);
                    //         exit();
                    //     }
                    // }
                    $pr_discount = $this->site->calculateDiscount($item_discount, $unit_cost);
                    $unit_cost = $this->sma->formatDecimal($unit_cost);
                    $item_net_cost = $unit_cost;
                    $pr_item_discount = $this->sma->formatDecimal($pr_discount);
                    $product_discount += $pr_item_discount;
                    $pr_item_tax = $item_tax = 0;
                    $tax = "";
                    if (isset($item_tax_rate) && $item_tax_rate != 0) {
                        $tax_details = $this->site->getTaxRateByID($product->tax_rate);
                        $ctax = $this->site->calculateTax($product, $tax_details, $unit_cost);
        
                        $item_tax = $ctax['amount'];
                        $tax = $ctax['tax'];
                        if ($product->tax_method != 1) {
                            $item_net_cost = $unit_cost - $item_tax;
                        }
        
                        $pr_item_tax = $this->sma->formatDecimal(($item_tax + $fed_tax + $further_tax) * $item_unit_quantity, 4);
                    }
                    $product_tax += $pr_item_tax;
                    $adv_tax_total += $adv_tax;
                    $subtotal = (($item_net_cost * $item_unit_quantity) + $adv_tax +$pr_item_tax) - $item_discount;
                    $unit = $this->site->getUnitByID($item_unit);
                    $item = array(
                        'product_id' => $product->id,
                        'product_code' => $item_code,
                        'product_name' => $product->name,
                        'option_id' => $item_option,
                        'net_unit_cost' => $item_net_cost,
                        'unit_cost' => $this->sma->formatDecimal($item_net_cost),
                        'quantity' => $item_quantity,
                        'product_unit_id' => $item_unit,
                        'product_unit_code' => $unit->code,
                        'unit_quantity' => $item_unit_quantity,
                        'quantity_balance' => $status == 'received' ? $item_quantity : 0,
                        'quantity_received' => $status == 'received' ? $item_quantity : 0,
                        'warehouse_id' => $warehouse_id,
                        'item_tax' => $pr_item_tax,
                        'tax_rate_id' => $item_tax_rate,
                        'tax' => $tax,
                        'adv_tax' => $adv_tax,
                        'discount' => $item_discount,
                        'item_discount' => $pr_item_discount,
                        'subtotal' => $this->sma->formatDecimal($subtotal),
                        'expiry' => $item_expiry,
                        'batch' => $batch_number,
                        'price' => $product->price,
                        'dropship' => $product->dropship,
                        'crossdock' => $product->crossdock,
                        'mrp' => $product->mrp,
                        'discount_one' => $discount_one,
                        'discount_two' => $discount_two,
                        'discount_three' => $discount_three,
                        'fed_tax' => $fed_tax,
                        'gst_tax' => $gst_tax,
                        'further_tax' => $further_tax,
                        'real_unit_cost' => $real_unit_cost,
                        'date' => date('Y-m-d', strtotime($date)),
                        'status' => $status,
                        'supplier_part_no' => '',
                    );
                    if (($status == "received" && $batch_number == "")) {
                        $sendvalue['message'] = 'Please Enter Batch code (' . $product->name . ')';
                        echo json_encode($sendvalue);
                        exit();
                    }
                    $items[] = $item;
                    $total += $this->sma->formatDecimal(($item_net_cost * $item_unit_quantity), 4);
                }
            }
        }
        if (count($items) == 0) {
            $sendvalue['message'] = lang("order_items");
            echo json_encode($sendvalue);
            exit();

        }
        else {
            krsort($items);
        }

        $total_discount = 0;
        $order_discount = $total_discount;
        $order_tax = $this->site->calculateOrderTax(0, ($total + $product_tax - $order_discount));
        $total_tax = $this->sma->formatDecimal(($product_tax + $order_tax+$adv_tax_total), 4);
        $grand_total = $this->sma->formatDecimal(($total + $total_tax +$adv_tax_total+ $this->sma->formatDecimal($shipping) - $order_discount), 4);
        $data = array(
            'reference_no' => $reference,
            'date' => $date,
            'own_company' => $own_company,
            'supplier_id' => $supplier_id,
            'supplier' => $supplier,
            'warehouse_id' => $warehouse_id,
            'note' => 'Purchase Created',
            'total' => $total,
            'product_discount' => $product_discount,
            'order_discount_id' => $this->input->post('discount'),
            'order_discount' => $order_discount,
            'total_discount' => $total_discount,
            'product_tax' => $product_tax,
            'order_tax_id' => $this->input->post('order_tax'),
            'order_tax' => $order_tax,
            'total_adv_tax' => $adv_tax_total,
            'total_tax' => $total_tax,
            'shipping' => $this->sma->formatDecimal($shipping),
            'grand_total' => $grand_total,
            'status' => $status,
            'created_by' => $this->session->userdata('user_id'),
            'payment_term' => $payment_term,
            'due_date' => $due_date,
        );
        foreach ($items as $current_key => $current_array) {
            foreach ($items as $search_key => $search_array) {
                if (($search_array['product_id'] == $current_array['product_id']) && ($search_array['batch'] == $current_array['batch'])) {
                    if ($search_key != $current_key) {
                        if ($search_key > 0) {
                            $this->session->set_flashdata('error', "Duplicate Batch in same product selected" . ' (' . $reference . ')');
                            redirect($_SERVER["HTTP_REFERER"]);
                        }
                    }
                }
            }
        }
        if ($this->purchases_model->addPurchase($data, $items)) {
            $this->session->set_userdata('remove_pols', 1);
            $this->session->set_flashdata('message', );
            $sendvalue['message'] = $this->lang->line("purchase_added");
            $sendvalue['status'] = true;
        } 
        else{
            $sendvalue['message'] = 'Purchase not create';
        }
        echo json_encode($sendvalue);
    }
    public function insert_item(){
        $sendvalue['status'] = false;
        $sendvalue['message'] = '';
        $pid = $this->input->post('pid');
        $product_id = $this->input->post('product');
        $quanitty = $this->input->post('quanitty');
        $batch = $this->input->post('batch');
        // $expiry = $this->input->post('expiry');
        $expiry = '';
        $purchaseq = $this->db->select('warehouse_id')->from('purchases')->where('id',$pid)->get();
        if($purchaseq->num_rows() > 0){
            $purchase = $purchaseq->result()[0];
            $product = $this->product($product_id);
            if($product){
                $item_code = $product->code;
                $batch_number = $batch;
                $check_batch_exists = $this->purchases_model->check_batch($item_code, $batch_number);
                if ($check_batch_exists) {
                    $sendvalue['message'] = "Batch Already Exist" . ' (' . $item_code . ')';
                    echo json_encode($sendvalue);
                    exit();
                }
                $product_tax = 0;
    
                if($product->tax_type == 1){
                    $product_tax = amountformate((($product->cost/100)*$product->tax_rate));
                }
                else{
                    $product_tax = amountformate($product->tax_rate);
                }
                $discount_one = 0;
                $discount_two =  0;
                $discount_three =  0;
                $fed_tax =  0;
                $gst_tax =  $product_tax;
                $further_tax =  0;
                $adv_tax =  0;
                $item_net_cost = $this->sma->formatDecimal($product->cost);
                $item_quantity = $quanitty;
                $item_unit_quantity = $quanitty;
                $item_discount = 0;
                $item_expiry = $expiry;
                $item_option = null;
                $item_tax_rate = $product_tax;
                $unit_cost = $this->sma->formatDecimal($product->cost);
                $real_unit_cost = $this->sma->formatDecimal($product->cost);
                $item_unit = $product->unit;
                if (isset($item_code) && isset($real_unit_cost) && isset($unit_cost) && isset($item_quantity)) {
                    // if ($item_expiry) {
                    //     $today = date('Y-m-d', time());
                    //     $new_item_expiry = substr($item_expiry, 0, 10);
                    //     $item_expiry_convert = substr($new_item_expiry, -4) . "-" . substr($new_item_expiry, 3, 2) . "-" . substr($new_item_expiry, 0, 2);
                    //     if (strtotime($item_expiry_convert) <= strtotime($today)) {
                    //         $sendvalue['message'] = lang('product_expiry_date_issue') . ' (' . $product->name . ')';
                    //         echo json_encode($sendvalue);
                    //         exit();
                    //     }
                    // }
                    $pr_discount = $this->site->calculateDiscount($item_discount, $unit_cost);
                    $unit_cost = $this->sma->formatDecimal($unit_cost);
                    $item_net_cost = $unit_cost;
                    $pr_item_discount = $this->sma->formatDecimal($pr_discount);
                    $pr_item_tax = $item_tax = 0;
                    $tax = "";
                    if (isset($item_tax_rate) && $item_tax_rate != 0) {
                        $tax_details = $this->site->getTaxRateByID($product->tax_rate);
                        $ctax = $this->site->calculateTax($product, $tax_details, $unit_cost);
                        $item_tax = $ctax['amount'];
                        $tax = $ctax['tax'];
                        if ($product->tax_method != 1) {
                            $item_net_cost = $unit_cost - $item_tax;
                        }
                        $pr_item_tax = $this->sma->formatDecimal(($item_tax + $fed_tax + $further_tax) * $item_unit_quantity, 4);
                    }
                    $subtotal = (($item_net_cost * $item_unit_quantity) + $adv_tax +$pr_item_tax) - $item_discount;
                    $unit = $this->site->getUnitByID($item_unit);
                    $item = array(
                        'purchase_id' => $pid,
                        'product_id' => $product->id,
                        'product_code' => $item_code,
                        'product_name' => $product->name,
                        'option_id' => $item_option,
                        'net_unit_cost' => $item_net_cost,
                        'unit_cost' => $this->sma->formatDecimal($item_net_cost),
                        'quantity' => $item_quantity,
                        'product_unit_id' => $item_unit,
                        'product_unit_code' => $unit->code,
                        'unit_quantity' => $item_unit_quantity,
                        'quantity_balance' =>  $item_quantity,
                        'quantity_received' => $item_quantity,
                        'warehouse_id' => $purchase->warehouse_id,
                        'item_tax' => $pr_item_tax,
                        'tax_rate_id' => $item_tax_rate,
                        'tax' => $tax,
                        'adv_tax' => $adv_tax,
                        'discount' => $item_discount,
                        'item_discount' => $pr_item_discount,
                        'subtotal' => $this->sma->formatDecimal($subtotal),
                        'expiry' => $item_expiry,
                        'batch' => $batch_number,
                        'price' => $product->price,
                        'dropship' => $product->dropship,
                        'crossdock' => $product->crossdock,
                        'mrp' => $product->mrp,
                        'discount_one' => $discount_one,
                        'discount_two' => $discount_two,
                        'discount_three' => $discount_three,
                        'fed_tax' => $fed_tax,
                        'gst_tax' => $gst_tax,
                        'further_tax' => $further_tax,
                        'real_unit_cost' => $real_unit_cost,
                        'date' => date('Y-m-d'),
                        'status' => 'received',
                        'supplier_part_no' => '',
                    );
                    if (($batch_number == "")) {
                        $sendvalue['message'] = 'Please Enter Batch code (' . $product->name . ')';
                        echo json_encode($sendvalue);
                        exit();
                    }
                    $this->db->insert('purchase_items', $item);
                    $this->purchases_model->changetotal($pid);
                    $this->site->syncQuantity(NULL, $pid);
                    $this->load->model('admin/stores_model');
                    $this->stores_model->updateStoreQty($item['product_id'],$item['warehouse_id'],0,"Add Item Purchase");
                    $sendvalue['status'] = true;
                    $sendvalue['message'] = 'Add new item successfully';
                }
            }
            else{
                $sendvalue['message'] = 'Product not found';
            }
        }
        else{
            $sendvalue['message'] = 'Purchase not found';
        }
        echo json_encode($sendvalue);
    }
    public function update_item(){
        $sendvalue['status'] = false;
        $sendvalue['message'] = '';
        $piid = $this->input->post('piid');
        $product_id = $this->input->post('pid');
        $quanitty = $this->input->post('quanitty');
        $batch = $this->input->post('batch');
        // $expiry = $this->input->post('expiry');
        $expiry = '';
        $pitemq = $this->db->select('*')->from('purchase_items')->where('id',$piid)->get();
        if($pitemq->num_rows() > 0){
            $pitem = $pitemq->result()[0];
            if($pitem->quantity == $pitem->quantity_balance){
                $product = $this->product($product_id);
                if($product){
                    $item_code = $product->code;
                    $batch_number = $batch;
                    // $check_batch_exists = $this->purchases_model->check_batch($item_code, $batch_number);
                    // if ($check_batch_exists) {
                    //     $sendvalue['message'] = "Batch Already Exist" . ' (' . $item_code . ')';
                    //     echo json_encode($sendvalue);
                    //     exit();
                    // }
                    $product_tax = 0;
        
                    if($product->tax_type == 1){
                        $product_tax = amountformate((($product->cost/100)*$product->tax_rate));
                    }
                    else{
                        $product_tax = amountformate($product->tax_rate);
                    }
                    $discount_one = 0;
                    $discount_two =  0;
                    $discount_three =  0;
                    $fed_tax =  0;
                    $gst_tax =  $product_tax;
                    $further_tax =  0;
                    $adv_tax =  0;
                    $item_net_cost = $this->sma->formatDecimal($product->cost);
                    $item_quantity = $quanitty;
                    $item_unit_quantity = $quanitty;
                    $item_discount = 0;
                    $item_expiry = $expiry;
                    $item_option = null;
                    $item_tax_rate = $product_tax;
                    $unit_cost = $this->sma->formatDecimal($product->cost);
                    $real_unit_cost = $this->sma->formatDecimal($product->cost);
                    $item_unit = $product->unit;
                    if (isset($item_code) && isset($real_unit_cost) && isset($unit_cost) && isset($item_quantity)) {
                        if ($item_expiry) {
                            $today = date('Y-m-d', time());
                            $new_item_expiry = substr($item_expiry, 0, 10);
                            $item_expiry_convert = substr($new_item_expiry, -4) . "-" . substr($new_item_expiry, 3, 2) . "-" . substr($new_item_expiry, 0, 2);
                            if (strtotime($item_expiry_convert) <= strtotime($today)) {
                                $sendvalue['message'] = lang('product_expiry_date_issue') . ' (' . $product->name . ')';
                                echo json_encode($sendvalue);
                                exit();
                            }
                        }
                        $pr_discount = $this->site->calculateDiscount($item_discount, $unit_cost);
                        $unit_cost = $this->sma->formatDecimal($unit_cost);
                        $item_net_cost = $unit_cost;
                        $pr_item_discount = $this->sma->formatDecimal($pr_discount);
                        $pr_item_tax = $item_tax = 0;
                        $tax = "";
                        if (isset($item_tax_rate) && $item_tax_rate != 0) {
                            $tax_details = $this->site->getTaxRateByID($product->tax_rate);
                            $ctax = $this->site->calculateTax($product, $tax_details, $unit_cost);
                            $item_tax = $ctax['amount'];
                            $tax = $ctax['tax'];
                            if ($product->tax_method != 1) {
                                $item_net_cost = $unit_cost - $item_tax;
                            }
                            $pr_item_tax = $this->sma->formatDecimal(($item_tax + $fed_tax + $further_tax) * $item_unit_quantity, 4);
                        }
                        $subtotal = (($item_net_cost * $item_unit_quantity) + $adv_tax +$pr_item_tax) - $item_discount;
                        $item = array(
                            'net_unit_cost' => $item_net_cost,
                            'unit_cost' => $this->sma->formatDecimal($item_net_cost),
                            'quantity' => $item_quantity,
                            'unit_quantity' => $item_unit_quantity,
                            'quantity_balance' =>  $item_quantity,
                            'quantity_received' => $item_quantity,
                            'item_tax' => $pr_item_tax,
                            'tax_rate_id' => $item_tax_rate,
                            'tax' => $tax,
                            'adv_tax' => $adv_tax,
                            'discount' => $item_discount,
                            'item_discount' => $pr_item_discount,
                            'subtotal' => $this->sma->formatDecimal($subtotal),
                            'expiry' => $item_expiry,
                            'batch' => $batch_number,
                            'price' => $product->price,
                            'dropship' => $product->dropship,
                            'crossdock' => $product->crossdock,
                            'mrp' => $product->mrp,
                            'discount_one' => $discount_one,
                            'discount_two' => $discount_two,
                            'discount_three' => $discount_three,
                            'fed_tax' => $fed_tax,
                            'gst_tax' => $gst_tax,
                            'further_tax' => $further_tax,
                            'real_unit_cost' => $real_unit_cost,
                        );
                        if (($batch_number == "")) {
                            $sendvalue['message'] = 'Please Enter Batch code (' . $product->name . ')';
                            echo json_encode($sendvalue);
                            exit();
                        }
                        $this->db->set($item);
                        $this->db->where('id',$piid);
                        $this->db->update('purchase_items');
                        $this->purchases_model->changetotal($pitem->purchase_id);
                        $this->site->syncQuantity(NULL, $pitem->purchase_id);
                        $this->load->model('admin/stores_model');
                        $this->stores_model->updateStoreQty($pitem->product_id,$pitem->warehouse_id,0,"Add Item Purchase");
                        $sendvalue['status'] = true;
                        $sendvalue['message'] = 'Update new item successfully';
                    }
                }
                else{
                    $sendvalue['message'] = 'Product not found';
                }
            }
            else{
                $sendvalue['message'] = 'You cannot edit this purchase batch';
            }
        }
        else{
            $sendvalue['message'] = 'Purchase item not found';
        }
        echo json_encode($sendvalue);
    }
    public function delete_item(){
        $pi_id = $this->input->post('id');
        $pid = $this->input->post('pid');
        $sendvalue['status'] = false;
        $sendvalue['message'] = '';
        $q = $this->db->select('*')->from('purchase_items')->where('id',$pi_id)->get();
        if($q->num_rows() > 0){
            $items = $q->result();
            $item = $items[0];
            if($item->quantity == $item->quantity_balance){
                $this->db->delete('purchase_items', array('id' => $pi_id));
                $this->purchases_model->changetotal($item->purchase_id);
                $this->site->syncQuantity(NULL, NULL, $items);
                $this->load->model('admin/stores_model');
                $this->stores_model->updateStoreQty($item->product_id,$item->warehouse_id,0,"Add Item Purchase");
                $sendvalue['message'] = 'Item delete successfullly';
                $sendvalue['status'] = true;
            }
            else{
                $sendvalue['message'] = 'You cannot delete this purchase batch';
            }
        }
        else{
            $sendvalue['message'] = 'Purchase item not found';
        }
        echo json_encode($sendvalue);
    }
    public function delete($id = null){
        $this->sma->checkPermissions(null, true);
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $this->db->select('id');
        $this->db->from('purchase_items');
        $this->db->where('purchase_id',$id);
        $this->db->where('quantity != quantity_balance');
        $q = $this->db->get();
        if($q->num_rows() == 0){
            if ($this->purchases_model->deletePurchase($id)) {
                if ($this->input->is_ajax_request()) {
                    $this->sma->send_json(array('error' => 0, 'msg' => lang("purchase_deleted")));
                }
                $this->session->set_flashdata('message', lang('purchase_deleted'));
                echo '<script>alert("'.lang('purchase_deleted').'");</script>';            
            }
            else{
                echo '<script>alert("Purhcase delete failed");</script>';            
                $this->session->set_flashdata('message', "Purhcase delete failed");
            }
        }
        else{
            echo '<script>alert("You cannot delete this purchase");</script>';            
            $this->session->set_flashdata('message', "You cannot delete this purchase");
        }
        admin_redirect('purchases');
    }
    public function add_payment($id = null){
        $sendvalue['status'] = false;
        $this->load->helper('security');
        if ($this->input->post('id')) {
            $id = $this->input->post('id');
        }
        $purchase = $this->purchases_model->getPurchaseByID($id);
        if ($purchase->payment_status == 'paid' && $purchase->grand_total == $purchase->paid) {
            $sendvalue['message'] = lang("purchase_already_paid");
        }
        else{
            $this->data['inv'] = $purchase;
            $this->data['payment_ref'] = ''; //$this->site->getReference('ppay');
            $sendvalue['html'] = $this->load->view($this->theme . 'purchases/add_payment', $this->data,true);
            $sendvalue['status'] = true;
        }
        echo json_encode($sendvalue);
    }
    public function add_payment_submit($id = null){
        $this->load->helper('security');
        $id = $this->input->get('id');
        $purchase = $this->purchases_model->getPurchaseByID($id);
        if ($purchase->payment_status == 'paid' && $purchase->grand_total == $purchase->paid) {
            $this->session->set_flashdata('error', lang("purchase_already_paid"));
        }
        $this->form_validation->set_rules('amount-paid', lang("amount"), 'required');
        $this->form_validation->set_rules('paid_by', lang("paid_by"), 'required');
        if ($this->form_validation->run() == true) {
            $date = $this->input->post('date');
            $payment = array(
                'date' => $date,
                'purchase_id' => $this->input->post('purchase_id'),
                'reference_no' => $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('ppay'),
                'amount' => $this->input->post('amount-paid'),
                'paid_by' => $this->input->post('paid_by'),
                'cheque_no' => $this->input->post('cheque_no'),
                'cc_no' => $this->input->post('pcc_no'),
                'cc_holder' => $this->input->post('pcc_holder'),
                'cc_month' => $this->input->post('pcc_month'),
                'cc_year' => $this->input->post('pcc_year'),
                'cc_type' => $this->input->post('pcc_type'),
                'note' => $this->sma->clear_tags($this->input->post('note')),
                'created_by' => $this->session->userdata('user_id'),
                'type' => 'sent',
            );
        }
        elseif ($this->input->post('add_payment')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if ($this->form_validation->run() == true && $this->purchases_model->addPayment($payment)) {
            $this->session->set_flashdata('message', lang("payment_added"));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }
    public function edit_payment($id = null){
        $sendvalue['status'] = false;
        $this->load->helper('security');
        if ($this->input->post('id')) {
            $id = $this->input->post('id');
        }

        $this->data['payment'] = $this->purchases_model->getPaymentByID($id);
        $sendvalue['html'] = $this->load->view($this->theme . 'purchases/edit_payment', $this->data,true);
        $sendvalue['status'] = true;
        echo json_encode($sendvalue);
    }
    public function edit_payment_submit($id = null){

        $this->load->helper('security');
        if ($this->input->post('id')) {
            $id = $this->input->post('id');
        }

        $date = $this->input->post('date');
        $payment = array(
            'date' => $date,
            'purchase_id' => $this->input->post('purchase_id'),
            'reference_no' => $this->input->post('reference_no'),
            'amount' => $this->input->post('amount-paid'),
            'paid_by' => $this->input->post('paid_by'),
            'cheque_no' => $this->input->post('cheque_no'),
            'cc_no' => $this->input->post('pcc_no'),
            'cc_holder' => $this->input->post('pcc_holder'),
            'cc_month' => $this->input->post('pcc_month'),
            'cc_year' => $this->input->post('pcc_year'),
            'cc_type' => $this->input->post('pcc_type'),
            'note' => $this->sma->clear_tags($this->input->post('note')),
        );

        if ($this->purchases_model->updatePayment($id, $payment)) {

            $this->session->set_flashdata('message', lang("payment_updated"));
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            $this->session->set_flashdata('message', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }
    public function delete_payment($id = null){
        $this->sma->checkPermissions('delete', true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->purchases_model->deletePayment($id)) {
            //echo lang("payment_deleted");
            $this->session->set_flashdata('message', lang("payment_deleted"));
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }





    //  Old
    public function add_expense_old(){
        $this->sma->checkPermissions('expenses', true);
        $this->load->helper('security');

        //$this->form_validation->set_rules('reference', lang("reference"), 'required');
        $this->form_validation->set_rules('amount', lang("amount"), 'required');
        $this->form_validation->set_rules('userfile', lang("attachment"), 'xss_clean');
        if ($this->form_validation->run() == true) {
            $date = $this->sma->fld(trim($this->input->post('date')));

            $insertdata['date'] = $date;
            $insertdata['reference'] = $this->input->post('reference') ? $this->input->post('reference') : $this->site->getReference('ex');
            $insertdata['amount'] = $this->input->post('amount');
            $insertdata['note'] = $this->input->post('note', true);
            $insertdata['created_by'] = $this->session->userdata('user_id');
            $insertdata['category_id'] = $this->input->post('category', true);
            $insertdata['warehouse_id'] = $this->input->post('warehouse', true);
            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $insertdata['attachment'] = $photo;
            }
            $etype = $this->input->post('type');
            $insertdata['etype'] = $etype;
            if($etype == "inbound"){
                $insertdata['purchases'] = json_encode($this->input->post('pinovice'));
                $insertdata['sales'] = '';
                $insertdata['suppliers'] = json_encode($this->input->post('supplier'));
                $insertdata['customers'] = '';
            }
            else if($etype == "outbound"){
                $insertdata['purchases'] = '';
                $insertdata['sales'] = json_encode($this->input->post('sinovices'));
                $insertdata['suppliers'] = json_encode($this->input->post('supplier'));
                $insertdata['customers'] = json_encode($this->input->post('customer'));
            }
            else{
                $insertdata['purchases'] = '';
                $insertdata['sales'] = '';
                $insertdata['suppliers'] = '';
                $insertdata['customers'] = '';
            }


            $insertdata['own_company'] = $this->input->post('owncompany');
            $paymentmethod = $this->input->post('paymethod');
            $insertdata['pay_method'] = $paymentmethod;
            $insertdata['wallet_id'] = 0;
            $insertdata['cheeque_no'] = '';
            $insertdata['transaction_no'] = '';
            $insertdata['pay_order_no'] = '';

            if($paymentmethod == "cash"){
                $insertdata['wallet_id'] = $this->input->post('wallet');
                if($insertdata['wallet_id'] == ""){
                    $this->session->set_flashdata('error', 'Select Wallet');
                    redirect($_SERVER["HTTP_REFERER"]);
                    exit();
                }
                else{
                    $this->db->select('*');
                    $this->db->from('sma_wallets');
                    $this->db->where('id',$insertdata['wallet_id']);
                    $this->db->where('status','active');
                    $wq = $this->db->get();
                    if($wq->num_rows() > 0){
                        if($wq->result()[0]->amount < $insertdata['amount']){
                            $this->session->set_flashdata('error', 'Cash not available in select wallet');
                            redirect($_SERVER["HTTP_REFERER"]);
                            exit();

                        }
                    }
                    else{
                        $this->session->set_flashdata('error', 'Wallet Not Found');
                        redirect($_SERVER["HTTP_REFERER"]);
                        exit();
                    }
                }
            }
            else if($paymentmethod == "cheque"){
                $insertdata['cheeque_no'] = $this->input->post('ex_chequeno');
                if($insertdata['cheeque_no'] == ""){
                    $this->session->set_flashdata('error', 'Enter Cheque No');
                    redirect($_SERVER["HTTP_REFERER"]);
                    exit();
                }
            }
            else if($paymentmethod == "onlinetransfer"){
                $insertdata['transaction_no'] = $this->input->post('ex_transfer');
                if($insertdata['transaction_no'] == ""){
                    $this->session->set_flashdata('error', 'Enter Transaction No');
                    redirect($_SERVER["HTTP_REFERER"]);
                    exit();
                }
            }
            else if($paymentmethod == "payorder"){
                $insertdata['pay_order_no'] = $this->input->post('ex_payorder');
                if($insertdata['pay_order_no'] == ""){
                    $this->session->set_flashdata('error', 'Enter Pay Order No');
                    redirect($_SERVER["HTTP_REFERER"]);
                    exit();
                }
            }
        } elseif ($this->input->post('add_expense')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == true && $this->purchases_model->addExpense($insertdata)) {
            if($this->input->post('paymethod') == "cash"){
                $wid = $this->input->post('wallet');
                $tinsert['user_id'] = $this->session->userdata('user_id');
                $tinsert['wallet_id'] = $wid;
                $tinsert['amount'] = $this->input->post('amount');
                $tinsert['type'] = '1';
                $this->db->insert('sma_wallet_transations',$tinsert);
                $this->db->set('amount', 'amount-'.$tinsert['amount'], FALSE);
                $this->db->where('id', $tinsert['wallet_id']);
                $this->db->update('sma_wallets');
            }
            $this->session->set_flashdata('message', lang("expense_added"));
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['exnumber'] = ''; //$this->site->getReference('ex');
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['own_company'] = $this->site->getAllown_companies();
            $this->data['categories'] = $this->purchases_model->getExpenseCategories();
            $this->data['modal_js'] = $this->site->modal_js();
            $this->db->select('*');
            $this->db->from('sma_wallets');
            $this->db->where('status','active');
            $q = $this->db->get();
            $this->data['wallets'] = $q->result();
            // $this->load->view($this->theme . 'purchases/add_expense', $this->data);
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('system_settings/add_expense'), 'page' => lang('Add Expense')), array('link' => '#', 'page' => lang('Add Expense')));
            $meta = array('page_title' => lang('Add Expense'), 'bc' => $bc);
            $this->page_construct('purchases/add_expense', $meta, $this->data);
        }
    }
    public function get_purchase_for_group_13($warehouse_id = null){
        $detail_link = anchor('admin/purchases/view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('purchase_details'));
        $payments_link = anchor('admin/purchases/payments/$1', '<i class="fa fa-money"></i> ' . lang('view_payments'), 'data-toggle="modal" data-target="#myModal"');
        $add_payment_link = anchor('admin/purchases/add_payment/$1', '<i class="fa fa-money"></i> ' . lang('add_payment'), 'data-toggle="modal" data-target="#myModal"');
        $email_link = anchor('admin/purchases/email/$1', '<i class="fa fa-envelope"></i> ' . lang('email_purchase'), 'data-toggle="modal" data-target="#myModal"');
        $edit_link = anchor('admin/purchases/edit/$1', '<i class="fa fa-edit"></i> ' . lang('edit_purchase'));
        $pdf_link = anchor('admin/purchases/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $print_barcode = anchor('admin/products/print_barcodes/?purchase=$1', '<i class="fa fa-print"></i> ' . lang('print_barcodes'));

        $action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
                <ul class="dropdown-menu pull-right" role="menu">
                    <li>' . $detail_link . '</li>
                    <li>' . $payments_link . '</li>
                    <li>' . $add_payment_link . '</li>
                    <li>' . $edit_link . '</li>
                    <li>' . $pdf_link . '</li>
                    <li>' . $email_link . '</li>
                    <li>' . $print_barcode . '</li>
                </ul>
            </div></div>';

        $this->load->library('datatables');
        $this->datatables->select("
            purchases.id, 
            DATE_FORMAT(date, '%Y-%m-%d') as date, 
            purchases.reference_no, 
            purchases.supplier,
            sma_warehouses.name,
            sma_own_companies.companyname, 
            purchases.status, 
            grand_total, 
            purchases.paid, 
            (grand_total-paid) as balance, 
            purchases.payment_status, 
            purchases.attachment
        ");
        $this->datatables->from('purchases');
        $this->datatables->join('sma_warehouses','sma_warehouses.id = purchases.warehouse_id','left');
        $this->datatables->join('sma_own_companies','sma_own_companies.id = purchases.own_company','left');

        if ($warehouse_id) {
                $this->datatables->where('purchases.warehouse_id', $warehouse_id);
        }
        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            $this->datatables->where('purchases.created_by', $this->session->userdata('user_id'));
        } elseif ($this->Supplier) {
            $this->datatables->where('purchases.supplier_id', $this->session->userdata('user_id'));
        }
        $this->datatables->where('purchases.supplier_id', '5');
        $this->datatables->add_column("Actions", $action, "purchases.id");
        echo $this->datatables->generate();
    }
    public function getPurchases($warehouse_id = null){
        $this->sma->checkPermissions('index');

        if ((!$this->Owner || !$this->Admin) && !$warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }

        if ($_SESSION['group_id'] === '13' || $_SESSION['group_id'] === 13) {
            $this->get_purchase_for_group_13($warehouse_id = null);
        } else {
            $detail_link = anchor('admin/purchases/view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('purchase_details'));
            $payments_link = anchor('admin/purchases/payments/$1', '<i class="fa fa-money"></i> ' . lang('view_payments'), 'data-toggle="modal" data-target="#myModal"');
            $add_payment_link = anchor('admin/purchases/add_payment/$1', '<i class="fa fa-money"></i> ' . lang('add_payment'), 'data-toggle="modal" data-target="#myModal"');
            $email_link = anchor('admin/purchases/email/$1', '<i class="fa fa-envelope"></i> ' . lang('email_purchase'), 'data-toggle="modal" data-target="#myModal"');
            $edit_link = anchor('admin/purchases/edit/$1', '<i class="fa fa-edit"></i> ' . lang('edit_purchase'));
            $pdf_link = anchor('admin/purchases/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
            $rpdf_link = anchor('admin/purchases/pdf_return/$1', '<i class="fa fa-file-pdf-o"></i> Purchase Return PDF');
            $print_barcode = anchor('admin/products/print_barcodes/?purchase=$1', '<i class="fa fa-print"></i> ' . lang('print_barcodes'));
            $return_link = anchor('admin/purchases/purchase_return/$1', '<i class="fa fa-angle-double-left"></i> ' . lang('return_purchase'));
            $purchacereturn = anchor('admin/purchases/purchase_return/$1', '<i class="fa fa-undo"></i> Purchase Return');
            $delete_link = "<a href='#' class='po' title='<b>" . $this->lang->line("delete_purchase") . "</b>' data-content=\"<p>"
                . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . admin_url('purchases/delete/$1') . "'>"
                . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
                . lang('delete_purchase') . "</a>";
            $action = '<div class="text-center"><div class="btn-group text-left">'
                . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
                . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
            <li>' . $detail_link . '</li>
            <li>' . $return_link . '</li>
            <li>' . $payments_link . '</li>
            <li>' . $add_payment_link . '</li>
            <li>' . $edit_link . '</li>
            <li>' . $pdf_link . '</li>
            <li>' . $rpdf_link . '</li>
            <li>' . $email_link . '</li>
            <li>' . $print_barcode . '</li>
            <li>' . $delete_link . '</li>
        </ul>
         </div></div>';

            $this->load->library('datatables');
            $this->datatables->select("
                purchases.id, 
                DATE_FORMAT(date, '%Y-%m-%d') as date, 
                purchases.reference_no, 
                purchases.supplier,
                sma_warehouses.name,
                sma_own_companies.companyname, 
                purchases.status, 
                grand_total, 
                purchases.paid, 
                (grand_total-paid) as balance, 
                purchases.payment_status, 
                purchases.attachment
            ");
            $this->datatables->from('purchases');
            $this->datatables->join('sma_warehouses','sma_warehouses.id = purchases.warehouse_id','left');
            $this->datatables->join('sma_own_companies','sma_own_companies.id = purchases.own_company','left');
            if ($warehouse_id) {
                $this->datatables->where('purchases.warehouse_id', $warehouse_id);
            }
            if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
                $this->datatables->where('purchases.created_by', $this->session->userdata('user_id'));
            } elseif ($this->Supplier) {
                $this->datatables->where('purchases.supplier_id', $this->session->userdata('user_id'));
            }
            $this->datatables->add_column("Actions", $action, "purchases.id");
            echo $this->datatables->generate();
        }
    }
    public function modal_view($purchase_id = null){
        $this->sma->checkPermissions('index', true);

        if ($this->input->get('id')) {
            $purchase_id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->purchases_model->getPurchaseByID($purchase_id);
        if (!$this->session->userdata('view_right')) {
            $this->sma->view_rights($inv->created_by, true);
        }
        $this->data['rows'] = $this->purchases_model->getAllPurchaseItems($purchase_id);
        $this->data['supplier'] = $this->site->getCompanyByID($inv->supplier_id);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        $this->data['payments'] = $this->purchases_model->getPaymentsForPurchase($purchase_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : null;
        $this->data['return_purchase'] = $inv->return_id ? $this->purchases_model->getPurchaseByID($inv->return_id) : NULL;
        $this->data['return_rows'] = $inv->return_id ? $this->purchases_model->getAllPurchaseItems($inv->return_id) : NULL;

        $this->load->view($this->theme . 'purchases/modal_view', $this->data);
    }
    public function pdf($purchase_id = null, $view = null, $save_bufffer = null){
        $this->sma->checkPermissions();

        if ($this->input->get('id')) {
            $purchase_id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->purchases_model->getPurchaseByID($purchase_id);
        if (!$this->session->userdata('view_right')) {
            $this->sma->view_rights($inv->created_by);
        }

        $this->data['own_company'] = $this->purchases_model->getAllPurchaseItemsCompany($inv->own_company);
        $this->data['rows'] = $this->purchases_model->getAllPurchaseItems($purchase_id);
        $this->data['supplier'] = $this->site->getCompanyByID($inv->supplier_id);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['inv'] = $inv;
        $this->data['return_purchase'] = $inv->return_id ? $this->purchases_model->getPurchaseByID($inv->return_id) : NULL;
        $this->data['return_rows'] = $inv->return_id ? $this->purchases_model->getAllPurchaseItems($inv->return_id) : NULL;
        $name = $this->lang->line("purchase") . "_" . str_replace('/', '_', $inv->reference_no) . ".pdf";
        $html = $this->load->view($this->theme . 'purchases/pdf', $this->data, true);
        if (!$this->Settings->barcode_img) {
            $html = preg_replace("'\<\?xml(.*)\?\>'", '', $html);
        }
        if ($view) {
            echo $html;
            die();
        } elseif ($save_bufffer) {
            return $this->sma->generate_pdf($html, $name, $save_bufffer);
        } else {
            $this->sma->generate_pdf($html, $name);
        }
    }
    public function pdf_return($purchase_id = null, $view = null, $save_bufffer = null){

        if ($this->input->get('id')) {
            $purchase_id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->purchases_model->getPurchaseByID($purchase_id);
        if (!$this->session->userdata('view_right')) {
            $this->sma->view_rights($inv->created_by);
        }

        $this->data['own_company'] = $this->purchases_model->getAllPurchaseItemsCompany($inv->own_company);
        // $this->data['rows'] = $this->purchases_model->getAllPurchaseItems($purchase_id);
        $this->data['rows'] = $this->purchases_model->returnItems($purchase_id);
        $this->data['supplier'] = $this->site->getCompanyByID($inv->supplier_id);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['inv'] = $inv;
        $name = $this->lang->line("purchase") . "_Return_" . str_replace('/', '_', $inv->reference_no) . ".pdf";
        $html = $this->load->view($this->theme . 'purchases/pdf_return', $this->data, true);
        // echo $html;
        // print_r($this->data['rows']);
        // exit();
        if (!$this->Settings->barcode_img) {
            echo $html = preg_replace("'\<\?xml(.*)\?\>'", '', $html);

        }
        if ($view) {
            echo $html;
            die();
        } elseif ($save_bufffer) {
            return $this->sma->generate_pdf($html, $name, $save_bufffer);
        } else {
            $this->sma->generate_pdf($html, $name);
        }
    }
    public function combine_pdf($purchases_id){
        $this->sma->checkPermissions('pdf');
        foreach ($purchases_id as $purchase_id) {
            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
            $inv = $this->purchases_model->getPurchaseByID($purchase_id);
            if (!$this->session->userdata('view_right')) {
                $this->sma->view_rights($inv->created_by);
            }
            $this->data['rows'] = $this->purchases_model->getAllPurchaseItems($purchase_id);
            $this->data['supplier'] = $this->site->getCompanyByID($inv->supplier_id);
            $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
            $this->data['created_by'] = $this->site->getUser($inv->created_by);
            $this->data['inv'] = $inv;
            $this->data['return_purchase'] = $inv->return_id ? $this->purchases_model->getPurchaseByID($inv->return_id) : NULL;
            $this->data['return_rows'] = $inv->return_id ? $this->purchases_model->getAllPurchaseItems($inv->return_id) : NULL;
            $inv_html = $this->load->view($this->theme . 'purchases/pdf', $this->data, true);
            if (!$this->Settings->barcode_img) {
                $inv_html = preg_replace("'\<\?xml(.*)\?\>'", '', $inv_html);
            }
            $html[] = array(
                'content' => $inv_html,
                'footer' => '',
            );
        }

        $name = lang("purchases") . ".pdf";
        $this->sma->generate_pdf($html, $name);
    }
    public function email($purchase_id = null){
        $this->sma->checkPermissions(false, true);

        if ($this->input->get('id')) {
            $purchase_id = $this->input->get('id');
        }
        $inv = $this->purchases_model->getPurchaseByID($purchase_id);
        $this->form_validation->set_rules('to', $this->lang->line("to") . " " . $this->lang->line("email"), 'trim|required|valid_email');
        $this->form_validation->set_rules('subject', $this->lang->line("subject"), 'trim|required');
        $this->form_validation->set_rules('cc', $this->lang->line("cc"), 'trim|valid_emails');
        $this->form_validation->set_rules('bcc', $this->lang->line("bcc"), 'trim|valid_emails');
        $this->form_validation->set_rules('note', $this->lang->line("message"), 'trim');

        if ($this->form_validation->run() == true) {
            if (!$this->session->userdata('view_right')) {
                $this->sma->view_rights($inv->created_by);
            }
            $to = $this->input->post('to');
            $subject = $this->input->post('subject');
            if ($this->input->post('cc')) {
                $cc = $this->input->post('cc');
            } else {
                $cc = null;
            }
            if ($this->input->post('bcc')) {
                $bcc = $this->input->post('bcc');
            } else {
                $bcc = null;
            }
            $supplier = $this->site->getCompanyByID($inv->supplier_id);
            $this->load->library('parser');
            $parse_data = array(
                'reference_number' => $inv->reference_no,
                'contact_person' => $supplier->name,
                'company' => $supplier->company,
                'site_link' => base_url(),
                'site_name' => $this->Settings->site_name,
                'logo' => '<img src="' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>',
            );
            $msg = $this->input->post('note');
            $message = $this->parser->parse_string($msg, $parse_data);
            $attachment = $this->pdf($purchase_id, null, 'S');

            try {
                if ($this->sma->send_email($to, $subject, $message, null, null, $attachment, $cc, $bcc)) {
                    delete_files($attachment);
                    $this->db->update('purchases', array('status' => 'ordered'), array('id' => $purchase_id));
                    $this->session->set_flashdata('message', $this->lang->line("email_sent"));
                    admin_redirect("purchases");
                }
            } catch (Exception $e) {
                $this->session->set_flashdata('error', $e->getMessage());
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } elseif ($this->input->post('send_email')) {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->session->set_flashdata('error', $this->data['error']);
            redirect($_SERVER["HTTP_REFERER"]);
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            if (file_exists('./themes/' . $this->Settings->theme . '/admin/views/email_templates/purchase.html')) {
                $purchase_temp = file_get_contents('themes/' . $this->Settings->theme . '/admin/views/email_templates/purchase.html');
            } else {
                $purchase_temp = file_get_contents('./themes/default/admin/views/email_templates/purchase.html');
            }
            $this->data['subject'] = array(
                'name' => 'subject',
                'id' => 'subject',
                'type' => 'text',
                'value' => $this->form_validation->set_value('subject', lang('purchase_order') . ' (' . $inv->reference_no . ') ' . lang('from') . ' ' . $this->Settings->site_name),
            );
            $this->data['note'] = array(
                'name' => 'note',
                'id' => 'note',
                'type' => 'text',
                'value' => $this->form_validation->set_value('note', $purchase_temp),
            );
            $this->data['supplier'] = $this->site->getCompanyByID($inv->supplier_id);

            $this->data['id'] = $purchase_id;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'purchases/email', $this->data);
        }
    }
    public function add_zero_payment_after_purchase_add($data){
        $purchase_id = $this->db->query("SELECT id + 1 as purchase_id FROM `sma_purchases` ORDER BY id DESC LIMIT 1")->result_array();
        $insertIntoPayments = [
            'date' => date('Y-m-d'),
            'purchase_id' => $purchase_id[0]['purchase_id'],
            'reference_no' => $data['reference_no'],
            'paid_by' => 'cash',
            'amount' => 0,
            'created_by' => $_SESSION['user_id'],
            'type' => 'pending'
        ];
        $this->db->insert('sma_payments', $insertIntoPayments);
    }
    public function edit($id = null){
        $this->sma->checkPermissions();

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $inv = $this->purchases_model->getPurchaseByID($id);

        if ($inv->status == 'returned' || $inv->return_id || $inv->return_purchase_ref) {
            $this->session->set_flashdata('error', lang('purchase_x_action'));
            admin_redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
        }
        if (!$this->session->userdata('edit_right')) {
            $this->sma->view_rights($inv->created_by);
        }
        $this->form_validation->set_message('is_natural_no_zero', $this->lang->line("no_zero_required"));
        $this->form_validation->set_rules('reference_no', $this->lang->line("ref_no"), 'required');
        $this->form_validation->set_rules('warehouse', $this->lang->line("warehouse"), 'required|is_natural_no_zero');
        $this->form_validation->set_rules('supplier', $this->lang->line("supplier"), 'required');

        $this->session->unset_userdata('csrf_token');
        if ($this->form_validation->run() == true) {

            $reference = $this->input->post('reference_no');

            // $check_reference_already_exits = $this->purchases_model->check_reference_already_exits($reference);

            // if ($check_reference_already_exits) {
            //     $this->session->set_flashdata('error', "Invoie Number Already Exist" . ' (' . $reference . ')');
            //     redirect($_SERVER["HTTP_REFERER"]);
            // }

            if ($this->Owner || $this->Admin) {
                $date = $this->sma->fld(trim($this->input->post('date')));
            } else {
                $date = $inv->date;
            }
            $warehouse_id = $this->input->post('warehouse');
            $supplier_id = $this->input->post('supplier');
            $status = $this->input->post('status');
            $own_company = $this->input->post('own_company');
            $shipping = $this->input->post('shipping') ? $this->input->post('shipping') : 0;
            $supplier_details = $this->site->getCompanyByID($supplier_id);
            $supplier = $supplier_details->company != '-'  ? $supplier_details->company : $supplier_details->name;
            $note = $this->sma->clear_tags($this->input->post('note'));
            $payment_term = $this->input->post('payment_term');
            $due_date = $payment_term ? date('Y-m-d', strtotime('+' . $payment_term . ' days', strtotime($date))) : null;

            $total = 0;
            $adv_tax_total = 0;
            $product_tax = 0;
            $product_discount = 0;
            $partial = false;
            $i = sizeof($_POST['product']);
            $gst_data = [];
            $total_cgst = $total_sgst = $total_igst = 0;
            $counter = sizeof($_POST['product']);
            for ($r = 0; $r < $i; $r++) {
                $item_code = $_POST['product'][$r];

                $mystring = $_POST['batch_number'][$r];
                $findme   = 'B#-';
                $pos = strpos($mystring, $findme);
                $check_batch_exists = $this->purchases_model->check_batch($item_code, $mystring);

                // Note our use of ===.  Simply == would not work as expected
                // because the position of 'a' was the 0th (first) character.
                if ($pos === false) {
                    //echo "The string '$findme' was not found in the string '$mystring'";
                    //$batch_number = "B#-".$_POST['batch_number'][$r];

                    if ($check_batch_exists &&  $status == "received") {
                        $this->session->set_flashdata('error', "Batch Already Exist" . ' (' . $item_code . ')');
                        redirect($_SERVER["HTTP_REFERER"]);
                    } else {
                        $this->session->set_flashdata('error', "Batch start with B#- in batch" . ' (' . $item_code . ')');
                        redirect($_SERVER["HTTP_REFERER"]);
                    }
                } else {
                    $batch_number = $_POST['batch_number'][$r];

                    // if ($check_batch_exists &&  $status == "received") {
                    //     $this->session->set_flashdata('error', "Batch Already Exist" . ' (' . $item_code . ')');
                    //     redirect($_SERVER["HTTP_REFERER"]);
                    // } else {
                    //     $batch_number = $_POST['batch_number'][$r];
                    // }
                }

                // $check_batch_exists = $this->purchases_model->check_batch_already_sale($item_code, $batch_number);

                // if ($check_batch_exists &&  $status == "received") {
                //     $this->session->set_flashdata('error', "Batch Already Sales" . ' (' . $item_code . ')');
                //     redirect($_SERVER["HTTP_REFERER"]);
                // }

                //$batch_number = "B#".$_POST['batch_number'][$r];
                $price = $_POST['purchase_price'][$r];
                $dropship = $_POST['purchase_dropship'][$r];
                $crossdock = $_POST['purchase_crossdock'][$r];
                $mrp = $_POST['purchase_mrp'][$r];
                $discount_one = $_POST['discount_one_' . $counter];
                $discount_two =  $_POST['discount_two_' . $counter];
                $discount_three =  $_POST['discount_three_' . $counter];
                $fed_tax =  $_POST['fed_tax_' . $counter];
                $gst_tax =  $_POST['gst_tax_' . $counter];
                $further_tax =  $_POST['further_tax_' . $counter];
                $adv_tax =  $_POST['advtax' . $counter];
                $item_net_cost = $this->sma->formatDecimal($_POST['net_cost'][$r]);
                $unit_cost = $this->sma->formatDecimal($_POST['unit_cost'][$r]);
                $real_unit_cost = $this->sma->formatDecimal($_POST['real_unit_cost'][$r]);
                $item_unit_quantity = $_POST['quantity'][$r];
                $quantity_received = $_POST['received_base_quantity'][$r];
                $item_option = isset($_POST['product_option'][$r]) && $_POST['product_option'][$r] != 'false' ? $_POST['product_option'][$r] : null;
                $item_tax_rate = isset($_POST['product_tax'][$r]) ? $_POST['product_tax'][$r] : null;
                $item_discount = isset($_POST['product_discount'][$r]) ? $_POST['product_discount'][$r] : null;
                $item_expiry = (isset($_POST['expiry'][$r]) && !empty($_POST['expiry'][$r])) ? $this->sma->fldcalender(trim($_POST['expiry'][$r])) : null;


                $product_details = $this->purchases_model->getProductByCode($item_code);


                $check = $this->purchases_model->check_sales($product_details->id, $batch_number);

                // //var_dump($check);

                if ($check > 0) {
                    //echo "True";
                    $this->session->set_flashdata('error', "Sorry you can not update purchase because " . $product_details->name . " already sale");
                    // $this->session->set_flashdata('error', $product_details->name . " Batch : ".$batch_number." already sale");
                    redirect($_SERVER["HTTP_REFERER"]);
                } else {
                    //echo "False";
                }

                //$this->sma->print_arrays($data, $products);


                if ($item_expiry) {
                    $date = $this->sma->fld(trim($this->input->post('date')));
                    $today = date('Y-m-d', time());
                    $new_item_expiry = substr($item_expiry, 0, 10);
                    $item_expiry_convert = substr($new_item_expiry, -4) . "-" . substr($new_item_expiry, 3, 2) . "-" . substr($new_item_expiry, 0, 2);
                }


                $supplier_part_no = (isset($_POST['part_no'][$r]) && !empty($_POST['part_no'][$r])) ? $_POST['part_no'][$r] : null;
                $quantity_balance = $_POST['quantity_balance'][$r];
                $ordered_quantity = $_POST['ordered_quantity'][$r];
                $item_unit = $_POST['product_unit'][$r];
                $item_quantity = $_POST['product_base_quantity'][$r];

                if ($status == 'received' || $status == 'partial') {
                    if ($quantity_received < $item_quantity) {
                        $partial = 'partial';
                    } elseif ($quantity_received > $item_quantity) {
                        $this->session->set_flashdata('error', lang("received_more_than_ordered"));
                        redirect($_SERVER["HTTP_REFERER"]);
                    }
                    $balance_qty =  $quantity_received - ($ordered_quantity - $quantity_balance);
                } else {
                    $balance_qty = $item_quantity;
                    $quantity_received = $item_quantity;
                }
                if (isset($item_code) && isset($real_unit_cost) && isset($unit_cost) && isset($item_quantity) && isset($quantity_balance)) {
                    $product_details = $this->purchases_model->getProductByCode($item_code);
                    $pr_discount = $this->site->calculateDiscount($item_discount, $unit_cost);
                    $unit_cost = $this->sma->formatDecimal($unit_cost);
                    $item_net_cost = $unit_cost;
                    $pr_item_discount = $this->sma->formatDecimal($pr_discount);
                    $product_discount += $pr_item_discount;
                    $pr_item_tax = $item_tax = 0;
                    $tax = "";

                    if (isset($item_tax_rate) && $item_tax_rate != 0) {

                        $tax_details = $this->site->getTaxRateByID($item_tax_rate);
                        $ctax = $this->site->calculateTax($product_details, $tax_details, $unit_cost);
                        $item_tax = $ctax['amount'];
                        $tax = $ctax['tax'];
                        if ($product_details->tax_method != 1) {
                            $item_net_cost = $unit_cost - $item_tax;
                        }
                        $pr_item_tax = $this->sma->formatDecimal(($item_tax + $fed_tax + $further_tax) * $item_unit_quantity, 4);
                        if ($this->Settings->indian_gst && $gst_data = $this->gst->calculteIndianGST($pr_item_tax, ($this->Settings->state == $supplier_details->state), $tax_details)) {
                            $total_cgst += $gst_data['cgst'];
                            $total_sgst += $gst_data['sgst'];
                            $total_igst += $gst_data['igst'];
                        }
                    }

                    // New Tax System


                    $product_tax += $pr_item_tax;
                    $adv_tax_total += $adv_tax;
                    $subtotal = (($item_net_cost * $item_unit_quantity) + $adv_tax +$pr_item_tax);
                    $unit = $this->site->getUnitByID($item_unit);

                    $item = array(
                        'product_id' => $product_details->id,
                        'product_code' => $item_code,
                        'product_name' => $product_details->name,
                        'option_id' => $item_option,
                        'net_unit_cost' => $item_net_cost,
                        'unit_cost' => $this->sma->formatDecimal($item_net_cost + $item_tax),
                        'quantity' => $item_quantity,
                        'product_unit_id' => $item_unit,
                        'product_unit_code' => $unit->code,
                        'unit_quantity' => $item_unit_quantity,
                        'quantity_balance' => $balance_qty,
                        'quantity_received' => $quantity_received,
                        'warehouse_id' => $warehouse_id,
                        'item_tax' => $pr_item_tax,
                        'tax_rate_id' => $item_tax_rate,
                        'tax' => $tax,
                        'adv_tax' => $adv_tax,
                        'discount' => $item_discount,
                        'item_discount' => $pr_item_discount,
                        'subtotal' => $this->sma->formatDecimal($subtotal),
                        'expiry' => $item_expiry,
                        'real_unit_cost' => $real_unit_cost,
                        'supplier_part_no' => $supplier_part_no,
                        'date' => date('Y-m-d'),
                        'batch' => $batch_number,
                        'discount_one' => $discount_one,
                        'discount_two' => $discount_two,
                        'discount_three' => $discount_three,
                        'fed_tax' => $fed_tax,
                        'gst_tax' => $gst_tax,
                        'further_tax' => $further_tax,
                        'price' => $this->sma->formatDecimal($price),
                        'dropship' => $this->sma->formatDecimal($dropship),
                        'crossdock' => $this->sma->formatDecimal($crossdock),
                        'mrp' => $this->sma->formatDecimal($mrp),

                    );

                    if ((($status == "received" || $status == "partial")  && $item['batch'] == "")) {
                        $this->form_validation->set_rules('batch', lang(""), 'required');
                    }

                    $items[] = ($item + $gst_data);
                    $total += $item_net_cost * $item_unit_quantity;
                }
                $counter--;
            }

            if (empty($items)) {
                $this->form_validation->set_rules('product', lang("order_items"), 'required');
            } else {
                foreach ($items as $item) {
                    $item["status"] = ($status == 'partial') ? 'received' : $status;
                    $products[] = $item;
                }
                krsort($products);
            }



            $order_discount = $this->site->calculateDiscount($this->input->post('discount'), ($total + $product_tax));
            $total_discount = $this->sma->formatDecimal(($order_discount + $product_discount), 4);
            $order_tax = $this->site->calculateOrderTax($this->input->post('order_tax'), ($total + $product_tax - $order_discount));
            $total_tax = $this->sma->formatDecimal(($product_tax + $adv_tax_total + $order_tax), 4);
            $grand_total = $this->sma->formatDecimal(($total + $total_tax + $adv_tax_total +  $this->sma->formatDecimal($shipping) - $order_discount), 4);
            $data = array(
                'reference_no' => $reference,
                'supplier_id' => $supplier_id,
                'supplier' => $supplier,
                'warehouse_id' => $warehouse_id,
                'own_company' => $own_company,
                'note' => $note,
                'total' => $total,
                'product_discount' => $product_discount,
                'order_discount_id' => $this->input->post('discount'),
                'order_discount' => $order_discount,
                'total_discount' => $total_discount,
                'product_tax' => $product_tax,
                'order_tax_id' => $this->input->post('order_tax'),
                'order_tax' => $order_tax,
                'total_adv_tax' => $adv_tax_total,
                'total_tax' => $total_tax,
                'shipping' => $this->sma->formatDecimal($shipping),
                'grand_total' => $grand_total,
                'status' => $status,
                'updated_by' => $this->session->userdata('user_id'),
                'updated_at' => date('Y-m-d H:i:s'),
                'payment_term' => $payment_term,
                'due_date' => $due_date,
            );
            if ($date) {
                // $data['date'] = $date;
            }
            if ($this->Settings->indian_gst) {
                $data['cgst'] = $total_cgst;
                $data['sgst'] = $total_sgst;
                $data['igst'] = $total_igst;
            }

            if ($_FILES['document']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment'] = $photo;
            }

            foreach ($products as $current_key => $current_array) {
                foreach ($products as $search_key => $search_array) {
                    if (($search_array['product_id'] == $current_array['product_id']) && ($search_array['batch'] == $current_array['batch'])) {
                        if ($search_key != $current_key) {
                            if ($search_key > 0) {
                                $this->session->set_flashdata('error', "Duplicate Batch in same product selected" . ' (' . $reference . ')');
                                redirect($_SERVER["HTTP_REFERER"]);
                            }
                        }
                    }
                }
            }

            // $this->sma->print_arrays($data, $product, $items);

        }

        if ($this->form_validation->run() == true && $this->purchases_model->updatePurchase($id, $data, $products)) {
            $this->session->set_userdata('remove_pols', 1);
            $this->session->set_flashdata('message', $this->lang->line("purchase_added"));
            admin_redirect('purchases');
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['inv'] = $inv;
            if ($this->Settings->disable_editing) {
                if ($this->data['inv']->date <= date('Y-m-d', strtotime('-' . $this->Settings->disable_editing . ' days'))) {
                    $this->session->set_flashdata('error', sprintf(lang("purchase_x_edited_older_than_x_days"), $this->Settings->disable_editing));
                    redirect($_SERVER["HTTP_REFERER"]);
                }
            }
            $inv_items = $this->purchases_model->getAllPurchaseItems($id);
            $c = rand(100000, 9999999);
            foreach ($inv_items as $item) {
                $row = $this->site->getProductByID($item->product_id);
                $row->expiry = (($item->expiry && $item->expiry != '0000-00-00') ? $this->sma->hrsd($item->expiry) : '');
                $row->base_quantity = $item->quantity;
                $row->base_unit = $row->unit ? $row->unit : $item->product_unit_id;
                $row->base_unit_cost = $row->cost ? $row->cost : $item->unit_cost;
                $row->unit = $item->product_unit_id;
                $row->qty = $item->unit_quantity;
                $row->oqty = $item->quantity;
                $row->supplier_part_no = $item->supplier_part_no;
                $row->received = $item->quantity_received ? $item->quantity_received : $item->quantity;
                $row->quantity_balance = $item->quantity_balance + ($item->quantity - $row->received);
                $row->discount = $item->discount ? $item->discount : '0';
                $options = $this->purchases_model->getProductOptions($row->id);
                $row->option = $item->option_id;
                $row->real_unit_cost = $item->real_unit_cost;
                $row->cost = $this->sma->formatDecimal($item->net_unit_cost + ($item->item_discount / $item->quantity));
                $row->tax_rate = $item->tax_rate_id;
                $row->discount_one = $item->discount_one;
                $row->discount_two = $item->discount_two;
                $row->discount_three = $item->discount_three;
                $row->fed_tax_rate = $item->fed_tax;
                $row->gst_tax_rate = $item->gst_tax;
                $row->further_tax_rate = $item->further_tax;
                $row->batch = $item->batch;
                $row->expiry = $item->expiry;
                $row->new_consiment = $row->price;
                $row->consiment = $item->price;
                $row->new_dropship = $row->dropship;
                $row->dropship = $item->dropship;
                $row->new_crossdock = $row->crossdock;
                $row->crossdock = $item->crossdock;
                $row->new_mrp = $row->mrp;
                $row->mrp = $item->mrp;
                $row->discount_one_checked = ($item->discount_one != null) ? "true" : "false";
                $row->discount_two_checked = ($item->discount_two != null) ? "true" : "false";
                $row->discount_three_checked = ($item->discount_three != null) ? "true" : "false";
                $units = $this->site->getUnitsByBUID($row->base_unit);
                $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                $ri = $this->Settings->item_addition ? $row->id : $c;
                $pr[$ri] = array(
                    'id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")",
                    'row' => $row, 'tax_rate' => $tax_rate, 'units' => $units, 'options' => $options
                );
                $c++;
            }
            $this->data['inv_items'] = json_encode($pr);
            $this->data['id'] = $id;
            $this->data['suppliers'] = $this->site->getAllCompanies('supplier');
            $this->data['purchase'] = $this->purchases_model->getPurchaseByID($id);
            $this->data['categories'] = $this->site->getAllCategories();
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['own_company'] = $this->site->getAllown_companies();
            $this->load->helper('string');
            $value = random_string('alnum', 20);
            $this->session->set_userdata('user_csrf', $value);
            $this->session->set_userdata('remove_pols', 1);
            $this->data['csrf'] = $this->session->userdata('user_csrf');
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('purchases'), 'page' => lang('purchases')), array('link' => '#', 'page' => lang('edit_purchase')));
            $meta = array('page_title' => lang('edit_purchase'), 'bc' => $bc);
            $this->page_construct('purchases/edit', $meta, $this->data);
        }
    }
    public function purchase_by_csv(){
        $this->sma->checkPermissions('csv');
        $this->load->helper('security');
        $this->form_validation->set_message('is_natural_no_zero', $this->lang->line("no_zero_required"));
        $this->form_validation->set_rules('warehouse', $this->lang->line("warehouse"), 'required|is_natural_no_zero');
        $this->form_validation->set_rules('supplier', $this->lang->line("supplier"), 'required');
        $this->form_validation->set_rules('userfile', $this->lang->line("upload_file"), 'xss_clean');

        if ($this->form_validation->run() == true) {
            $quantity = "quantity";
            $product = "product";
            $unit_cost = "unit_cost";
            $tax_rate = "tax_rate";
            $reference = $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('po');
            if ($this->Owner || $this->Admin) {
                $date = $this->sma->fld(trim($this->input->post('date')));
            } else {
                $date = null;
            }
            $warehouse_id = $this->input->post('warehouse');
            $supplier_id = $this->input->post('supplier');
            $status = $this->input->post('status');
            $shipping = $this->input->post('shipping') ? $this->input->post('shipping') : 0;
            $supplier_details = $this->site->getCompanyByID($supplier_id);
            $supplier = $supplier_details->company != '-'  ? $supplier_details->company : $supplier_details->name;
            $note = $this->sma->clear_tags($this->input->post('note'));

            $total = 0;
            $product_tax = 0;
            $product_discount = 0;
            $gst_data = [];
            $total_cgst = $total_sgst = $total_igst = 0;

            if (isset($_FILES["userfile"])) {

                $this->load->library('upload');

                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = 'csv';
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = true;

                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    admin_redirect("purchases/purchase_by_csv");
                }

                $csv = $this->upload->file_name;

                $arrResult = array();
                $handle = fopen($this->digital_upload_path . $csv, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                        $arrResult[] = $row;
                    }
                    fclose($handle);
                }
                $titles = array_shift($arrResult);

                $keys = array('code', 'net_unit_cost', 'quantity', 'variant', 'item_tax_rate', 'discount', 'expiry');
                $final = array();
                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
                }
                $rw = 2;
                foreach ($final as $csv_pr) {

                    if (isset($csv_pr['code']) && isset($csv_pr['net_unit_cost']) && isset($csv_pr['quantity'])) {

                        if ($product_details = $this->purchases_model->getProductByCode($csv_pr['code'])) {

                            if ($csv_pr['variant']) {
                                $item_option = $this->purchases_model->getProductVariantByName($csv_pr['variant'], $product_details->id);
                                if (!$item_option) {
                                    $this->session->set_flashdata('error', lang("pr_not_found") . " ( " . $product_details->name . " - " . $csv_pr['variant'] . " ). " . lang("line_no") . " " . $rw);
                                    redirect($_SERVER["HTTP_REFERER"]);
                                }
                            } else {
                                $item_option = json_decode('{}');
                                $item_option->id = null;
                            }

                            $item_code = $csv_pr['code'];
                            $item_net_cost = $this->sma->formatDecimal($csv_pr['net_unit_cost']);
                            $item_quantity = $csv_pr['quantity'];
                            $quantity_balance = $csv_pr['quantity'];
                            $item_tax_rate = $csv_pr['item_tax_rate'];
                            $item_discount = $csv_pr['discount'];
                            $item_expiry = isset($csv_pr['expiry']) ? $this->sma->fsd($csv_pr['expiry']) : null;

                            $pr_discount = $this->site->calculateDiscount($item_discount, $item_net_cost);
                            $pr_item_discount = $this->sma->formatDecimal(($pr_discount * $item_quantity), 4);
                            $product_discount += $pr_item_discount;

                            $tax = "";
                            $pr_item_tax = 0;
                            $unit_cost = $item_net_cost - $pr_discount;
                            $gst_data = [];
                            $tax_details = ((isset($item_tax_rate) && !empty($item_tax_rate)) ? $this->purchases_model->getTaxRateByName($item_tax_rate) : $this->site->getTaxRateByID($product_details->tax_rate));
                            if ($tax_details) {
                                $ctax = $this->site->calculateTax($product_details, $tax_details, $unit_cost);
                                $item_tax = $ctax['amount'];
                                $tax = $ctax['tax'];
                                if ($product_details->tax_method != 1) {
                                    $item_net_cost = $unit_cost - $item_tax;
                                }
                                $pr_item_tax = $this->sma->formatDecimal($item_tax * $item_quantity, 4);
                                if ($this->Settings->indian_gst && $gst_data = $this->gst->calculteIndianGST($pr_item_tax, ($this->Settings->state == $supplier_details->state), $tax_details)) {
                                    $total_cgst += $gst_data['cgst'];
                                    $total_sgst += $gst_data['sgst'];
                                    $total_igst += $gst_data['igst'];
                                }
                            }

                            $product_tax += $pr_item_tax;
                            $subtotal = $this->sma->formatDecimal(((($item_net_cost * $item_quantity) + $pr_item_tax) - $pr_item_discount), 4);
                            $unit = $this->site->getUnitByID($product_details->unit);
                            $product = array(
                                'product_id' => $product_details->id,
                                'product_code' => $item_code,
                                'product_name' => $product_details->name,
                                'option_id' => $item_option->id,
                                'net_unit_cost' => $item_net_cost,
                                'quantity' => $item_quantity,
                                'product_unit_id' => $product_details->unit,
                                'product_unit_code' => $unit->code,
                                'unit_quantity' => $item_quantity,
                                'quantity_balance' => $quantity_balance,
                                'warehouse_id' => $warehouse_id,
                                'item_tax' => $pr_item_tax,
                                'tax_rate_id' => $tax_details ? $tax_details->id : null,
                                'tax' => $tax,
                                'discount' => $item_discount,
                                'item_discount' => $pr_item_discount,
                                'expiry' => $item_expiry,
                                'subtotal' => $subtotal,
                                'date' => date('Y-m-d', strtotime($date)),
                                'status' => $status,
                                'unit_cost' => $this->sma->formatDecimal(($item_net_cost + $item_tax), 4),
                                'real_unit_cost' => $this->sma->formatDecimal(($item_net_cost + $item_tax + $pr_discount), 4),
                            );

                            $products[] = ($product + $gst_data);
                            $total += $this->sma->formatDecimal(($item_net_cost * $item_quantity), 4);
                        } else {
                            $this->session->set_flashdata('error', $this->lang->line("pr_not_found") . " ( " . $csv_pr['code'] . " ). " . $this->lang->line("line_no") . " " . $rw);
                            redirect($_SERVER["HTTP_REFERER"]);
                        }
                        $rw++;
                    }
                }
            }

            $order_discount = $this->site->calculateDiscount($this->input->post('discount'), ($total + $product_tax));
            $total_discount = $this->sma->formatDecimal(($order_discount + $product_discount), 4);
            $order_tax = $this->site->calculateOrderTax($this->input->post('order_tax'), ($total + $product_tax - $order_discount));
            $total_tax = $this->sma->formatDecimal(($product_tax + $order_tax), 4);
            $grand_total = $this->sma->formatDecimal(($total + $total_tax + $this->sma->formatDecimal($shipping) - $total_discount), 4);
            $data = array(
                'reference_no' => $reference,
                'date' => $date,
                'supplier_id' => $supplier_id,
                'supplier' => $supplier,
                'warehouse_id' => $warehouse_id,
                'note' => $note,
                'total' => $total,
                'product_discount' => $product_discount,
                'order_discount_id' => $this->input->post('discount'),
                'order_discount' => $order_discount,
                'total_discount' => $total_discount,
                'product_tax' => $product_tax,
                'order_tax_id' => $this->input->post('order_tax'),
                'order_tax' => $order_tax,
                'total_tax' => $total_tax,
                'shipping' => $this->sma->formatDecimal($shipping),
                'grand_total' => $grand_total,
                'status' => $status,
                'created_by' => $this->session->userdata('username'),
            );
            if ($this->Settings->indian_gst) {
                $data['cgst'] = $total_cgst;
                $data['sgst'] = $total_sgst;
                $data['igst'] = $total_igst;
            }

            if ($_FILES['document']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment'] = $photo;
            }

            // $this->sma->print_arrays($data, $products);
        }

        if ($this->form_validation->run() == true && $this->purchases_model->addPurchase($data, $products)) {

            $this->session->set_flashdata('message', $this->lang->line("purchase_added"));
            admin_redirect("purchases");
        } else {

            $data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
            $this->data['ponumber'] = ''; // $this->site->getReference('po');

            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('purchases'), 'page' => lang('purchases')), array('link' => '#', 'page' => lang('add_purchase_by_csv')));
            $meta = array('page_title' => lang('add_purchase_by_csv'), 'bc' => $bc);
            $this->page_construct('purchases/purchase_by_csv', $meta, $this->data);
        }
    }
    public function suggestions(){
        $term = $this->input->get('term', true);
        $warehouse_id = $this->input->get('warehouse_id', true);
        $supplier_id = $this->input->get('supplier_id', true);
        $own_company = $this->input->get('own_company', true);

        if (strlen($term) < 1 || !$term) {
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . admin_url('welcome') . "'; }, 10);</script>");
        }

        $analyzed = $this->sma->analyze_term($term);
        $sr = $analyzed['term'];
        $option_id = $analyzed['option_id'];

        $rows = $this->purchases_model->getProductNames($sr, $warehouse_id, $supplier_id);
        $fed_tax_price = $this->site->fed_tax($q);
        // need to b check 
        $gst_tax_price = $this->site->gst_tax($q);
        $further_tax_price = $this->site->further_tax($q);

        $own_company_details = $this->site->getown_companiesByID($own_company);

        // print_r($own_company_details->ntn);

        // die();
        // exit();


        if ($rows) {
            $r = 0;
            foreach ($rows as $row) {

                $c = uniqid(mt_rand(), true);
                $option = false;
                $row->item_tax_method = $row->tax_method;
                $options = $this->purchases_model->getProductOptions($row->id);
                if ($options) {
                    $opt = $option_id && $r == 0 ? $this->purchases_model->getProductOptionByID($option_id) : current($options);
                    if (!$option_id || $r > 0) {
                        $option_id = $opt->id;
                    }
                } else {
                    $opt = json_decode('{}');
                    $opt->cost = 0;
                    $option_id = FALSE;
                }
                $row->option = $option_id;
                $row->supplier_part_no = '';
                if ($row->supplier1 == $supplier_id) {
                    $row->supplier_part_no = $row->supplier1_part_no;
                } elseif ($row->supplier2 == $supplier_id) {
                    $row->supplier_part_no = $row->supplier2_part_no;
                } elseif ($row->supplier3 == $supplier_id) {
                    $row->supplier_part_no = $row->supplier3_part_no;
                } elseif ($row->supplier4 == $supplier_id) {
                    $row->supplier_part_no = $row->supplier4_part_no;
                } elseif ($row->supplier5 == $supplier_id) {
                    $row->supplier_part_no = $row->supplier5_part_no;
                }
                if ($opt->cost != 0) {
                    $row->cost = $opt->cost;
                }

                $row->cost = $supplier_id ? $this->getSupplierCost($supplier_id, $row) : $row->cost;
                $row->real_unit_cost = $row->cost;
                $row->base_quantity = 1;
                $row->base_unit = $row->unit;
                $row->base_unit_cost = $row->cost;
                $row->unit = $row->purchase_unit ? $row->purchase_unit : $row->unit;
                $row->new_entry = 1;
                $row->expiry = '';
                $row->qty = 1;
                $row->quantity_balance = '';
                $row->discount = '0';
                $row->consiment = $row->price;

                $row->batch = '';

                $row->discount_one_checked = 'false';
                $row->discount_two_checked = 'false';
                $row->discount_three_checked = 'false';
                $row->fed_tax_checked = 'false';

                // $row->fed_tax_rate = $fed_tax_price->fed_tax;
                $row->fed_tax_rate = $row->fed_tax;
                $row->gst_tax_rate = $row->gst_tax;
                $row->further_tax_rate = $row->further_tax;

                $row->own_companies_check_strn = $own_company_details->strn;
                $row->show_further_tax = $further_tax_price->further_tax;

                unset($row->details, $row->product_details, $row->price, $row->file, $row->supplier1price, $row->supplier2price, $row->supplier3price, $row->supplier4price, $row->supplier5price, $row->supplier1_part_no, $row->supplier2_part_no, $row->supplier3_part_no, $row->supplier4_part_no, $row->supplier5_part_no);

                $units = $this->site->getUnitsByBUID($row->base_unit);
                $tax_rate = $this->site->getTaxRateByID($row->tax_rate);

                $pr[] = array(
                    'id' => sha1($c . $r), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")",
                    'row' => $row, 'tax_rate' => $tax_rate, 'units' => $units, /*'batch' => "123456", 'expiry' => "12/12/2018", */ 'options' => $options
                );
                $r++;
            }
            $this->sma->send_json($pr);
        } else {
            $this->sma->send_json(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
        }
    }
    public function purchase_actions(){
        if (!$this->Owner && !$this->GP['bulk_actions']) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {

                    $this->sma->checkPermissions('delete');
                    foreach ($_POST['val'] as $id) {
                        $this->purchases_model->deletePurchase($id);
                    }
                    $this->session->set_flashdata('message', $this->lang->line("purchases_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                } elseif ($this->input->post('form_action') == 'combine') {

                    $html = $this->combine_pdf($_POST['val']);
                } elseif ($this->input->post('form_action') == 'export_excel') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('purchases'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('reference_no'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('supplier'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('status'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('grand_total'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $purchase = $this->purchases_model->getPurchaseByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->sma->hrld($purchase->date));
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $purchase->reference_no);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $purchase->supplier);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $purchase->status);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $this->sma->formatMoney($purchase->grand_total));
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'purchases_' . date('Y_m_d_H_i_s');
                    $this->load->helper('excel');
                    create_excel($this->excel, $filename);
                }
            } else {
                $this->session->set_flashdata('error', $this->lang->line("no_purchase_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }
    public function payments($id = null){
        $this->sma->checkPermissions(false, true);

        $this->data['payments'] = $this->purchases_model->getPurchasePayments($id);
        $this->data['inv'] = $this->purchases_model->getPurchaseByID($id);
        $this->load->view($this->theme . 'purchases/payments', $this->data);
    }
    public function payment_note($id = null){
        $this->sma->checkPermissions('payments', true);
        $payment = $this->purchases_model->getPaymentByID($id);
        $inv = $this->purchases_model->getPurchaseByID($payment->purchase_id);
        $this->data['supplier'] = $this->site->getCompanyByID($inv->supplier_id);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        $this->data['payment'] = $payment;
        $this->data['page_title'] = $this->lang->line("payment_note");

        $this->load->view($this->theme . 'purchases/payment_note', $this->data);
    }
    public function email_payment($id = null){
        $this->sma->checkPermissions('payments', true);
        $payment = $this->purchases_model->getPaymentByID($id);
        $inv = $this->purchases_model->getPurchaseByID($payment->purchase_id);
        $supplier = $this->site->getCompanyByID($inv->supplier_id);
        $this->data['inv'] = $inv;
        $this->data['payment'] = $payment;
        if (!$supplier->email) {
            $this->sma->send_json(array('msg' => lang("update_supplier_email")));
        }
        $this->data['supplier'] = $supplier;
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        $this->data['payment'] = $payment;
        $this->data['page_title'] = lang("payment_note");
        $html = $this->load->view($this->theme . 'purchases/payment_note', $this->data, TRUE);

        $html = str_replace(array('<i class="fa fa-2x">&times;</i>', 'modal-', '<p>&nbsp;</p>', '<p style="border-bottom: 1px solid #666;">&nbsp;</p>', '<p>' . lang("stamp_sign") . '</p>'), '', $html);
        $html = preg_replace("/<img[^>]+\>/i", '', $html);
        // $html = '<div style="border:1px solid #DDD; padding:10px; margin:10px 0;">'.$html.'</div>';

        $this->load->library('parser');
        $parse_data = array(
            'stylesheet' => '<link href="' . $this->data['assets'] . 'styles/helpers/bootstrap.min.css" rel="stylesheet"/>',
            'name' => $supplier->company && $supplier->company != '-' ? $supplier->company :  $supplier->name,
            'email' => $supplier->email,
            'heading' => lang('payment_note') . '<hr>',
            'msg' => $html,
            'site_link' => base_url(),
            'site_name' => $this->Settings->site_name,
            'logo' => '<img src="' . base_url('assets/uploads/logos/' . $this->Settings->logo) . '" alt="' . $this->Settings->site_name . '"/>'
        );
        $msg = file_get_contents('./themes/' . $this->Settings->theme . '/admin/views/email_templates/email_con.html');
        $message = $this->parser->parse_string($msg, $parse_data);
        $subject = lang('payment_note') . ' - ' . $this->Settings->site_name;

        if ($this->sma->send_email($supplier->email, $subject, $message)) {
            $this->sma->send_json(array('msg' => lang("email_sent")));
        } else {
            $this->sma->send_json(array('msg' => lang("email_failed")));
        }
    }
    public function expensesList(){
        $this->db->select(
            $this->db->dbprefix('expenses') . ".id as id, 
            {$this->db->dbprefix('expenses')}.date, 
            {$this->db->dbprefix('expenses')}.reference, 
            {$this->db->dbprefix('expenses')}.etype, 
            {$this->db->dbprefix('warehouses')}.name as warehouse_name, 
            {$this->db->dbprefix('expense_categories')}.name as category, 
            {$this->db->dbprefix('own_companies')}.companyname as own_compnay, 
            {$this->db->dbprefix('expenses')}.pay_method, 
            {$this->db->dbprefix('expenses')}.amount, 
            {$this->db->dbprefix('expenses')}.note, 
            CONCAT({$this->db->dbprefix('users')}.first_name, ' ', {$this->db->dbprefix('users')}.last_name) as user, 
            {$this->db->dbprefix('expenses')}.attachment", 
            false
        )
        ->from('expenses')
        ->join('users', 'users.id=expenses.created_by', 'left')
        ->join('expense_categories', 'expense_categories.id=expenses.category_id', 'left')
        ->join('warehouses', 'warehouses.id=expenses.warehouse_id', 'left')
        ->join('own_companies', 'own_companies.id=expenses.own_company', 'left')
        ->group_by('expenses.id');

        if (!$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            $this->db->where('created_by', $this->session->userdata('user_id'));
        }
        $q = $this->db->get();
        return $q->result();
    }
    public function expense_note($id = null){
        $expense = $this->purchases_model->getExpenseByID($id);
        $this->data['user'] = $this->site->getUser($expense->created_by);
        $this->data['category'] = $expense->category_id ? $this->purchases_model->getExpenseCategoryByID($expense->category_id) : NULL;
        $this->data['warehouse'] = $expense->warehouse_id ? $this->site->getWarehouseByID($expense->warehouse_id) : NULL;
        $this->data['expense'] = $expense;
        $this->data['page_title'] = $this->lang->line("expense_note");
        $this->load->view($this->theme . 'purchases/expense_note', $this->data);
    }
    public function edit_expense_old($id = null){
        $this->sma->checkPermissions('edit', true);
        $this->load->helper('security');
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->form_validation->set_rules('reference', lang("reference"), 'required');
        $this->form_validation->set_rules('amount', lang("amount"), 'required');
        $this->form_validation->set_rules('userfile', lang("attachment"), 'xss_clean');
        if ($this->form_validation->run() == true) {
            if ($this->Owner || $this->Admin) {
                $date = $this->sma->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $data = array(
                'date' => $date,
                'reference' => $this->input->post('reference'),
                'amount' => $this->input->post('amount'),
                'note' => $this->input->post('note', true),
                'category_id' => $this->input->post('category', true),
                'warehouse_id' => $this->input->post('warehouse', true),
            );
            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment'] = $photo;
            }

            //$this->sma->print_arrays($data);

        } elseif ($this->input->post('edit_expense')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == true && $this->purchases_model->updateExpense($id, $data)) {
            $this->session->set_flashdata('message', lang("expense_updated"));
            admin_redirect("purchases/expenses");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['expense'] = $this->purchases_model->getExpenseByID($id);
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['categories'] = $this->purchases_model->getExpenseCategories();
            $this->load->view($this->theme . 'purchases/edit_expense', $this->data);
        }
    }
    public function delete_expense_old($id = null){
        $this->sma->checkPermissions('delete', true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $expense = $this->purchases_model->getExpenseByID($id);
        if ($this->purchases_model->deleteExpense($id)) {
            $insert['user_id'] = $this->session->userdata('user_id');
            $insert['wallet_id'] = $expense->wallet_id;
            $insert['amount'] = $expense->amount;
            $insert['type'] = '0';
            $this->db->insert('sma_wallet_transations',$insert);
            $this->db->set('amount', 'amount+'.$expense->amount, FALSE);
            $this->db->where('id', $expense->wallet_id);
            $this->db->update('sma_wallets');
            if ($expense->attachment) {
                unlink($this->upload_path . $expense->attachment);
            }
            $this->sma->send_json(array('error' => 0, 'msg' => lang("expense_deleted")));
        }
    }
    public function expense_actions(){
        if (!$this->Owner && !$this->GP['bulk_actions']) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    $this->sma->checkPermissions('delete');
                    foreach ($_POST['val'] as $id) {
                        $this->purchases_model->deleteExpense($id);
                    }
                    $this->session->set_flashdata('message', $this->lang->line("expenses_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('expenses'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('reference'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('amount'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('note'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('created_by'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $expense = $this->purchases_model->getExpenseByID($id);
                        $user = $this->site->getUser($expense->created_by);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->sma->hrld($expense->date));
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $expense->reference);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $this->sma->formatMoney($expense->amount));
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $expense->note);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $user->first_name . ' ' . $user->last_name);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
                    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'expenses_' . date('Y_m_d_H_i_s');
                    $this->load->helper('excel');
                    create_excel($this->excel, $filename);
                }
            } else {
                $this->session->set_flashdata('error', $this->lang->line("no_expense_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }
    public function view_return($id = null){
        $this->sma->checkPermissions('return_purchases');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->purchases_model->getReturnByID($id);
        if (!$this->session->userdata('view_right')) {
            $this->sma->view_rights($inv->created_by);
        }
        $this->data['barcode'] = "<img src='" . admin_url('products/gen_barcode/' . $inv->reference_no) . "' alt='" . $inv->reference_no . "' class='pull-left' />";
        $this->data['supplier'] = $this->site->getCompanyByID($inv->supplier_id);
        $this->data['payments'] = $this->purchases_model->getPaymentsForPurchase($id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        $this->data['rows'] = $this->purchases_model->getAllReturnItems($id);
        $this->data['purchase'] = $this->purchases_model->getPurchaseByID($inv->purchase_id);
        $this->load->view($this->theme . 'purchases/view_return', $this->data);
    }
    public function return_purchase($id = null){
        $this->sma->checkPermissions('return_purchases');
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $purchase = $this->purchases_model->getPurchaseByID($id);
        if ($purchase->return_id) {
            $this->session->set_flashdata('error', lang("purchase_already_returned"));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        $this->form_validation->set_rules('return_surcharge', lang("return_surcharge"), 'required');
        if ($this->form_validation->run() == true) {
            $reference = $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('rep');
            if ($this->Owner || $this->Admin) {
                $date = $this->sma->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $return_surcharge = $this->input->post('return_surcharge') ? $this->input->post('return_surcharge') : 0;
            $note = $this->sma->clear_tags($this->input->post('note'));
            $supplier_details = $this->site->getCompanyByID($purchase->supplier_id);
            $total = 0;
            $product_tax = 0;
            $product_discount = 0;
            $gst_data = [];
            $total_cgst = $total_sgst = $total_igst = 0;
            $i = isset($_POST['product']) ? sizeof($_POST['product']) : 0;
            for ($r = 0; $r < $i; $r++) {
                $item_id = $_POST['product_id'][$r];
                $item_code = $_POST['product'][$r];
                $purchase_item_id = $_POST['purchase_item_id'][$r];
                $item_option = isset($_POST['product_option'][$r]) && !empty($_POST['product_option'][$r]) && $_POST['product_option'][$r] != 'false' ? $_POST['product_option'][$r] : null;
                $real_unit_cost = $this->sma->formatDecimal($_POST['real_unit_cost'][$r]);
                $unit_cost = $this->sma->formatDecimal($_POST['unit_cost'][$r]);
                $item_unit_quantity = (0 - $_POST['quantity'][$r]);
                $item_expiry = isset($_POST['expiry'][$r]) ? $_POST['expiry'][$r] : '';
                $item_tax_rate = isset($_POST['product_tax'][$r]) ? $_POST['product_tax'][$r] : null;
                $item_discount = isset($_POST['product_discount'][$r]) ? $_POST['product_discount'][$r] : null;
                $item_unit = $_POST['product_unit'][$r];
                $item_quantity = (0 - $_POST['product_base_quantity'][$r]);

                if (isset($item_code) && isset($real_unit_cost) && isset($unit_cost) && isset($item_quantity)) {
                    $product_details = $this->purchases_model->getProductByCode($item_code);
                    $item_type = $product_details->type;
                    $item_name = $product_details->name;
                    $pr_discount = $this->site->calculateDiscount($item_discount, $unit_cost);
                    $unit_cost = $this->sma->formatDecimal($unit_cost - $pr_discount);
                    $pr_item_discount = $this->sma->formatDecimal(($pr_discount * $item_unit_quantity), 4);
                    $product_discount += $pr_item_discount;
                    $item_net_cost = $unit_cost;
                    $pr_item_tax = $item_tax = 0;
                    $tax = "";

                    if (isset($item_tax_rate) && $item_tax_rate != 0) {

                        $tax_details = $this->site->getTaxRateByID($item_tax_rate);
                        $ctax = $this->site->calculateTax($product_details, $tax_details, $unit_cost);
                        $item_tax = $ctax['amount'];
                        $tax = $ctax['tax'];
                        if ($product_details->tax_method != 1) {
                            $item_net_cost = $unit_cost - $item_tax;
                        }
                        $pr_item_tax = $this->sma->formatDecimal($item_tax * $item_unit_quantity, 4);
                        if ($this->Settings->indian_gst && $gst_data = $this->gst->calculteIndianGST($pr_item_tax, ($this->Settings->state == $supplier_details->state), $tax_details)) {
                            $total_cgst += $gst_data['cgst'];
                            $total_sgst += $gst_data['sgst'];
                            $total_igst += $gst_data['igst'];
                        }
                    }
                    $product_tax += $pr_item_tax;
                    $subtotal = $this->sma->formatDecimal((($item_net_cost * $item_unit_quantity) + $pr_item_tax), 4);
                    $unit = $this->site->getUnitByID($item_unit);
                    $product = array(
                        'product_id' => $item_id,
                        'product_code' => $item_code,
                        'product_name' => $item_name,
                        'option_id' => $item_option,
                        'net_unit_cost' => $item_net_cost,
                        'unit_cost' => $this->sma->formatDecimal($item_net_cost + $item_tax),
                        'quantity' => $item_quantity,
                        'product_unit_id' => $item_unit,
                        'product_unit_code' => $unit->code,
                        'unit_quantity' => $item_unit_quantity,
                        'quantity_balance' => $item_quantity,
                        'warehouse_id' => $purchase->warehouse_id,
                        'item_tax' => $pr_item_tax,
                        'tax_rate_id' => $item_tax_rate,
                        'tax' => $tax,
                        'discount' => $item_discount,
                        'item_discount' => $pr_item_discount,
                        'subtotal' => $this->sma->formatDecimal($subtotal),
                        'real_unit_cost' => $real_unit_cost,
                        'purchase_item_id' => $purchase_item_id,
                        'status' => 'received',
                    );
                    $products[] = ($product + $gst_data);
                    $total += $this->sma->formatDecimal(($item_net_cost * $item_unit_quantity), 4);
                }
            }
            if (empty($products)) {
                $this->form_validation->set_rules('product', lang("order_items"), 'required');
            } else {
                krsort($products);
            }
            $order_discount = $this->site->calculateDiscount($this->input->post('discount') ? $this->input->post('order_discount') : null, ($total + $product_tax));
            $total_discount = $this->sma->formatDecimal(($order_discount + $product_discount), 4);
            $order_tax = $this->site->calculateOrderTax($this->input->post('order_tax'), ($total + $product_tax - $order_discount));
            $total_tax = $this->sma->formatDecimal(($product_tax + $order_tax), 4);
            $grand_total = $this->sma->formatDecimal(($total + $total_tax + $this->sma->formatDecimal($return_surcharge) - $order_discount), 4);
            $data = array(
                'date' => $date,
                'purchase_id' => $id,
                'reference_no' => $purchase->reference_no,
                'supplier_id' => $purchase->supplier_id,
                'supplier' => $purchase->supplier,
                'warehouse_id' => $purchase->warehouse_id,
                'note' => $note,
                'total' => $total,
                'product_discount' => $product_discount,
                'order_discount_id' => ($this->input->post('discount') ? $this->input->post('order_discount') : null),
                'order_discount' => $order_discount,
                'total_discount' => $total_discount,
                'product_tax' => $product_tax,
                'order_tax_id' => $this->input->post('order_tax'),
                'order_tax' => $order_tax,
                'total_tax' => $total_tax,
                'surcharge' => $this->sma->formatDecimal($return_surcharge),
                'grand_total' => $grand_total,
                'created_by' => $this->session->userdata('user_id'),
                'return_purchase_ref' => $reference,
                'status' => 'returned',
                'payment_status' => $purchase->payment_status == 'paid' ? 'due' : 'pending',
            );
            if ($this->Settings->indian_gst) {
                $data['cgst'] = $total_cgst;
                $data['sgst'] = $total_sgst;
                $data['igst'] = $total_igst;
            }
            if ($_FILES['document']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment'] = $photo;
            }
        }
        if ($this->form_validation->run() == true && $this->purchases_model->addPurchase($data, $products)) {
            $this->session->set_flashdata('message', lang("return_purchase_added"));
            admin_redirect("purchases");
        }
        else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['inv'] = $purchase;
            if ($this->data['inv']->status != 'received' && $this->data['inv']->status != 'partial') {
                $this->session->set_flashdata('error', lang("purchase_status_x_received"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
            if ($this->Settings->disable_editing) {
                if ($this->data['inv']->date <= date('Y-m-d', strtotime('-' . $this->Settings->disable_editing . ' days'))) {
                    $this->session->set_flashdata('error', sprintf(lang("purchase_x_edited_older_than_x_days"), $this->Settings->disable_editing));
                    redirect($_SERVER["HTTP_REFERER"]);
                }
            }
            $inv_items = $this->purchases_model->getAllPurchaseItems($id);
            $c = rand(100000, 9999999);
            foreach ($inv_items as $item) {
                $row = $this->site->getProductByID($item->product_id);
                $row->expiry = (($item->expiry && $item->expiry != '0000-00-00') ? $this->sma->hrsd($item->expiry) : '');
                $row->base_quantity = $item->quantity;
                $row->base_unit = $row->unit ? $row->unit : $item->product_unit_id;
                $row->base_unit_cost = $row->cost ? $row->cost : $item->unit_cost;
                $row->unit = $item->product_unit_id;
                $row->qty = $item->unit_quantity;
                $row->oqty = $item->unit_quantity;
                $row->purchase_item_id = $item->id;
                $row->supplier_part_no = $item->supplier_part_no;
                $row->received = $item->quantity_received ? $item->quantity_received : $item->quantity;
                $row->quantity_balance = $item->quantity_balance + ($item->quantity - $row->received);
                $row->discount = $item->discount ? $item->discount : '0';
                $options = $this->purchases_model->getProductOptions($row->id);
                $row->option = !empty($item->option_id) ? $item->option_id : '';
                $row->real_unit_cost = $item->real_unit_cost;
                $row->cost = $this->sma->formatDecimal($item->net_unit_cost + ($item->item_discount / $item->quantity));
                $row->tax_rate = $item->tax_rate_id;
                unset($row->details, $row->product_details, $row->price, $row->file, $row->product_group_id);
                $units = $this->site->getUnitsByBUID($row->base_unit);
                $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                $ri = $this->Settings->item_addition ? $row->id : $c;
                $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'units' => $units, 'tax_rate' => $tax_rate, 'options' => $options);
                $c++;
            }
            $this->data['inv_items'] = json_encode($pr);
            $this->data['id'] = $id;
            $this->data['reference'] = '';
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('purchases'), 'page' => lang('purchases')), array('link' => '#', 'page' => lang('return_purchase')));
            $meta = array('page_title' => lang('return_purchase'), 'bc' => $bc);
            $this->page_construct('purchases/return_purchase', $meta, $this->data);
        }
    }
    public function getSupplierCost($supplier_id, $product){
        switch ($supplier_id) {
            case $product->supplier1:
                $cost =  $product->supplier1price > 0 ? $product->supplier1price : $product->cost;
                break;
            case $product->supplier2:
                $cost =  $product->supplier2price > 0 ? $product->supplier2price : $product->cost;
                break;
            case $product->supplier3:
                $cost =  $product->supplier3price > 0 ? $product->supplier3price : $product->cost;
                break;
            case $product->supplier4:
                $cost =  $product->supplier4price > 0 ? $product->supplier4price : $product->cost;
                break;
            case $product->supplier5:
                $cost =  $product->supplier5price > 0 ? $product->supplier5price : $product->cost;
                break;
            default:
                $cost = $product->cost;
        }
        return $cost;
    }
    public function update_status($id){

        $this->form_validation->set_rules('status', lang("status"), 'required');

        if ($this->form_validation->run() == true) {
            $status = $this->input->post('status');
            $note = $this->sma->clear_tags($this->input->post('note'));
        } elseif ($this->input->post('update')) {
            $this->session->set_flashdata('error', validation_errors());
            admin_redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'sales');
        }

        if ($this->form_validation->run() == true && $this->purchases_model->updateStatus($id, $status, $note)) {
            $this->session->set_flashdata('message', lang('status_updated'));
            admin_redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'sales');
        } else {

            $this->data['inv'] = $this->purchases_model->getPurchaseByID($id);
            $this->data['returned'] = FALSE;
            if ($this->data['inv']->status == 'returned' || $this->data['inv']->return_id) {
                $this->data['returned'] = TRUE;
            }
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'purchases/update_status', $this->data);
        }
    }
    // ------------------------New Code By Ismail FSD-----------------------//
    public function detail(){
        $purchase_id = $this->input->get('id');
        if ($purchase_id != "") {
            $inv = $this->purchases_model->details($purchase_id);
            $this->data['inv'] = $inv;
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('sales'), 'page' => lang('sales')), array('link' => '#', 'page' => lang('view')));
            $meta = array('page_title' => 'Invoice No: '.$inv->reference_no.' '.lang('view_sales_details'), 'bc' => $bc);
            $this->page_construct('purchases/detail', $meta, $this->data);
        }
        else{
            redirect(base_url('admin/purchases'));
        }
    }
    public function purchase_return($id){
        $this->data['purchase_id'] = $id;
        $this->data['items'] = $this->purchases_model->getitems($id);
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('purchases'), 'page' => lang('purchases')), array('link' => '#', 'page' => 'Purchase Return'));
        $meta = array('page_title' => 'Purchase Return', 'bc' => $bc);
        $this->page_construct('purchases/purchase_return', $meta, $this->data);
    }
    public function purchase_return_submit(){
        $sendvalue['codestatus'] = false;
        $items = array();
        $purchaseid = $this->input->post('purchase_id');
        $item_id = $this->input->post('item_id');
        $return_qty = $this->input->post('return_qty');
        $reason = $this->input->post('reason');
        $totaldiscount = 0;
        $totaltax = 0;
        $totalval = 0;
        foreach($item_id as $key => $row){
            if($item_id[$key] != "" && $return_qty[$key] > 0 && $reason[$key] != ""){
                $data = $this->purchases_model->getPurchaseItemData($item_id[$key]);
                if($data){
                    $qb = (int)$data->quantity_balance;
                    if($qb >= $return_qty[$key]){
                        $items[$key]['purchase_item_id'] = $item_id[$key];
                        $items[$key]['product_id'] = $data->product_id;
                        $items[$key]['net_unit_cost'] = $data->net_unit_cost;
                        $items[$key]['dropship'] = $data->dropship;
                        $items[$key]['crossdock'] = $data->crossdock;
                        $items[$key]['mrp'] = $data->mrp;
                        $items[$key]['expiry'] = $data->expiry;
                        $items[$key]['batch'] = $data->batch;
                        $items[$key]['quantity'] = $return_qty[$key];
                        $items[$key]['warehouse_id'] = $data->warehouse_id;
                        $items[$key]['item_tax_id'] = $data->tax_rate_id;
                        $items[$key]['item_tax'] = ($data->item_tax/$data->quantity)*$return_qty[$key];
                        $items[$key]['further_tax'] = ($data->further_tax/$data->quantity)*$return_qty[$key];
                        $items[$key]['fed_tax'] = ($data->fed_tax/$data->quantity)*$return_qty[$key];
                        $items[$key]['adv_tax'] = ($data->adv_tax/$data->quantity)*$return_qty[$key];
                        $items[$key]['total_tax'] = $items[$key]['item_tax']+$items[$key]['further_tax']+$items[$key]['fed_tax']+$items[$key]['adv_tax'];
                        $items[$key]['discount_one'] = $data->discount_one;
                        $items[$key]['discount_two'] = $data->discount_two;
                        $items[$key]['discount_three'] = $data->discount_three;
                        $items[$key]['total_discount'] = ($data->item_discount/$data->quantity)*$return_qty[$key];;
                        $items[$key]['subtotal'] = ($items[$key]['net_unit_cost']*$items[$key]['quantity'])+$items[$key]['total_tax']-$items[$key]['total_discount'];
                        $items[$key]['reason'] = $reason[$key];
                        $totaldiscount += $items[$key]['total_discount'];
                        $totaltax += $items[$key]['total_tax'];
                        $totalval += $items[$key]['subtotal'];


                    }
                    else{
                        $sendvalue['message'] = "Return quantity not available";
                        echo json_encode($sendvalue);
                        exit();
                    }
                }
            }
            else{
                if($item_id[$key] != ""){
                    if($return_qty[$key] > 0 && $reason[$key] == ""){
                        $sendvalue['message'] = "Enter Reason";
                        echo json_encode($sendvalue);
                        exit();

                    }
                    else if($return_qty[$key] == 0 && $reason[$key] != ""){
                        $sendvalue['message'] = "Enter Quantity";
                        echo json_encode($sendvalue);
                        exit();
                    }
                }
            }
        }
        if(count($items) > 0){
            $insert['return_date'] = date("Y-m-d H:i:s");
            $insert['purchase_id'] = $purchaseid;
            $insert['items_discount'] = $totaldiscount;
            $insert['items_tax'] = $totaltax;
            $insert['subtotal'] = $totalval;
            $insert['surcharge'] = 0;
            $insert['grand_total'] = $totalval+$insert['surcharge'];
            $insert['created_by'] = $this->session->userdata('user_id');
            $sendvalue = $this->purchases_model->addreturn($insert,$items);
        }
        else{
            $sendvalue['message'] = "Return Items Not Found";
        }
        echo json_encode($sendvalue);
    }
    public function returns(){
        $this->data['pr_rows'] = $this->purchases_model->reuturns();
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => 'Purchase Returns'));
        $meta = array('page_title' => 'Purchase Returns', 'bc' => $bc);
        $this->page_construct('purchases/purchase_return_list', $meta, $this->data);
    }
    public function return_view($id){
        $this->data['details'] = $this->purchases_model->reuturn_data($id);
        if($this->data['details']->codestats == "ok"){
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => 'Purchase Returns'));
            $meta = array('page_title' => 'Purchase Returns', 'bc' => $bc);
            $this->page_construct('purchases/purchase_return_view', $meta, $this->data);
        }
        else{
            // redirect(admin_url('purchases/returns'));
        }
    }
    public function return_delete(){
        $sendvalue['codestatus'] = 'no';
        $id = $this->input->get('id');
        $reason = $this->input->get('reason');
        if($reason != ""){

            $return_rows = $this->purchases_model->returnItems($id);
            foreach($return_rows as $row){
                $this->db->delete('sma_purchase_return_items_tb', array('id' => $row->id));
                $this->purchases_model->updateQty($row->product_id,$row->warehouse_id,$row->purchase_item_id,$row->quantity,'+');
            }
            $this->db->delete('sma_purchase_return_tb', array('purchase_id' => $id));
            $activitynote = 'Return ID: '.$id.'<br><p style="color:red" ><b>Reason:</b> '.$reason.'</p>';
            $this->useractivities_model->add([
                'note'=>$activitynote,
                'location'=>'Purchases->Detail->Return->Delete',
                'purchase_id'=>$id,
                'action_by'=>$this->session->userdata('user_id')
            ]);
            $sendvalue['codestatus'] = "Return purchase deleted";
            echo json_encode($sendvalue);
            exit();
        }
        else{
            $sendvalue['codestatus'] = "Enter Reason";
            echo json_encode($sendvalue);
            exit();
        }



    }
    public function adjustment(){
        if($this->data['Owner'] || $this->data['Admin'] || $this->data['GP']['purchase_adj_view']){
        // if($this->data['GP']['purchase_adj_view']){
            $this->db->select('
                sma_purchase_item_adjs.date as adj_date,
                sma_users.first_name,
                sma_users.last_name,
                sma_purchase_items.*
            ');
            $this->db->from('sma_purchase_items');
            $this->db->join('sma_purchase_item_adjs','sma_purchase_item_adjs.id = sma_purchase_items.batch_adj_id','left');
            $this->db->join('sma_users','sma_users.id = sma_purchase_item_adjs.created_by','left');
            $this->db->where('sma_purchase_items.batch_adj_id != ','0');
            $q = $this->db->get();
            $this->data['rows'] = $q->result();
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('purchases'), 'page' => lang('purchases')), array('link' => '#', 'page' => 'Batch Adjustment'));
            $meta = array('page_title' => 'Batch Adjustment', 'bc' => $bc);
            $this->page_construct('purchases/adjustments', $meta, $this->data);
        }
        else{
            admin_redirect();
        }
    }
    public function batchslist(){
        $html = "<option value='' selected >Batch Not Found</option>";
        $pid = $this->input->get('pid');
        $wid = $this->input->get('wid');
        $this->db->select('id,net_unit_cost,price,dropship,crossdock,mrp,expiry,batch,quantity_balance');
        $this->db->from('sma_purchase_items');
        $this->db->where('quantity_balance > ','0');
        $this->db->where('product_id',$pid);
        if($wid != "all"){
            $this->db->where('warehouse_id',$wid);
        }
        $q = $this->db->get();
        $rows = $q->result();
        if(count($rows) > 0){
            $html = "";
        }
        $no = 1;
        foreach($rows as $row){
            $html .= "<option 
                value='".$row->id."' 
                data-cost='".$row->net_unit_cost."' 
                data-tpprice='".$row->price."' 
                data-dropship='".$row->dropship."' 
                data-crossdock='".$row->crossdock."' 
                data-mrp='".$row->mrp."' 
                data-expiry='".$row->expiry."' 
                data-batch='".$row->batch."' 
                data-available='".$row->quantity_balance."' 
            ";
            if($no == 1){
                $html .= " selected ";               
            }
            $html .= ">".$row->batch." (Av.Qty:".$row->quantity_balance.",Expiry:".$row->expiry." )</option>";
        }
        echo $html;
    }
    public function addadjustment(){
        $senvalue['codestatus'] = false;
        $senvalue['message'] = 'Try Again';
        if($this->data['Owner'] || $this->data['Admin'] || $this->data['GP']['purchase_adj_add']){
            $purchase_item_id = $this->input->post('batch');
            $quantiy = $this->input->post('adj_qty');
            $adj_expiry = $this->input->post('adj_expiry');
            $adj_costprice = $this->input->post('adj_costprice');
            $adj_tp_price = $this->input->post('adj_tp_price');
            $adj_dropship_price = $this->input->post('adj_dropship_price');
            $adj_crossdock_price = $this->input->post('adj_crossdock_price');
            $adj_mrp_price = $this->input->post('adj_mrp_price');
            $reason = $this->input->post('reason');
            if($reason != ""){
                $this->db->select('*');
                $this->db->from('sma_purchase_items');
                $this->db->where('id',$purchase_item_id);
                $q = $this->db->get();
                if($q->num_rows() == 0){
                    $senvalue['message'] = 'Invalid Batch';
                }
                else{
                    $purchase_item = $q->result()[0];
                    $newbatch = $purchase_item->batch.'-ADJ-01';
                    $bno = 1;
                    for($i=0;$i<1;){
                        $newbatch = $purchase_item->batch.'-ADJ-'.$bno;
                        $this->db->select('*');
                        $this->db->from('sma_purchase_items');
                        $this->db->where('batch',$newbatch);
                        $this->db->where('product_id',$purchase_item->product_id);
                        $bq = $this->db->get();
                        if($bq->num_rows() == 0){
                            $i++;
                            break;
                        }
                        else{
                            $bno++;
                        }
                    }
                    if($purchase_item->quantity_balance >= $quantiy){
                        $data['created_by'] = $this->session->userdata('user_id');
                        $data['status'] = 'complete';
                        $this->db->insert('sma_purchase_item_adjs', $data);
                        $adj_id = $this->db->insert_id();
                        $item['purchase_id'] = 0;
                        $item['transfer_id'] = 0;
                        $item['batch_adj_id'] = $adj_id;
                        $item['product_id'] = $purchase_item->product_id;
                        $item['product_code'] = $purchase_item->product_code;
                        $item['product_name'] = $purchase_item->product_name;
                        $item['option_id'] = $purchase_item->option_id;
                        if($this->data['Owner'] || $this->data['Admin'] || $this->data['GP']['purchase_adj_price']){
                            $item['net_unit_cost'] = $adj_costprice;
                            $item['price'] = $adj_tp_price;
                            $item['dropship'] = $adj_dropship_price;
                            $item['crossdock'] = $adj_crossdock_price;
                            $item['mrp'] = $adj_mrp_price;
                        }
                        else{
                            $item['net_unit_cost'] = $purchase_item->net_unit_cost;
                            $item['price'] = $purchase_item->price;
                            $item['dropship'] = $purchase_item->dropship;
                            $item['crossdock'] = $purchase_item->crossdock;
                            $item['mrp'] = $purchase_item->mrp;
                        }
                        $item['quantity'] = $quantiy;
                        $item['warehouse_id'] = $purchase_item->warehouse_id;
                        $item['item_tax'] = $purchase_item->item_tax/$purchase_item->quantity*$quantiy;
                        $item['tax_rate_id'] = $purchase_item->tax_rate_id;
                        $item['tax'] = $purchase_item->tax;
                        $item['discount'] = $purchase_item->discount/$purchase_item->quantity*$quantiy;
                        $item['item_discount'] = $purchase_item->item_discount/$purchase_item->quantity*$quantiy;
                        $item['expiry'] = $adj_expiry;
                        $item['batch'] = $newbatch;
                        $item['subtotal'] = $purchase_item->subtotal/$purchase_item->quantity*$quantiy;
                        $item['quantity_balance'] = $quantiy;
                        $item['date'] = date('Y-m-d');
                        $item['status'] = $purchase_item->status;
                        $item['unit_cost'] = $purchase_item->unit_cost;
                        $item['real_unit_cost'] = $purchase_item->real_unit_cost;
                        $item['quantity_received'] = $quantiy;
                        $item['supplier_part_no'] = $purchase_item->supplier_part_no;
                        $item['purchase_item_id'] = $purchase_item->purchase_item_id;
                        $item['product_unit_id'] = $purchase_item->product_unit_id;
                        $item['product_unit_code'] = $purchase_item->product_unit_code;
                        $item['unit_quantity'] = $quantiy;
                        $item['gst'] = $purchase_item->gst;
                        $item['cgst'] = $purchase_item->cgst;
                        $item['sgst'] = $purchase_item->sgst;
                        $item['igst'] = $purchase_item->igst;
                        $item['discount_one'] = $purchase_item->discount_one;
                        $item['discount_two'] = $purchase_item->discount_two;
                        $item['discount_three'] = $purchase_item->discount_three;
                        $item['further_tax'] = $purchase_item->further_tax;
                        $item['fed_tax'] = $purchase_item->fed_tax;
                        $item['gst_tax'] = $purchase_item->gst_tax;
                        $item['old_batch'] = $purchase_item->batch;
                        $item['old_purchase_item_id'] = $purchase_item->id;
                        $this->db->insert('sma_purchase_items', $item);

                        $this->db->set('quantity_balance', 'quantity_balance-'.$quantiy, FALSE);
                        $this->db->set('quantity_received', 'quantity_received-'.$quantiy, FALSE);
                        $this->db->where('id', $purchase_item_id);
                        $this->db->update('purchase_items');
                        $senvalue['codestatus'] = true;
                        $senvalue['message'] = 'Batch Adjustment Successfully';
                        $activitynote = 'Batch Adjustment ID: '.$adj_id.'<br><p style="color:red" ><b>Reason:</b> '.$reason.'</p>';
                        $this->useractivities_model->add([
                            'note'=>$activitynote,
                            'location'=>'Purchases->Batch Adjustment->Submit',
                            'product_id'=>$purchase_item->product_id,
                            'action_by'=>$this->session->userdata('user_id')
                        ]);
                    }
                    else{
                        $senvalue['message'] = 'Quantity not Available';
                    }
                }
            }
            else{
                $senvalue['message'] = 'Enter Reason';
            }
        }
        else{
            $senvalue['message'] = 'Permission Denied';
        }
        echo json_encode($senvalue);
    }
    public function deletebatchadj(){
        $sendvalue['codestatus'] = "no";
        $id = $this->input->get('id');
        $reason = $this->input->get('reason');
        if($reason != ""){
            $this->db->select('*');
            $this->db->from('sma_purchase_items');
            $this->db->where('id',$id);
            $q = $this->db->get();
            if($q->num_rows() > 0){
                $data  = $q->result()[0];
                if($data->quantity == $data->quantity_balance){
                    $this->db->delete('sma_purchase_items', array('id' => $data->id));
                    $this->db->set('quantity_balance', 'quantity_balance+'.$data->quantity_balance, FALSE);
                    $this->db->set('quantity_received', 'quantity_received+'.$data->quantity_balance, FALSE);
                    $this->db->where('id', $data->old_purchase_item_id);
                    $this->db->update('sma_purchase_items');
                    $sendvalue['codestatus'] = 'Batch Adjustment Successfully';
                    $activitynote = '<p style="color:red" ><b>Reason:</b> '.$reason.'</p>';
                    $this->useractivities_model->add([
                        'note'=>$activitynote,
                        'location'=>'Purchases->Batch Adjustment->Delete',
                        'product_id'=>$data->product_id,
                        'action_by'=>$this->session->userdata('user_id')
                    ]);
                }
                else{
                    $sendvalue['codestatus'] = "Firstly Delete Sales";
                }
            }
            else{
                $sendvalue['codestatus'] = "Batch Not Found";
            }
        }
        else{
            $sendvalue['codestatus'] = "Enter Reason";
        }
        echo json_encode($sendvalue);
    }
    public function searching_purchase(){
        $q = $this->input->get('q');
        $senddata['results'] = array();
        $this->db->select('id,reference_no as text');
        $this->db->from('sma_purchases');
        $this->db->like('reference_no',$q,'both');
        $this->db->limit(5);
        $q = $this->db->get();
        $senddata['results'] = $q->result();
        echo json_encode($senddata);
    }
    public function searching_sales(){
        $q = $this->input->get('q');
        $senddata['results'] = array();
        $this->db->select('id,reference_no as text');
        $this->db->from('sma_sales');
        $this->db->like('reference_no',$q,'both');
        $this->db->limit(5);
        $q = $this->db->get();
        $senddata['results'] = $q->result();
        echo json_encode($senddata);
    }
    public function searching_supplier(){
        $q = $this->input->get('q');
        $senddata['results'] = array();
        $this->db->select('id,name as text');
        $this->db->from('sma_companies');
        $this->db->where('group_name','supplier');
        $this->db->like('name',$q,'both');
        $this->db->limit(5);
        $q = $this->db->get();
        $senddata['results'] = $q->result();
        echo json_encode($senddata);
    }
    public function searching_customer(){
        $q = $this->input->get('q');
        $senddata['results'] = array();
        $this->db->select('id,name as text');
        $this->db->from('sma_companies');
        $this->db->where('group_name','customer');
        $this->db->like('name',$q,'both');
        $this->db->limit(5);
        $q = $this->db->get();
        $senddata['results'] = $q->result();
        echo json_encode($senddata);
    }
}
