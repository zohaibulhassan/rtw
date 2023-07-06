<?php defined('BASEPATH') or exit('No direct script access allowed');

class General extends CI_Controller
{

    public function warehouses()
    {
        $term = $this->input->get('term');
        $this->db->select('id,name as text');
        $this->db->from('warehouses');
        if ($term != "") {
            $this->db->like('name', $term);
        }
        $this->db->limit(10);
        $q = $this->db->get();
        $data['results'] = $q->result();
        $data['pagination']['more'] = false;
        echo json_encode($data);
    }
    public function brands()
    {
        $term = $this->input->get('term');
        $this->db->select('id,name as text');
        $this->db->from('brands');
        if ($term != "") {
            $this->db->like('name', $term);
        }
        $this->db->limit(10);
        $q = $this->db->get();
        $data['results'] = $q->result();
        $data['pagination']['more'] = false;
        echo json_encode($data);
    }
    public function products()
    {
        $group = $this->input->get('group');
        $term = $this->input->get('term');
        $this->db->select('id,name as text');
        $this->db->from('products');
        if ($group != "") {
            $this->db->where('group_id', $group);
        }
        if ($term != "") {
            $this->db->like('name', $term);
            $this->db->or_like('code', $term);
        }
        $this->db->limit(10);
        $q = $this->db->get();
        $data['results'] = $q->result();
        $data['pagination']['more'] = false;
        echo json_encode($data);
    }
    public function categories()
    {
        $term = $this->input->get('term');
        $dselect = $this->input->get('dselect');
        $this->db->select('id,name as text, "false" as selected');
        $this->db->from('categories');
        $this->db->where('parent_id', 0);
        if ($term != "") {
            $this->db->like('name', $term);
        }
        $this->db->limit(10);
        $q = $this->db->get();
        $rows = $q->result();
        foreach ($rows as $row) {
            if ($row->id == $dselect) {
                $row->selected = true;
            }
            $data['results'][] = $row;
        }
        $data['pagination']['more'] = false;
        echo json_encode($data);
    }
    public function subcategories()
    {
        $term = $this->input->get('term');
        $category = $this->input->get('category');
        $data['results'] = array();
        if ($category != "") {
            $dselect = $this->input->get('dselect');
            $this->db->select('id,name as text, "false" as selected');
            $this->db->from('categories');
            $this->db->where('parent_id', $category);
            if ($term != "") {
                $this->db->like('name', $term);
            }
            $this->db->limit(10);
            $q = $this->db->get();
            $rows = $q->result();
            foreach ($rows as $row) {
                if ($row->id == $dselect) {
                    $row->selected = true;
                }
                $data['results'][] = $row;
            }
        }
        $data['pagination']['more'] = false;
        echo json_encode($data);
    }
    public function groups()
    {
        $term = $this->input->get('term');
        $this->db->select('id,name as text');
        $this->db->from('product_groups');
        if ($term != "") {
            $this->db->like('name', $term);
        }
        $this->db->limit(10);
        $q = $this->db->get();
        $data['results'] = $q->result();
        $data['pagination']['more'] = false;
        echo json_encode($data);
    }
    public function units()
    {
        $term = $this->input->get('term');
        $this->db->select('id,name as text');
        $this->db->from('units');
        if ($term != "") {
            $this->db->like('name', $term);
        }
        $this->db->limit(10);
        $q = $this->db->get();
        $data['results'] = $q->result();
        $data['pagination']['more'] = false;
        echo json_encode($data);
    }
    public function product_tax()
    {
        $term = $this->input->get('term');
        $this->db->select('id,name as text');
        $this->db->from('tax_rates');
        if ($term != "") {
            $this->db->like('name', $term);
        }
        $this->db->limit(10);
        $q = $this->db->get();
        $data['results'] = $q->result();
        $data['pagination']['more'] = false;
        echo json_encode($data);
    }
    public function suppliers()
    {
        $term = $this->input->get('term');
        $this->db->select('id,name as text');
        $this->db->from('companies');
        if ($term != "") {
            $this->db->like('name', $term);
        }
        $this->db->where('group_name', 'supplier');
        $this->db->limit(10);
        $q = $this->db->get();
        $data['results'] = $q->result();
        $data['pagination']['more'] = false;
        echo json_encode($data);
    }
    public function purchases()
    {
        $term = $this->input->get('term');
        $this->db->select('id,reference_no as text');
        $this->db->from('purchases');
        if ($term != "") {
            $this->db->like('reference_no', $term);
        }
        $this->db->limit(10);
        $q = $this->db->get();
        $data['results'] = $q->result();
        $data['pagination']['more'] = false;
        echo json_encode($data);
    }
    public function sales()
    {
        $term = $this->input->get('term');
        $this->db->select('id,name as text');
        $this->db->from('companies');
        if ($term != "") {
            $this->db->like('name', $term);
        }
        $this->db->limit(10);
        $q = $this->db->get();
        $data['results'] = $q->result();
        $data['pagination']['more'] = false;
        echo json_encode($data);
    }
    public function customers()
    {
        $term = $this->input->get('term');
        $this->db->select('id,name as text');
        $this->db->from('companies');
        if ($term != "") {
            $this->db->like('name', $term);
        }
        $this->db->where('group_name', 'customer');
        $this->db->limit(10);
        $q = $this->db->get();
        $data['results'] = $q->result();
        $data['pagination']['more'] = false;
        echo json_encode($data);
    }
    // public function products(){
    //     $term = $this->input->get('term');
    //     $this->db->select('id,name as text');
    //     $this->db->from('products');
    //     if($term != ""){
    //         $this->db->like('name',$term);
    //     }
    //     $this->db->where('status','1');
    //     $this->db->limit(10);
    //     $q = $this->db->get();
    //     $data['results'] = $q->result();
    //     $data['pagination']['more'] = false;
    //     echo json_encode($data);
    // }
    public function searching_products_pos()
    {
        $supplier_id = $this->input->get('supplier_id');
        $term = $this->input->get('term', true);
        $this->db->select('id,CONCAT(name,"  (",code,")") as name');
        $this->db->from('products');
        $this->db->where('(name LIKE "%' . $term . '%" OR code LIKE "%' . $term . '%")');
        $this->db->where('status', 1);
        $this->db->limit(10);
        $q =  $this->db->get();
        $products = array();
        $rows = $q->result();
        foreach ($rows as $row) {
            $products[] = array(
                'id' => sha1($row->id),
                'item_id' => $row->id,
                'label' => $row->name,
                'row' => $row
            );
        }
        echo json_encode($products);
    }




