<?php defined('BASEPATH') OR exit('No direct script access allowed'); //Write by Ismail FSD

require_once FCPATH . "vendor/woocommerce_api/vendor/autoload.php";
use Automattic\WooCommerce\Client;

class Wordpresswoocommerce_model extends CI_Model
{
    
    public function __construct(){
        parent::__construct();
    }

    // New Code

    public function update_product_detail($id){
        $this->db->select('
            p.id,
            p.code,
            p.name,
            p.price,
            p.category_id ,
            p.prescription,
            f.name as foumula,
            ff.name as form_name,
            fs.name as strength_name,
        ');
        $this->db->from('products as p');
        $this->db->join('product_formulas as f','f.id = p.formulas','left');
        $this->db->join('formula_forms as ff','ff.id = f.form_id','left');
        $this->db->join('formula_forms as fs','fs.id = f.strength_id','left');
        $this->db->where('p.id',$id);
        $q = $this->db->get();
        if($q->num_rows() > 0){
            $product = $q->row();
            $this->db->select('
                sp.*,
                store.wordpress_wocommerce_consumer_key,
                store.wordpress_wocommerce_consumer_secret,
                store.types,
                store.store_url,
                store.status as store_status
            ');
            $this->db->from('store_products_tb as sp');
            $this->db->join('stores_tb as store','store.id = sp.store_id','left');
            $this->db->where('sp.product_id',$id);
            $q2 = $this->db->get();
            $sproducts = $q2->result();
            // echo '<pre>';
            // echo '<p>Product Detail</p>';
            // print_r($product);
            // echo '<p>Store Product Detail</p>';
            // print_r($sproducts);

            foreach($sproducts as $p){
                $name = str_replace("?"," ",$product->name);
                $sendproduct = array();
                $sendproduct['name'] = $name;
                $sendproduct['meta_data'] = array();
                $sendproduct['meta_data'][] = array(
                    'key' => 'meta-prescription-post',
                    'value' => $product->prescription == "1" ? 'yes' : 'no'
                );
                $sendproduct['meta_data'][] = array(
                    'key' => 'ibs_formula_products_select_formula_name',
                    'value' => $product->foumula
                );
                $sendproduct['meta_data'][] = array(
                    'key' => 'ibs_formula_products_select_formula_form',
                    'value' => $product->form_name
                );
                $sendproduct['meta_data'][] = array(
                    'key' => 'ibs_formula_products_select_formula_strength',
                    'value' => $product->strength_name
                );
                // echo '<p>Send Data</p>';
                // print_r($sendproduct);
                $sendproduct = json_encode($sendproduct);
                $curl = curl_init();
                $url = $p->store_url."wp-json/wc/v3/products/".$p->store_product_id."?consumer_key=".$p->wordpress_wocommerce_consumer_key;
                $url .= "&consumer_secret=".$p->wordpress_wocommerce_consumer_secret;
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'PUT',
                    CURLOPT_POSTFIELDS =>$sendproduct,
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Cookie: wfwaf-authcookie-25379d3ccc77bd732cca60f2ba39394c=1%7Cadministrator%7C4368694d3942e8a1815458cb0ac1ce125a5748635fe7488d5f4ab96d78b25107'
                    ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                $replay = json_decode($response);
                // echo '<p>Response</p>';
                // print_r($replay);
            }
            
            
    



        }
    }

    // old code
    public function updateProduct($res){
        $sendvalue['codestatus'] = "no";
        try {
            if($res->integration_type == "full" || $res->integration_type == "qty" || $res->integration_type == "priceqty"){
                $data['stock_quantity'] = (int)$res->updateqty;
                $data['manage_stock'] = 1;
                if($data['stock_quantity'] > 0 ){
                    $data['stock_status'] = 'instock';
                }
                else{
                    $data['stock_status'] = 'outofstock';
                }
            }
            if($res->integration_type == "full" || $res->integration_type == "price" || $res->integration_type == "priceqty"){
                if($res->updatemrpprice == $res->updateprice){
                    $data['regular_price'] = (string)$res->updateprice;
                    $data['sale_price'] = '';
                }
                else{
                    $data['regular_price'] = (string)$res->updatemrpprice;
                    $data['sale_price'] = (string)$res->updateprice;
                }
                // $data['sale_price'] = $this->roundno($res->updateprice);
            }
            if($res->integration_type == "full"){
                $data['name'] = $res->name;
                $data['short_description'] = $res->product_details;
                if($res->status == 1 || $res->status == "1"){
                    $data['status'] = 'publish';
                }
                else{
                    $data['status'] = 'draft';
                }
            }
            $store['url'] = $res->store_url;
            $store['wwck'] = $res->wordpress_wocommerce_consumer_key;
            $store['wwcs'] = $res->wordpress_wocommerce_consumer_secret;
            $returnwo = $this->curlUpdateStoreProduct($store,$res->store_product_id, $data);

            // $sendvalue = "Updated";
            $sendvalue['codestatus'] = "Updated";
            $sendvalue['storecode'] = $res;
            $sendvalue['woo'] = $returnwo;
        }
        //catch exception
        catch(Exception $e) {
            // $sendvalue = "Try Again: ".$e->getMessage();
            // $sendvalue = "Try Again: ";
            $sendvalue['codestatus'] = "Try Again: ".$e->getMessage();
            $sendvalue['storecode'] = $res;
        }
        return $sendvalue;

    }
    public function updateProductDetail($res,$spid){
        $sendvalue['codestatus'] = 'no';
        try {
            // $woocommerce = new Client(
            //     $res['store_url'], 
            //     $res['wordpress_wocommerce_consumer_key'], 
            //     $res['wordpress_wocommerce_consumer_secret'],
            //     [
            //         'version' => 'wc/v3',
            //     ]
            // );
            // $returndata = $woocommerce->put('products/'.$spid, $res['product']);
            $store['url'] = $res['store_url'];
            $store['wwck'] = $res['wordpress_wocommerce_consumer_key'];
            $store['wwcs'] = $res['wordpress_wocommerce_consumer_secret'];

            $returndata = $this->curlUpdateStoreProduct($store,$spid,$res['product']);

            $sendvalue['codestatus'] = "ok";
            $sendvalue['productdata'] = $returndata;      
        }
        //catch exception
        catch(Exception $e) {
            $sendvalue['codestatus'] = "Store Request Failed: ".$e->getMessage();
        }
        return $sendvalue;
    }
    public function curlUpdateStoreProduct($res,$spid,$pdata){
        $data = json_encode($pdata);
        $curl = curl_init();
        $url = $res['url']."wp-json/wc/v3/products/".$spid."?consumer_key=".$res['wwck'];
        $url .= "&consumer_secret=".$res['wwcs'];
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS =>$data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: wfwaf-authcookie-25379d3ccc77bd732cca60f2ba39394c=1%7Cadministrator%7C4368694d3942e8a1815458cb0ac1ce125a5748635fe7488d5f4ab96d78b25107'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }
    public function roundno($no){
        $rno = round($no);
        if($rno<$no){
            $rno += 1;
        }
        return (string)$rno;
    }
    public function createProduct($res){
        $sendvalue['codestatus'] = 'no';
        try {
            $data['name'] = $res['product']['name'];
            $data['type'] = $res['product']['type'];
            $data['manage_stock'] = true;
            $data['regular_price'] = $res['product']['regular_price'];
            $data['short_description'] = $res['product']['short_description'];
            // $woocommerce = new Client(
            //     $res['store_url'], 
            //     $res['wordpress_wocommerce_consumer_key'], 
            //     $res['wordpress_wocommerce_consumer_secret'],
            //     [
            //         'version' => 'wc/v3',
            //     ]
            // );
            // $returndata = $woocommerce->post('products', $data);
            $store['url'] = $res['store_url'];
            $store['wwck'] = $res['wordpress_wocommerce_consumer_key'];
            $store['wwcs'] = $res['wordpress_wocommerce_consumer_secret'];
            $returndata = $this->curlCreateProduct($store,$data);
            $sendvalue['codestatus'] = "Created";
            $sendvalue['productdata'] = $returndata;      
        }
        //catch exception
        catch(Exception $e) {
            $sendvalue['codestatus'] = "Try Again: ".$e->getMessage();
        }
        return $sendvalue;
    }
    public function newProduct($res){
        $sendvalue['codestatus'] = 'no';
        try {
            // $woocommerce = new Client(
            //     $res['store_url'], 
            //     $res['wordpress_wocommerce_consumer_key'], 
            //     $res['wordpress_wocommerce_consumer_secret'],
            //     [
            //         'version' => 'wc/v3',
            //     ]
            // );
            // $returndata = $woocommerce->post('products', $res['product']);
            $store['url'] = $res['store_url'];
            $store['wwck'] = $res['wordpress_wocommerce_consumer_key'];
            $store['wwcs'] = $res['wordpress_wocommerce_consumer_secret'];
            $returndata = $this->curlCreateProduct($store,$res['product']);
            $sendvalue['codestatus'] = "ok";
            $sendvalue['productdata'] = $returndata;      
        }
        //catch exception
        catch(Exception $e) {
            $sendvalue['codestatus'] = "Store Request Failed: ".$e->getMessage();
        }
        return $sendvalue;
    }
    public function curlCreateProduct($res,$data){
        $curl = curl_init();
        $url = $res['url']."wp-json/wc/v3/products?consumer_key=".$res['wwck'];
        $url .= "&consumer_secret=".$res['wwcs'];
        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => $data,
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }
    public function deleteProduct($res,$spid){
        $sendvalue['codestatus'] = 'no';
        try {
            // $woocommerce = new Client(
            //     $res['store_url'], 
            //     $res['wordpress_wocommerce_consumer_key'], 
            //     $res['wordpress_wocommerce_consumer_secret'],
            //     [
            //         'version' => 'wc/v3',
            //     ]
            // );
            // $returndata = $woocommerce->delete('products/'.$spid, ['force' => true]);
            $store['url'] = $res['store_url'];
            $store['wwck'] = $res['wordpress_wocommerce_consumer_key'];
            $store['wwcs'] = $res['wordpress_wocommerce_consumer_secret'];
            $returndata = $this->curlDeleteProduct($store,$spid);
            $sendvalue['codestatus'] = "ok";
            $sendvalue['productdata'] = $returndata;
        }
        //catch exception
        catch(Exception $e) {
            $sendvalue['codestatus'] = "Store Request Failed: ".$e->getMessage();
        }
        return $sendvalue;
    }
    public function curlDeleteProduct($res,$spid){
        $curl = curl_init();
        $url = $res['url']."wp-json/wc/v3/products/".$spid."?consumer_key=".$res['wwck'];
        $url .= "&consumer_secret=".$res['wwcs'];
        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'DELETE',
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }
    public function createWebHook($store_id,$data){
        $sendvalue = 0;
        $note = '';
        $this->db->select('*');
        $this->db->from('sma_stores_tb');
        $this->db->where('id',$store_id);
        $q = $this->db->get();
        if($q->num_rows() > 0){
            $store = $q->result()[0];
            try {
                // $woocommerce = new Client(
                //     $store->store_url, 
                //     $store->wordpress_wocommerce_consumer_key, 
                //     $store->wordpress_wocommerce_consumer_secret, 
                //     [
                //         'version' => 'wc/v3',
                //     ]
                // );
                // $returndata = $woocommerce->post('webhooks', $data);
                $res['url'] = $store->store_url;
                $res['wwck'] = $store->wordpress_wocommerce_consumer_key;
                $res['wwcs'] = $store->wordpress_wocommerce_consumer_secret;
                $returndata = $this->curlCreateWebHook($res,$data);
                $sendvalue = $returndata->id;
                $note = 'Create Webwook Request Webhook ID: '.$sendvalue.' URL: '.$data['delivery_url'];
            }
            //catch exception
            catch(Exception $e) {
                $sendvalue = 0;
                // echo "Store Request Failed: ".$e->getMessage();
                $note = 'Create Webwook Request: '.$e->getMessage();
            }
        }
        return $sendvalue;
    }
    public function curlCreateWebHook($res,$data){
        $curl = curl_init();
        $url = $res['url']."wp-json/wc/v3/webhooks?consumer_key=".$res['wwck'];
        $url .= "&consumer_secret=".$res['wwcs'];
        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => $data,
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }
    public function getproducts($req,$page = 1,$limit = 100){
        $sendvalue['codestatus'] = false;
        try {
            $products = $this->curlProducts($req,$page,$limit);
            // $woocommerce = new Client(
            //     $req->store_url, 
            //     $req->wordpress_wocommerce_consumer_key, 
            //     $req->wordpress_wocommerce_consumer_secret, 
            //     [
            //         'version' => 'wc/v3',
            //     ]
            // );
            // $products = $woocommerce->get('products',array('per_page' => $limit, 'page' => $page, 'status' => 'publish'));
            $sendvalue['products'] = $products;
            $sendvalue['codestatus'] = true;
            $sendvalue['message'] = "Successfully";
        }
        //catch exception
        catch(Exception $e) {
            $sendvalue['message'] = "Store Request Failed: ".$e->getMessage();
        }
        return $sendvalue;
    }
    public function curlProducts($req,$page = 1,$limit = 100){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $req->store_url."wp-json/wc/v3/products?consumer_key=".$req->wordpress_wocommerce_consumer_key."&consumer_secret=".$req->wordpress_wocommerce_consumer_secret."&per_page=".$limit."&page=".$page."&status=publish",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response);
    }
    public function getorder($req,$page = 1,$product = 0,$status='any',$limit = 100){
        $sendvalue['codestatus'] = false;
        try {
            // $woocommerce = new Client(
            //     $req->store_url, 
            //     $req->wordpress_wocommerce_consumer_key, 
            //     $req->wordpress_wocommerce_consumer_secret, 
            //     [
            //         'version' => 'wc/v3',
            //     ]
            // );
            // $where['per_page'] = $limit;
            // $where['page'] = $page;
            // if($product != 0){
            //     $where['product'] = $product;
            // }
            // $where['status'] = $status;
            // $orders = $woocommerce->get('orders',$where);
            $orders = $this->curlOrders($req,$page,$product,$status,$limit);
            $sendvalue['orders'] = $orders;
            $sendvalue['codestatus'] = true;
        }
        //catch exception
        catch(Exception $e) {
            $sendvalue['message'] = "Store Request Failed: ".$e->getMessage();
        }
        return $sendvalue;
    }
    public function curlOrders($req,$page = 1,$product = 0,$status='any',$limit = 100){
        $curl = curl_init();
        $url = $req->store_url."wp-json/wc/v3/orders?consumer_key=".$req->wordpress_wocommerce_consumer_key;
        $url .= "&consumer_secret=".$req->wordpress_wocommerce_consumer_secret;
        $url .= "&per_page=".$limit;
        $url .= "&page=".$page;
        if($product != 0){
            $url .= "&product=".$product;
        }
        $url .= "&status=".$status;
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }
}
