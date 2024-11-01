<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*


Guides:
We can't use $this->load and similar as usual in codeigniter
For this case, wordpress widgets, use for example: $this->CI->load

*/

class Files extends MY_Controller {

	public function __construct(){
		parent::__construct();
        
        $this->load->model('file_m');
        $this->load->model('repository_m');
        $this->load->model('listing_m');
                
        $this->data['is_ajax'] = true;

//        current_user_can('administrator')    
//        $user = wp_get_current_user();
//        if ( in_array( 'author', (array) $user->roles ) ) {
//        }
        
	}
    
    
	public function index(&$output=NULL, $atts=array())
	{

	}
    
    
    // Upload file to listing
	public function listing(&$output=NULL, $atts=array())
	{
	   $repository_id = $this->input->post_get('repository_id', TRUE);
       $file = $this->input->post_get('file', TRUE);
       $files = $this->input->post_get('files', TRUE);

       if(empty($repository_id))
            exit('Repository not defined');
       
       // [Security check]
       
       if(!empty($file))
       {
            check_access('file_m', $file, 'edit');
       }
       
       check_access('repository_m', $repository_id, 'edit');
       
       // [/Security check]
    
       $rep_images_num = $this->file_m->count_in_repository($repository_id);
       
       $upload_options = array('rep_images_num' => $rep_images_num);
        
       $this->load->library('UploadHandler', array( 'options'=>$upload_options,
                                                    'initialize'=>false
                                                     ));
       
//        $_GET = array_merge($_POST, $_GET);
//        var_dump($_GET);
//        array(2) {
//          ["action"]=>
//          string(9) "ci_action"
//          ["page"]=>
//          string(13) "files_listing"
//        }
        
        if($_SERVER['REQUEST_METHOD'] == 'DELETE' || (isset($_GET['_method']) && $_GET['_method']== 'DELETE') )
        {
            $response = $this->uploadhandler->initialize(true);          
            //var_dump($response);

            if($response['success'] == 'true')
            {
                
                $file_remove = array(
                    'filename' => $this->uploadhandler->get_file_name_param(),
                    'repository_id' => $repository_id
                );
                
                $file = $this->file_m->get_by($file_remove, TRUE);
                
                if(is_object($file))
                {
                     $this->file_m->delete($file->idfile);
                }
            }
        }
        else if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $response = $this->uploadhandler->initialize(false);
            //var_dump($response);

            if(isset($response['files']))
            {
                foreach($response['files'] as $file)
                {                    
                    $file->thumbnail_url = plugins_url(SW_WIN_SLUG.'').'/assets/img/icons/filetype/_blank.png';
                    $file->zoom_enabled = false;
                    $file->delete_url = admin_url('admin-ajax.php')."?action=ci_action&page=files_listing&repository_id=$repository_id&_method=DELETE&file=".rawurlencode($file->name);;
                    
                    if(file_exists(sw_win_upload_path().'files/thumbnail/'.$file->name))
                    {
                        $file->thumbnail_url = sw_win_upload_dir().'/files/thumbnail/'.$file->name;
                        $file->zoom_enabled = true;
                    }
                    else if(file_exists(SW_WIN_PLUGIN_PATH.'/assets/img/icons/filetype/'.get_file_extension($file->name).'.png'))
                    {
                        $file->thumbnail_url = plugins_url(SW_WIN_SLUG.'').'/assets/img/icons/filetype/'.get_file_extension($file->name).'.png';
                    }
                    
                    $file->short_name = character_hard_limiter($file->name, 20);
                    
                    $this->db->reconnect(); // MySQL timeout possible
                    
                    $next_order = $this->file_m->get_max_order()+1;
                    
                    $response['orders'][$file->name] = $next_order;
                    $response['repository_id'] = $repository_id;
                    
                    if(empty($file->error))
                    {
                        // Add file to repository
                        $file_id = $this->file_m->save(array(
                            'repository_id' => $repository_id,
                            'order' => $next_order,
                            'filename' => $file->name,
                            'filetype' => $file->type
                        ));
                    }
                }
            }
            
            $this->uploadhandler->generate_response($response);
            
        }
        else
        {
            exit(__('Wrong method', 'sw_win'));
        }
	}
        
        // Upload file to repository
	public function repository(&$output=NULL, $atts=array())
	{
            $repository_id = $this->input->post_get('repository_id', TRUE);
            $file = $this->input->post_get('file', TRUE);
            $files = $this->input->post_get('files', TRUE);

            if(empty($repository_id))
                 exit('Repository not defined');

            // [Security check]

            if(!empty($file))
            {
                 check_access('file_m', $file, 'edit');
            }

            check_access('repository_m', $repository_id, 'edit');

            // [/Security check]

            $rep_images_num = $this->file_m->count_in_repository($repository_id);

            $upload_options = array('rep_images_num' => $rep_images_num);

            $this->load->library('UploadHandler', array( 'options'=>$upload_options,
                                                         'initialize'=>false
                                                          ));

             if($_SERVER['REQUEST_METHOD'] == 'DELETE' || (isset($_GET['_method']) && $_GET['_method']== 'DELETE') )
             {
                 $response = $this->uploadhandler->initialize(true);          
                 //var_dump($response);

                 if($response['success'] == 'true')
                 {

                     $file_remove = array(
                         'filename' => $this->uploadhandler->get_file_name_param(),
                         'repository_id' => $repository_id
                     );

                     $file = $this->file_m->get_by($file_remove, TRUE);

                     if(is_object($file))
                     {
                          $this->file_m->delete($file->idfile);
                     }
                 }
             }
             else if($_SERVER['REQUEST_METHOD'] == 'POST')
             {
                 $response = $this->uploadhandler->initialize(false);

                 if(isset($response['files']))
                 {
                     foreach($response['files'] as $file)
                     {                    
                         $file->thumbnail_url = plugins_url(SW_WIN_SLUG.'').'/assets/img/icons/filetype/_blank.png';
                         $file->zoom_enabled = false;
                         $file->delete_url = admin_url('admin-ajax.php')."?action=ci_action&page=files_repository&repository_id=$repository_id&_method=DELETE&file=".rawurlencode($file->name);;

                         if(file_exists(sw_win_upload_path().'files/thumbnail/'.$file->name))
                         {
                             $file->thumbnail_url = sw_win_upload_dir().'/files/thumbnail/'.$file->name;
                             $file->zoom_enabled = true;
                         }
                         else if(file_exists(SW_WIN_PLUGIN_PATH.'/assets/img/icons/filetype/'.get_file_extension($file->name).'.png'))
                         {
                             $file->thumbnail_url = plugins_url(SW_WIN_SLUG.'').'/assets/img/icons/filetype/'.get_file_extension($file->name).'.png';
                         }

                         $file->short_name = character_hard_limiter($file->name, 20);

                         $this->db->reconnect(); // MySQL timeout possible

                         $next_order = $this->file_m->get_max_order()+1;

                         $response['orders'][$file->name] = $next_order;
                         $response['repository_id'] = $repository_id;

                         if(empty($file->error))
                         {
                             // Add file to repository
                             $file_id = $this->file_m->save(array(
                                 'repository_id' => $repository_id,
                                 'order' => $next_order,
                                 'filename' => $file->name,
                                 'filetype' => $file->type
                             ));
                         }
                     }
                 }

                 $this->uploadhandler->generate_response($response);

             }
             else
             {
                 exit(__('Wrong method', 'sw_win'));
             }
	}
    
	public function order(&$output=NULL, $atts=array())
	{
	   $repository_id = $this->input->post('repository_id', TRUE);
       $order = $this->input->post('order', TRUE);
       
       check_access('repository_m', $repository_id, 'edit');
       
        // Fetch all files by repository_id
        $files = $this->file_m->get_by(array(
            'repository_id' => $repository_id
        ));
       
        // Update all files with order value
        if(isset($order))
        foreach($order as $order=>$filename)
        {
            foreach($files as $file)
            {
                if($filename == $file->filename)
                {
                    $this->file_m->save(array(
                        'order' => $order,
                    ), $file->idfile);
                    break;
                }
            }
        }

        $data['success'] = true;
        $length = strlen(json_encode($data));
        header('Content-Type: application/json; charset=utf8');
        header('Content-Length: '.$length);
        echo json_encode($data);
        exit();
    }
    
	public function edit(&$output=NULL, $atts=array())
	{
        $rel = $this->input->get('rel', TRUE);
        
        $dim = '800x600';
        $filename = $rel;

        check_access('file_m', $filename, 'edit');
       
        $input_data = $_POST;
        $this->data['resize'] = $dim;
        
        $filename_db = urldecode($filename);
        $filename_db = str_replace("&#40;","(",$filename_db);
        $filename_db = str_replace("&#41;",")",$filename_db);
        
        $filename_url = $filename;
        $filename_url = str_replace("&#40;","(",$filename_url);
        $filename_url = str_replace("&#41;",")",$filename_url);
        
        $this->load->model('file_m');
        $this->data['form'] = $this->file_m->get_by(array('filename'=>$filename_db), TRUE);
        
        if(empty($this->data['form']->idfile))
            exit('Not exists: '.$filename_db);
        
        $this->data['model']= '';
        $this->load->model('repository_m');
        $repository_m= $this->repository_m->get_by(array('idrepository'=>$this->data['form']->repository_id), TRUE);
        $this->data['model']= $repository_m->model_name;
        
//        $this->load->model('estate_m');
//        $this->data['properties'] = $this->estate_m->get_form_dropdown('address', FALSE, TRUE, TRUE);
//        
        // Check if user have permission on this file
//        if($this->session->userdata('type') == 'USER')
//        if(!$this->user_m->is_related_repository($this->data['user_id'], $this->data['form']->repository_id))
//        {
//            exit('No permissions');
//        }

        if(sw_count($input_data) > 0)
        {
            if(isset($input_data['image-data']))
            {
                $data_im = explode(',', $input_data['image-data']);
                $data_im = base64_decode($data_im[1]);
                $fpath = sw_win_upload_path().'files/'.$filename_db;
                
                $im = imagecreatefromstring($data_im);
                switch (strtolower(substr(strrchr($filename_db, '.'), 1))) {
                    case 'jpg':
                    case 'jpeg':
                        imagejpeg($im, $fpath);
                        break;
                    case 'gif':
                        imagegif($im, $fpath);
                        break;
                    case 'png':
                        imagepng($im, $fpath);
                        break;
                    default:
                }
                
                // generate image versions
                
                $this->load->library('UploadHandler', array('initialize'=>FALSE));
                $this->uploadhandler->regenerate_versions($filename_db, '');
                
                $this->file_m->delete_cache($filename_db);
            }
            
            if(isset($input_data['alt']))
            {
                $data = $this->file_m->array_from_post(array('alt', 'description', 'title', 'link', 'listing_id'));
                
                $this->file_m->save($data, $this->data['form']->idfile);

                wp_redirect(admin_url( 'admin-ajax.php' ).'?action=ci_action&page=files_edit&rel='.urlencode($rel)); exit;
            }
        }
        
        if($dim!=='false'):
            $dim_exp = explode('x', $dim);
            $this->data['width'] = $dim_exp[0];
            $this->data['height'] = $dim_exp[1];
            $wanted_ratio = $this->data['width'] / $this->data['height'];
        
            $this->data['filepath'] = sw_win_upload_dir().'/files/'.$filename_url;
        
            $dim_real = getimagesize(sw_win_upload_path().'files/'.$filename_db);
            $this->data['width_r'] = $dim_real[0];
            $this->data['height_r'] = $dim_real[1];
            $real_ratio = $this->data['width_r'] / $this->data['height_r'];
        
            //     800x600       700x600
            if($wanted_ratio > $real_ratio)
            {
                $this->data['width'] = $this->data['width_r'];
                $this->data['height'] = $this->data['width_r'] * 1/$wanted_ratio;
            }
            //          800x600       900x600
            else if($wanted_ratio <= $real_ratio)
            {
                $this->data['width'] = $this->data['height_r'] * $wanted_ratio;
                $this->data['height'] = $this->data['height_r'];
            }
        
            // for larger images
            if($this->data['width'] > $dim_exp[0])
            {
                $this->data['width'] = $dim_exp[0];
                $this->data['height'] = $dim_exp[1];
            }
        endif;
        
        
        $this->load->view('admin/files/edit', $this->data);
       
    }
    
}
