<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*


Guides:
We can't use $this->load and similar as usual in codeigniter
For this case, wordpress widgets, use for example: $this->CI->load

*/

class Frontendexport extends MY_Widgetcontroller {

	public function __construct(){
		parent::__construct();
        
        $this->CI->load->model('field_m');
        $this->CI->load->model('repository_m');
        $this->CI->load->model('listing_m');
        
        $this->CI->load->library('pagination');
        
        $this->data['is_export'] = true;
        
	}
    
    
	public function index(&$output=NULL, $atts=array())
	{

	}

	public function pdf(&$output=NULL, $atts=array())
	{
            $get = $_GET;
            if(!isset($get['listing_id']) || empty($get['listing_id'])) {
                echo __('Wrong link', 'sw_win');
                return false;
                exit();
            }
            
            // www.mapquestapi.com api key, to generate map image for PDF export
            $mapquest_api_key='c9MNDPFQVui453XfIl7RBH1FxXkVW9sd';
                
            $listing_id = $get['listing_id'];
            
            $lang_code = sw_current_language();
            $lang_id =  sw_current_language_id();
            if(isset($get['lang_id'])&&!empty($get['lang_id'])){
                $lang_code = sw_get_languages($get['lang_id']);
                /* if lang exists */
                if($lang_code){
                    $lang_id = $get['lang_id'];
                    
                    global $sitepress;
                    if(isset($sitepress))
                        $sitepress->switch_lang($lang_code, true);
                }
            }
            
            
            $type ='';
            
            if(sw_settings('pdf_export_mpdf') && sw_settings('pdf_export_mpdf') =='1') {
                $type="mpdf";
            }
            
            if(!file_exists(SW_WIN_PDF_PLUGIN_PATH.'/mpdf/vendor/mpdf/')) {
                $type='';
            }
            
            if($type=='mpdf') {
                include_once(SW_WIN_PDF_PLUGIN_PATH."/Pdf_m.php");

                $pdf = New Pdf_m();
                $file_pdf =  $pdf->generate_by_listing($listing_id, $lang_code, $mapquest_api_key,$lang_id);
                
            } else {
                include_once(SW_WIN_PDF_PLUGIN_PATH."/Pdf.php");

                $pdf = New Pdf();
                $file_pdf =  $pdf->generate_by_listing($listing_id, $lang_code, $mapquest_api_key,$lang_id);
            }

        
	}
}
