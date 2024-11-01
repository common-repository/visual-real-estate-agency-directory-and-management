<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Treefield extends My_Controller {

	public function __construct(){
		parent::__construct();
        
        $this->load->model('file_m');
        $this->load->model('field_m');
        $this->load->model('treefield_m');
        $this->load->model('repository_m');
        $this->load->model('listing_m');
        $this->load->model('dependentfield_m');
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
    
	public function categories()
	{
        $this->data['field_id'] = 1;
       
        // [START]Table datamodel
        
        $this->data['treefield_table'] = $this->treefield_m->get_table_tree(sw_current_language_id(), $this->data['field_id'], 0);
        
        // [END] Table datamodel
       
        // Load view
		$this->data['subview'] = 'admin/treefield/categories';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
	public function locations()
	{
        $this->data['field_id'] = 2;
        
        // [START]Table datamodel
        
        $this->data['treefield_table'] = $this->treefield_m->get_table_tree(sw_current_language_id(), $this->data['field_id'], 0);
        
        // [END] Table datamodel
       
        // Load view
		$this->data['subview'] = 'admin/treefield/locations';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
	public function addvalue($id=NULL)
	{
	    wp_enqueue_media();
        $this->data['agents'] = array();
       
        // Get parameters
        $id = $this->input->get('id');
        $field_id = $this->input->get('field_id');
        $wp_page = $this->input->get('page');
        $this->data['wp_page'] = $wp_page;
        
        // Set up the form
        if(empty($id))
        {

        }
        else
        {
            $this->data['form_object'] = $this->treefield_m->get_lang($id);
            
            $field_id = $this->data['form_object']->field_id;
        }
        
        $this->data['field_id'] = $field_id;
        $this->data['treefield_dropdown'] = $this->treefield_m->get_treefield(sw_current_language_id(), $field_id, $id);
        $this->data['fields_under_selected'] = $this->dependentfield_m->get_fields_under(sw_current_language_id(), 0);
        
        $this->data['item'] = $this->dependentfield_m->get_by(array('treefield_id'=>$id, 'field_id'=>$field_id), TRUE);
        
        // Fetch hidden fields
        if(!empty($this->data['item']->hidden_fields_list))
        {
            foreach(explode(',', $this->data['item']->hidden_fields_list) as $f_id)
            {
                $this->data['item']->{'field_'.$f_id} = '1';
            }
        }
        
        $rules = $this->treefield_m->form_admin;
        $rules_lang = $this->treefield_m->rules_lang;
        
        $this->form_validation->set_rules(array_merge($rules, $rules_lang));
        
        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            $data = $this->treefield_m->array_from_post($this->treefield_m->get_post_from_rules($rules));
            
            if($id == NULL)
            {
                $parent_id = $this->input->post('parent_id');
                $data['order'] = $this->treefield_m->max_order($parent_id);
                $data['field_id'] = $field_id;
            }
            
            $data_lang = $this->treefield_m->array_from_post($this->treefield_m->get_lang_post_fields());

            $multi_add = false;
            $multi_values = array();
            $i=0;
            
            foreach($data_lang as $key=>$value)
            {
                if(substr($key, 0, 5) != 'value')continue;
                if(substr_count($value, ',')>0)
                {
                    foreach(explode(',', $value) as $key_1=>$value_l)
                    {
                        $multi_values[$key_1][$key] = $value_l;
                    }
                    $multi_add = true;
                }
                $i++;
            }
            
            // [Hidden fields]
            $hidden_fields_list = array();
            
            foreach($this->data['fields_under_selected'] as $field)
            {
                if(!isset($_POST['field_'.$field->idfield]))
                    $hidden_fields_list[] = $field->idfield;
            }
            
            $data_h = array();
            $data_h['field_id'] = $field_id;
            
            $data_h['hidden_fields_list'] = implode(',', $hidden_fields_list);
            
            // [/Hidden fields]
            $iddependentfields = NULL;
            
            if(isset($this->data['item']->iddependentfields))
                $iddependentfields = $this->data['item']->iddependentfields;
            
            if($multi_add)
            {
                foreach($multi_values as $l_data)
                {
                    $data['order'] = $this->treefield_m->max_order($parent_id);
                    $treefield_id = $this->treefield_m->save_with_lang($data, $l_data, NULL, NULL);
                    
                    $data_h['treefield_id'] = $treefield_id;
                    
                    if($this->data['field_id'] == 1)
                        $id_dependent_field = $this->dependentfield_m->save($data_h, $iddependentfields);
                }
                
                if(empty($treefield_id))
                {
                    exit(__('Multi insert failed','sw_win'));
                }
                else
                {
                    redirect(admin_url("admin.php?page=$wp_page&function=addvalue&field_id=$field_id&updated=true"));
                }
            }
            else
            {
                $id = $this->treefield_m->save_with_lang($data, $data_lang, $id);
                
                $data_h['treefield_id'] = $id;
                
                if($this->data['field_id'] == 1)
                $id_dependent_field = $this->dependentfield_m->save($data_h, $iddependentfields);
            }

            wp_redirect(admin_url("admin.php?page=$wp_page&function=addvalue&id=$id&updated=true")); exit;
        }
        
        // Load view
		$this->data['subview'] = 'admin/treefield/addvalue';
        $this->load->view('admin/_layout_main', $this->data);
	}

    public function addvalue_from_svg($id=NULL)
	{
        wp_enqueue_media();
        $this->data['agents'] = array();
       
        // Get parameters
        $id = $this->input->get('id');
        $field_id = $this->input->get('field_id');
        $wp_page = $this->input->get('page');
        $this->data['wp_page'] = $wp_page;
        
        $this->data['errors_svg'] = array();
        $this->data['geo_map_prepared'] = array();
        
        $errors_svg = array();
        $geo_map_prepared = array();
        $svg_path = SW_WIN_GEOMAP_PLUGIN_PATH.'/svg_maps/';
        if( file_exists($svg_path)) {
            $svg_files = array_diff( scandir($svg_path), array('..', '.'));
        
            foreach ($svg_files  as $svg) {
                $sql_o = file_get_contents($svg_path.$svg);
                $match = '';
                preg_match_all('/(data-title-map)=("[^"]*")/i', $sql_o, $match);
                
                if(!empty($match[2])) {
                    $geo_map_prepared[$svg] = trim(str_replace('"', '', $match[2][0]));
                } else if(stristr($sql_o, "http://amcharts.com/ammap") !== FALSE ) {
                    $geo_map_prepared[$svg] = 'undefined';
                    $match='';
                    preg_match_all('/(SVG map) of ([^"]* -)/i', $sql_o, $match2);
                    if(!empty($match2) && isset($match2[2][0])) {
                        $title = str_replace(array(" -","High","Low"), '', $match2[2][0]);
                        $geo_map_prepared[$svg] = trim($title);
                    }
                }
                else {
                    $errors_svg[] = "<p class='alert alert-danger alert-dismissible'>Map ".$svg." is not formatted correctly</p>";
                }
            }
        }
        
        asort($geo_map_prepared);
        $this->data['geo_map_prepared'] = $geo_map_prepared;
        $this->data['errors_svg'] = $errors_svg;
        
        $this->form_validation->set_rules('geo_map', "lang: Map", 'trim|required');
        
        if($this->form_validation->run()) {
            
            if(config_item('app_type') == 'demo')
            {
                echo "<span style=\"color:red;border:1px solid red;padding:5px;\">Map generate disabled in demo.</span>";
                return;
            }
            
            $geo_map = $this->input->post('geo_map'); 

            $accept_generate = false;
            if($this->input->post('accept_generate') && $this->input->post('accept_generate')== 1){
               $accept_generate = true;
            } 
            
            $random_locations = false;
            if($this->input->post('random_locations') && $this->input->post('random_locations')== 1){
               $random_locations = true;
            } 
            
            if($accept_generate) {
                if( file_exists($svg_path.$geo_map)) {
                        
                    // replace default svg
                    $svg = file_get_contents($svg_path.$geo_map);

                        
                                        
                    /* changed map from $match2 */
                    if(stristr($svg, "http://amcharts.com/ammap") != FALSE ) {
                        $svg = str_replace('title', 'data-name', $svg);
                        $match2='';
                        preg_match_all('/(SVG map) of ([^"]* -)/i', $svg, $match2);
                        if(!empty($match2) && isset($match2[2][0])) {
                            $title='';
                            $title = str_replace(array(" -","High","Low"), '', $match2[2][0]);
                            $title = trim($title);
                            $svg = str_replace('<svg', '<svg data-title-map="'.trim($title).'"', $svg);
                        }
                        
                    }
                    /* end changed map from $match2 */
                    
                    /* clear */
                    $locations_list_vl_0 = $this->treefield_m->get_by(array('field_id'=>'2', 'parent_id'=>0));
                    if(!empty($locations_list_vl_0))
                    foreach ($locations_list_vl_0 as $key => $value) {
                        $this->treefield_m->delete($value->idtreefield);
                    }
        
                    $langs_object = sw_get_languages();
                    $treefield_data_dynamic = array();
                    
                    /* multimap */
                    if(stripos($svg, 'data-map-type="multimap"') !== FALSE || stripos($svg, "data-map-type='multimap'") !== FALSE) {
                        
                        $treefield_array = array();
                        $treefield_lvl_0_id = array();
                        
                        $dom = new DOMDocument();
                        $dom->preserveWhiteSpace = false; 
                        $dom->formatOutput = true; 
                        $dom->loadXml($svg);
                        /* set version */
                        $root_svg = $dom->getElementsByTagName('svg')->item(0);
                        $root_svg->setAttribute('data-sw_geomodule-version', '2.0');
                         
                        $paths = $dom->getElementsByTagName('path'); //here you have an corresponding object
                        foreach ($paths as $path) {
                            $lvl_0 = $path->getAttribute('data-name-lvl_0');
                            $lvl_1 = $path->getAttribute('data-name');
                            if(($lvl_0 && !empty($lvl_0)) && ($lvl_1 && !empty($lvl_1))){
                                $lvl_0 = trim($lvl_0);
                                $lvl_1 = trim($lvl_1);
                                
                                $path->setAttribute('data-name-lvl_0', $lvl_0);
                                $path->setAttribute('data-name-lvl_1', $lvl_1);
                                $path->setAttribute('data-name', $lvl_1.', '.$lvl_0);
                                
                                /* if first added to root */
                                if(!isset($treefield_array[$lvl_0])) {
                                    $data = array
                                    (
                                        'parent_id' => 0,
                                        'template' => 'treefield_treefield',
                                        'level'=>0
                                    );
                                    
                                    $data_lang= array();
                                    foreach ( $langs_object as $key => $v) {
                                        $data_lang['value_'.$v->id] = $lvl_0;
                                    }
                                    $treefield_id = $this->treefield_m->save_with_lang($data, $data_lang, $field_id);
                                    $treefield_lvl_0_id[$lvl_0]=$treefield_id;
                                }
                                
                                $treefield_array[$lvl_0][]=$lvl_1;
                            }
                        }     
                        
                        foreach ($treefield_array as $root => $v) {
                            foreach ($v as $key=>$value) {
                                $data = array(
                                    'parent_id' => $treefield_lvl_0_id[$root],
                                    'template' => 'treefield_treefield'
                                );

                                $data_lang= array();
                                foreach ( $langs_object as $lang_ob) {
                                    $data_lang['value_'.$value['id']] = $value;
                                }
                                $treefield_data_dynamic[] = $root.' - '.$value. ' -';
                                $treefield_id = $this->treefield_m->save_with_lang($data, $data_lang, $field_id);
                            }
                        }
                        $svg= $dom->saveXML();
                    } else {
                    
                    $data = array
                        (
                            'parent_id' => 0,
                            'template' => 'treefield_treefield',
                            'level'=>0,
                            'field_id'=>$field_id
                        );

                    
                    $data_lang= array();
                    foreach ( $langs_object as $key => $value) {
                        $data_lang['value_'.$value['id']] = $this->data['geo_map_prepared'][$geo_map];
                    }
                    
                    $treefield_root_id = $this->treefield_m->save_with_lang($data, $data_lang);
                    
                    $root_name = $this->data['geo_map_prepared'][$geo_map];
                    $treefield_array = array();
                    $treefield_lvl_0_id = array();

                    $dom = new DOMDocument();
                    $dom->preserveWhiteSpace = false; 
                    $dom->formatOutput = true; 
                    $dom->loadXml($svg);
                    
                    /* set version */
                    $root_svg = $dom->getElementsByTagName('svg')->item(0);
                    $root_svg->setAttribute('data-sw_geomodule-version', '2.0');
                    
                    $paths = $dom->getElementsByTagName('path'); //here you have an corresponding object
                        foreach ($paths as $path) {
                            $lvl_1 = $path->getAttribute('data-name');
                            if($lvl_1 && !empty($lvl_1)){
                                $lvl_1 = trim($lvl_1);
                                
                                $data = array(
                                    'parent_id' => $treefield_root_id,
                                    'template' => 'treefield_treefield',
                                    'field_id'=>$field_id,
                                );

                                $data_lang= array();
                                foreach ( $langs_object as $lang_ob) {
                                    $data_lang['value_'.$lang_ob['id']] = $lvl_1;
                                }
                                $treefield_id = $this->treefield_m->save_with_lang($data, $data_lang);
                                $treefield_data_dynamic[] = $treefield_id;
                                
                                $path->setAttribute('data-name-lvl_0', $root_name);
                                $path->setAttribute('data-name-lvl_1', $lvl_1);
                                $path->setAttribute('data-name', $lvl_1.', '.$root_name);
                                
                                $path->setAttribute('data-id-lvl_0', $treefield_root_id);
                                $path->setAttribute('data-id-lvl_1', $treefield_id);
                                $path->setAttribute('data-idtreefield',$treefield_id);
                                $treefield_array[]=$lvl_1;
                            }
                        }     
                    
                    $g = $dom->getElementsByTagName('g'); //here you have an corresponding object
                        foreach ($g as $path) {
                            $lvl_1 = $path->getAttribute('data-name');
                            if($lvl_1 && !empty($lvl_1)){
                                $lvl_1 = trim($lvl_1);
                                
                                $data = array(
                                    'parent_id' => $treefield_root_id,
                                    'template' => 'treefield_treefield',
                                    'field_id'=>$field_id,
                                );
                                $data_lang= array();
                                foreach ( $langs_object as $lang_ob) {
                                    $data_lang['value_'.$lang_ob['id']] = $lvl_1;
                                }
                                $treefield_id = $this->treefield_m->save_with_lang($data, $data_lang);
                                $treefield_data_dynamic[] = $treefield_id;
                                
                                $path->setAttribute('data-name-lvl_0', $root_name);
                                $path->setAttribute('data-name-lvl_1', $lvl_1);
                                $path->setAttribute('data-name', $lvl_1.', '.$root_name);
                                
                                $path->setAttribute('data-id-lvl_0', $treefield_root_id);
                                $path->setAttribute('data-id-lvl_1', $treefield_id);
                                $path->setAttribute('data-idtreefield',$treefield_id);
                                $treefield_array[]=$lvl_1;
                            }
                        }  
                        
                    $svg= $dom->saveXML();
                    }
                    
                    file_put_contents(sw_win_upload_path().'files/current_map.svg', $svg);
                   
                    if($random_locations) {
                        $this->load->model('listing_m');

                        $results_obj_id = $this->listing_m->get();

                        if($results_obj_id and !empty($results_obj_id))
                            foreach ($results_obj_id as $key => $estate_id) {
                                $estate_id = $estate_id->idlisting;
                                $data = array();
                                $random_region = $treefield_data_dynamic[array_rand($treefield_data_dynamic)];

                                $data['location_id'] = $random_region;  

                                $this->listing_m->save($data, $estate_id);
                            }
                    }
                    redirect(admin_url("admin.php?page=$wp_page"));
                } 
            } else {
                $this->data['error']= __('Must accept checkbox. Current map will be replaced with new one', 'sw_win');
            }
        }
      
        
        // Load view
        $this->data['subview'] = 'admin/treefield/addvalue_from_svg';
        $this->load->view('admin/_layout_main', $this->data);
	}

        
    public function remvalue($id = NULL, $redirect='1')
	{   
        // Get parameters
        $id = $this->input->get('id');
        
        if(is_numeric($id))
            $this->treefield_m->delete($id);
            
        wp_redirect(admin_url("admin.php?page=treefield_categories")); exit;
	}
    
}
