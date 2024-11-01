<?php

class Repository_m extends MY_Model {
    
    protected $_table_name = 'sw_repository';
    protected $_order_by = 'idrepository';
    protected $_timestamps = TRUE;
    public $_primary_key = 'idrepository';
    
    
    public function save($data, $id=NULL)
    {
        // Remove never activated repositories
        // created before 24h ago
        $not_activated_repositories = $this->get_by(array('is_activated'=>NULL, 
                                        'date_submit <'=>date('Y-m-d H:i:s', time()-0.5*60*60)));
        foreach($not_activated_repositories as $rep)
        {
            $this->delete($rep->idrepository);
        }
        
        return parent::save($data, $id);
    }
    
    public function is_related($repository_id, $user_id, $method = 'edit')
    {
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
    
	public function delete ($id)
	{
        // Delete all files from filesystem
        $files = $this->file_m->get_by(array(
            'repository_id' => $id
        ));
        
        foreach($files as $file)
        {
            if(file_exists(sw_win_upload_path().'files/'.$file->filename))
                unlink(sw_win_upload_path().'files/'.$file->filename);
            if(file_exists(sw_win_upload_path().'files/thumbnail/'.$file->filename))
                unlink(sw_win_upload_path().'files/thumbnail/'.$file->filename);
        }
       
        // Delete all files from db
        $this->db->where('repository_id', $id);
        $this->db->delete($this->file_m->get_table_name()); 
        
        // Delete repository row
        parent::delete($id);
	}
    
}



