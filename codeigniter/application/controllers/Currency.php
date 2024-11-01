<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Currency extends My_Controller {

	public function __construct(){
		parent::__construct();

        $this->load->model('currency_m');
	}
    
    
	public function index()
	{
	   
        $query = $this->db->get('wp_options');
        foreach ($query->result() as $row)
        {
            dump($row);
        }
       
		$this->load->view('welcome_message');
	}
    
	public function manage()
	{
        // Fetch all results
        $this->data['results'] = array();
       
        // Load view
		$this->data['subview'] = 'admin/currency/manage';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function importeu()
    {
        $xml_url = 'http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml';

        echo '<pre>';
        echo __('Sync from', 'sw_win').': '.$xml_url;
        echo '<br style="clear:both;"/>';
        echo '<br style="clear:both;"/>';

        $args = array(
            'user-agent'  =>  'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8) AppleWebKit/535.6.2 (KHTML, like Gecko) Version/5.2 Safari/535.6.2',
        ); 

        $xmlhd = wp_remote_get(esc_url_raw( $xml_url ), $args);
        $xmlhd = wp_remote_retrieve_body($xmlhd);

        $xml = @simplexml_load_string($xmlhd);
        if ($xml === false) {
            echo '<span style="color:red;">'.__('Failed loading XML', 'sw_win').'</span><br>';
            foreach(libxml_get_errors() as $error) {
                echo "<br>", $error->message;
            }
        } else {
            echo __('Loading XML successfully', 'sw_win');
            echo '<br style="clear:both;"/>';
            
            $c1 = $xml->Cube;
            $c2 = $c1->Cube;
            foreach($c2->children() as $currency) {
                //dump($currency);
                $currency_db = $this->currency_m->get_by(array('currency_code'=>$currency['currency']), true);
                
                if(!empty($currency_db))
                {
                    $this->currency_m->save(array('rate_index'=>$currency['rate']), $currency_db->idcurrency);
                    echo '<span style="color:green;">'.__('Updated', 'sw_win').': '.$currency['currency'].', '.$currency['rate'].'</span><br>';
                }
                else
                {
                    $this->currency_m->save(array('currency_code'=>$currency['currency'], 'rate_index'=>$currency['rate']));
                    echo '<span style="color:blue;">'.__('Inserted', 'sw_win').': '.$currency['currency'].', '.$currency['rate'].'</span><br>';
                }
            } 
        }
        
        echo '</pre>';
    }

	public function addcurrency($id=NULL)
	{
        // Get parameters
        $id = $this->input->get('id');
       
        // Set up the form
        if(empty($id))
        {
        }
        else
        {
            $this->data['form_object'] = $this->currency_m->get($id);
        }

        $rules = $this->currency_m->form_admin;
        
        $this->form_validation->set_rules($rules);
        
        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            $data = $this->currency_m->array_from_post($this->currency_m->get_post_from_rules($rules));

            $id = $this->currency_m->save($data, $id);
            
            wp_redirect(admin_url("admin.php?page=currency_manage&function=addcurrency&id=$id&updated=true")); exit;
        }
        
        // Load view
		$this->data['subview'] = 'admin/currency/addcurrency';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function remcurrency($id = NULL, $redirect='1')
	{   
        // Get parameters
        $id = $this->input->get('id');
        
        if(is_numeric($id))
            $this->currency_m->delete($id);
	}
    
    // json for datatables
    public function datatable()
    {
        // configuration
        $columns = array('idcurrency', 'currency_symbol', 'currency_code', 'rate_index' );
        $controller = 'currency';
        
        // Fetch parameters
        $parameters = $this->input->post();
        $draw = $this->input->post_get('draw');
        $start = $this->input->post_get('start');
        $length = $this->input->post_get('length');
        $search = $this->input->post_get('search');

        if(isset($search['value']))
            $parameters['searck_tag'] = $search['value'];
        
        $recordsTotal = $this->{$controller.'_m'}->total_lang(array(), sw_current_language_id());
        
        prepare_search_query_GET($columns, $controller.'_m');
        $recordsFiltered = $this->{$controller.'_m'}->total_lang(array(), sw_current_language_id());
        
        prepare_search_query_GET($columns, $controller.'_m');
        $data = $this->{$controller.'_m'}->get_pagination_lang($length, $start, sw_current_language_id());
        
        $query = $this->db->last_query();
        
        // Add buttons
        foreach($data as $key=>$row)
        {
            $row->edit = btn_edit(admin_url("admin.php?page=currency_manage&function=addcurrency&id=".$row->{"id$controller"}));
            $row->delete = btn_delete_noconfirm(admin_url("admin.php?page=currency_manage&function=remcurrency&id=".$row->{"id$controller"}));
            
            foreach($columns as $val)
            {
                if(isset($row->$val))
                {
                    // Customize is_readed value preview, add title
                    if($val == 'idcurrency')
                    {
                        if($row->is_activated == 1)
                        {
                           $row->$val .= '&nbsp;<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>';
                        } else {
                           $row->$val .= '&nbsp;<span class="label label-danger">'.__("Not activated", "sw_win").'</span>';       
                        }
                    }
                }
                elseif(isset($row->json_object))
                {
                    $json = json_decode($row->json_object);
                    if(isset($json->$val))
                    {
                        $row->$val = $json->$val;
                    }
                    else
                    {
                        $row->$val = '-';
                    }
                }
                else
                {
                    $row->$val = '-';
                }
            }
            
        }

        //format array is optional
        $json = array(
                "parameters" => $parameters,
                "query" => $query,
                "draw" => $draw,
                "recordsTotal" => $recordsTotal,
                "recordsFiltered" => $recordsFiltered,
                "data" => $data
                );

        //$length = strlen(json_encode($data));
        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache');
        header('Content-Type: application/json; charset=utf8');
        //header('Content-Length: '.$length);
        echo json_encode($json);
        
        exit();
    }
    
}