    public function searching_products()
    {
        $supplier_id = $this->input->get('supplier_id');
        $term = $this->input->get('term', true);
        $this->db->select('id,CONCAT(name,"  (",code,")") as name');
        $this->db->from('products');
        $this->db->where('(name LIKE "%' . $term . '%" OR code LIKE "%' . $term . '%")');
        if ($supplier_id != "") {
            $this->db->where('(supplier1 = ' . $supplier_id . ' OR supplier2 = ' . $supplier_id . ' OR supplier3 = ' . $supplier_id . ' OR supplier4 = ' . $supplier_id . ' OR supplier5 = ' . $supplier_id . ')');
        }
        $this->db->where('status', 1);
        $this->db->limit(10);
        $q =  $this->db->get();
        $products = array();
        $rows = $q->result();
        foreach ($rows as $row) {
            $products[] = array(
                'id' => sha1($row->id),
                'item_id' => $row->id,
                'label' => $row->name,
                'row' => $row
            );
        }
        echo json_encode($products);
    }



    public function searching_products2()
    {
        $supplier_id = $this->input->get('supplier_id');
        $req = $this->input->get('term', true);
        $term = $req['term'];
        $this->db->select('
            id,
            CONCAT(name,"  (",code,")") as text,
        ');
        $this->db->from('products');
        $this->db->where('(name LIKE "%' . $term . '%" OR code LIKE "%' . $term . '%")');
        // $this->db->where('(supplier1 = '.$supplier_id.' OR supplier2 = '.$supplier_id.' OR supplier3 = '.$supplier_id.' OR supplier4 = '.$supplier_id.' OR supplier5 = '.$supplier_id.')');
        $this->db->where('status', 1);
        $this->db->limit(10);
        $q =  $this->db->get();
        $products = array();
        $products['results'] = $q->result();
        echo json_encode($products);
    }


    public function searching_products3()
    {
        $this->load->database();
        $req = $this->input->get('term', true);
        $term = $req['term'];
        $this->db->select('
            id,
            CONCAT(name,"  (",code,")") as text,
        ');
        $this->db->from('products');
        $this->db->where('(name LIKE "%' . $term . '%" OR code LIKE "%' . $term . '%")');
        // $this->db->where('(supplier1 = '.$supplier_id.' OR supplier2 = '.$supplier_id.' OR supplier3 = '.$supplier_id.' OR supplier4 = '.$supplier_id.' OR supplier5 = '.$supplier_id.')');
        $this->db->where('status', 1);
        $this->db->limit(10);
        $q =  $this->db->get();
        $products = array();
        $products['results'] = $q->result();
        echo json_encode($products);
    }


    public function select_products()
    {
        $sendvalue['codestatus'] = false;
        $id = $this->input->get('id');
        $warehouse_id = $this->input->get('warehouse_id');
        // $warehouse_id = 4;
        $this->db->select('
            products.id,
            products.code,
            products.company_code,
            products.name,
            products.cost,
            products.price,
            products.carton_size,
            products.formulas,
            products.mrp,
            products.alert_quantity,
            COALESCE((
                SELECT SUM(sma_purchase_items.quantity_balance) FROM sma_purchase_items WHERE sma_purchase_items.product_id = ' . $id . ' AND sma_purchase_items.warehouse_id = ' . $warehouse_id . '
            ),0) as balance_qty,
            products.fed_tax,
            products.tax_method,
            tax_rates.name as tax_name,
            tax_rates.rate as tax_rate,
            tax_rates.type as tax_type,
            0 as product_tax,
            1 as quantity,
            "" as batch,
            "" as expiry
        ');
        $this->db->from('products as products');
        $this->db->join('tax_rates', 'tax_rates.id = products.tax_rate', 'left');
        $this->db->where('products.id', $id);
        $q =  $this->db->get();
        if ($q->num_rows() > 0) {
            $products = $q->result()[0];
            if ($products->tax_type == 1) {
                $products->product_tax = amountformate((($products->cost / 100) * $products->tax_rate));
            } else {
                $products->product_tax = amountformate($products->tax_rate);
            }
            $sendvalue['products'] = $products;
            $sendvalue['codestatus'] = true;
        }
        echo json_encode($sendvalue);
    }
    public function select_products2()
    {
        $sendvalue['codestatus'] = false;
        $id = $this->input->get('id');
        $warehouse_id = $this->input->get('warehouse_id');
        // $warehouse_id = 4;
        $this->db->select('
            products.id,
            products.code,
            products.company_code,
            products.name,
            products.cost,
            products.price,
            products.carton_size,
            products.formulas,
            products.mrp,
            products.alert_quantity,
            products.fed_tax,
            products.tax_method,
            tax_rates.name as tax_name,
            tax_rates.rate as tax_rate,
            tax_rates.type as tax_type,
            0 as product_tax,
            1 as quantity,
            "" as batch,
            "" as expiry

        ');
        $this->db->from('products as products');
        $this->db->join('tax_rates', 'tax_rates.id = products.tax_rate', 'left');
        $this->db->where('products.id', $id);
        $q =  $this->db->get();
        if ($q->num_rows() > 0) {
            $products = $q->result()[0];
            if ($products->tax_type == 1) {
                $products->product_tax = amountformate((($products->cost / 100) * $products->tax_rate));
            } else {
                $products->product_tax = amountformate($products->tax_rate);
            }
            $sendvalue['products'] = $products;
            $sendvalue['codestatus'] = true;
        }
        echo json_encode($sendvalue);
    }
}
