<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*


Guides:
We can't use $this->load and similar as usual in codeigniter
For this case, wordpress widgets, use for example: $this->CI->load

*/

class Dashwidgets extends MY_Widgetcontroller {

	public function __construct(){
		parent::__construct();
        
        $this->CI->load->model('field_m');
        $this->CI->load->model('repository_m');
        $this->CI->load->model('listing_m');
	}
    
    
	public function index(&$output=NULL, $atts=array())
	{
        $this->data['subview'] = 'widgets/index';
        $this->print_template($output);
	}
    
	public function listings(&$output=NULL, $atts=array(), $instance=NULL)
	{
        // dump($atts);

        $columns = array('idlisting', 'address', 'field_10', 'field_4');
        
        $conditions = array();
        
        $atts['num_listings'] = 10;
        
        $conditions['search_order'] = 'idlisting DESC';
        
        // dump($conditions);

        prepare_search_query_GET($columns, 'listing_m', $conditions);
        $this->data['listings'] = $this->CI->listing_m->get_pagination_lang($atts['num_listings'], 0, sw_current_language_id(), TRUE);

        // echo $this->CI->db->last_query();

        $this->data['subview'] = 'dashwidgets/listings';
        $this->print_template($output);
	}
    
	public function news(&$output=NULL, $atts=array(), $instance=NULL)
	{
        // dump($atts);
        
        $conditions = array();
        
        $atts['num_listings'] = 10;
        
        $conditions['search_order'] = 'idlisting DESC';
        
        // dump($conditions);

        prepare_search_query_GET($columns, 'listing_m', $conditions);
        $this->data['listings'] = $this->CI->listing_m->get_pagination_lang($atts['num_listings'], 0, sw_current_language_id(), TRUE);

        // echo $this->CI->db->last_query();

        $this->data['subview'] = 'dashwidgets/news';
        $this->print_template($output);
	}
    
}
