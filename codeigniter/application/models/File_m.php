<?php

class File_m extends MY_Model {
    
    protected $_table_name = 'sw_file';
    protected $_order_by = 'order, idfile';
    public $_primary_key = 'idfile';
    
    public function get_max_order()
    {
        // get max order
        return parent::max_order();
    }
    
    public function get_where_in($where_in)
    {
        $this->db->where_in('repository_id', $where_in);
        return $this->get();
    }
    
    public function get_repository($repository_id)
    {
        $this->db->where('repository_id', $repository_id);
        return $this->get();
    }
    
    public function count_in_repository($repository_id)
    {
        $this->db->where('repository_id', $repository_id);
        $this->db->from($this->_table_name);
        $count = $this->db->count_all_results();;
        
        if(!empty($count))
            return $count;
        
        return 0;
    }
    
    public function delete($id)
    {
        $file = $this->get($id);
        if(!empty($file->filename))
        {
            $this->delete_cache($file->filename);
            parent::delete($id);
        }
    }
    
    public function is_related($filename_or_id, $user_id, $method = 'edit')
    {
        $file = NULL;
        
        if(is_numeric($filename_or_id))
        {
            $file = $this->get_by(array('idfile'=>$filename_or_id), TRUE);
        }
        else
        {
            $file = $this->get_by(array('filename'=>$filename_or_id), TRUE);
        }
        
//        echo $this->db->last_query();
//        var_dump($file);
        
        if(empty($file))
            return false;

        $repository_id = $file->repository_id;
        
        if(empty($repository_id))
            return false;
            
        // Get repository model name and check in model
        
        $repository = $this->repository_m->get($repository_id);
        
        $model_name = $repository->model_name;
        
        $this->load->model($model_name); 
        
        // if repository id is not related to listing, allow
        $related_obj = $this->$model_name->get_by(array('repository_id'=>$repository_id), TRUE);

        if (empty($related_obj))
            return true;
        
        // if repositors is_activated == 0 then allow
        if($repository->is_activated == 0)
            return true;
        
        // if it's related to listing, check if user is related to listing
        
        $related_obj_id = $this->$model_name->get_id($related_obj);
               
        return $this->$model_name->is_related($related_obj_id, get_current_user_id());
    }
    
    public function delete_cache($filename)
    {
        if ($handle = opendir(sw_win_upload_path().'files/strict_cache/')) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    if(strpos($entry, $filename) !== FALSE)
                    {
                        @unlink(sw_win_upload_path().'files/strict_cache/'.$entry);
                    }
                }
            }
            closedir($handle);
        }
    }

}



