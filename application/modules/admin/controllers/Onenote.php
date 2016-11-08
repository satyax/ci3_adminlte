<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Onenote extends Admin_Controller {
  
	public function __construct()	{
		parent::__construct();
		$this->load->library('form_builder');
    $this->load->library('wawan_lib');
    
    $this->load->model('Trs_notes_model', 'trs_notes');
    $this->load->model('Trs_notes_files_model', 'trs_notes_files');
    $this->load->model('Mst_auth_admin_model', 'mst_auth_admin');
	}
  
	public function index()	{    
    /*if ($id_notes != '') {
      $this->edit($id_notes); 
    } else {*/
      
		  $crud = $this->generate_crud('trs_notes','Notes',array('is_deleted' => '0'));
      //$crud = $this->generate_crud('vw_trs_notes_active','Notes');
      $crud->columns('title', 'content', 'user_created', 'created_at');
      
      $crud->unset_add();
      $crud->unset_edit();
      $crud->unset_read();
      $crud->unset_delete();
      
      $crud->add_action('Edit', '', 'admin/onenote/edit', 'fa fa-edit');
      
      $str = $this->load->view('onenote/top_notes','',true);    
      $this->mViewData['top_html'] = $str;    
      
      $this->mTitle = 'One Notes';
      $this->render_crud();
//    }
	}
  
  public function get_sub_categories() {
    $varGet = $this->input->get();
    if ($varGet['id_categories'] !='') {
      $this->load->model('Mst_sub_categories_model', 'mst_sub_categories');
      $result = $this->mst_sub_categories->get_custom(['id_categories'=>$varGet['id_categories']],null,null,null);
      echo json_encode($result);
    }
  }
  
  public function add() {
    $this->add_script('assets/dist/oshop/one_notes.js',true,'head');
    
    $this->mViewData['view_history'] = true;
    $this->load->model('Mst_categories_model', 'mst_categories');
    $this->mViewData['categories'] = $this->mst_categories->get_all();
    $this->render('onenote/entry_form');
  }
  
  public function view_history($id_notes) {
    $this->edit($id_notes,false);
  }
  
  public function edit($id_notes, $view_history = true) {    
    if ($id_notes != '') {
      $this->add_script('assets/dist/oshop/one_notes.js',true,'head');
      
      $this->load->model('Mst_categories_model', 'mst_categories');
      $this->load->model('Mst_sub_categories_model', 'mst_sub_categories');
      
      $notes = $this->trs_notes->get_by('id_notes',$id_notes);
      $notes_files = $this->trs_notes_files->get_custom(array('id_notes' => $id_notes));
      
      $allow_permanent_delete = $this->mst_auth_admin->get_custom(array('id_admin_user' => $this->mUser->id,'id_auth'=> '1'),null,null,null);;
      if (sizeOf($allow_permanent_delete) > 0) { $allow_permanent_delete = true; } else { $allow_permanent_delete = false; }
      $this->mViewData['allow_permanent_delete'] = $allow_permanent_delete;
      
      $this->mViewData['categories'] = $this->mst_categories->get_all();
      $this->mViewData['sub_categories'] = $this->mst_sub_categories->get_custom(['id_categories'=>$notes->id_categories],null,null,null);
      
      $this->mViewData['id_notes'] = $id_notes;
      $this->mViewData['notes'] = $notes;
      $this->mViewData['notes_files'] = $notes_files;
      $this->mViewData['file_upload_folder'] = $this->mSiteConfig['file_upload_folder'];
      $this->mViewData['view_history'] = $view_history;
      
      /*if (!$view_only) {
        $crud = $this->generate_crud('trs_notes','Notes',array('is_deleted' => '1'));
        $crud->columns('title', 'content', 'user_edited', 'date_edited', 'last_name', 'active');
        $crud->unset_add();
        $crud->unset_edit();
        $crud->unset_delete();
        $crud->add_action('View', '', 'admin/onenote/view_only', 'fa fa-edit');
        
        $str = $this->load->view('onenote/top_notes','',true);    
        $this->mViewData['top_html'] = $str;    
        
        $this->mTitle = 'One Notes Version History';
        $this->mViewData['versionHistory'] = $this->render_crud_grid_only(true);
      }*/
      
      if ($view_history) {
        $this->db->where('is_deleted',1);
        $this->db->where('unique_keys',$notes->unique_keys);
        $data['notes_version'] = $this->db->get('trs_notes')->result();
        $data['image_folder'] = $this->mSiteConfig['image_folder'];
        $data['wawan_lib'] = $this->wawan_lib;
        if ($data) $this->mViewData['versionHistoryTable'] = $this->load->view('onenote/version_history_table',$data,true);
      }
      
      $this->render('onenote/entry_form');
    }
  }
  
  public function del_notes() {
    $varGet = $this->input->post();
    $id_notes = $varGet['key'];
    //$notes = $this->trs_notes->get_by('id_notes',$id_notes);
    //$this->db->flush_cache();
    //$this->db->where('id_notes',$id_notes);
    $notes = $this->db->query("select * from trs_notes where id_notes = $id_notes")->result()[0];
    
    if ($notes) {
      
      $this->db->trans_start();
      
      $unique_keys = $notes->unique_keys;
      
      //delete2in file nya dari komputer
      $sql = "select * from trs_notes_files where id_notes in (select a.id_notes from trs_notes a where a.unique_keys = '$unique_keys')";
      $results = $this->db->query($sql)->result();
      if ($results) {
        foreach($results as $row) {
          if (file_exists($row->physical_file_location)) {
            if (!unlink($row->physical_file_location)) {
              file_put_contents($unique_keys.'--faildelete.txt',$row['physical_file_location'],FILE_APPEND);
            }
          }
        }
      }
      
      //delete data dari tabel2 terkait
      $sql = "delete from trs_notes_files where id_notes in (select a.id_notes from trs_notes a where a.unique_keys = '$unique_keys')";
      $this->db->query($sql);
      $sql = "delete from trs_notes where unique_keys = '$unique_keys'";
      $this->db->query($sql);
      
      $this->db->trans_complete();
      header('Location: '.base_url().'admin/onenote');
    }
  }
  
  public function del_file() {    
    $varGet = $this->input->post();
    
    if ($varGet['key'] != '') {
      
      $id_notes_files = $varGet['key'];
      $notes_old = $this->trs_notes_files->get_by('id_notes_files',$id_notes_files);
      if ($notes_old) {
        $this->db->trans_start();
        
        //update dulu notes lama bahwa filenya ada yang dihapus        
        $data = ['is_deleted' => 1];
        $this->db->where('id_notes_files', $id_notes_files);
        $this->db->update('trs_notes_files', $data);
        
        $id_notes_old = $notes_old->id_notes;
        $id_notes_new = $this->createNewVersion($id_notes_old);
        
        $this->db->trans_complete();
        header('Location: '.base_url().'admin/onenote/edit/'.$id_notes_new);
      }
      
    }
  }
  
  public function createNewVersion($id_notes_old) {
    if (!(isset($id_notes_old) AND !empty($id_notes_old))) { return; }
    
    $notes_old = $this->db->where('id_notes', $id_notes_old)->get('trs_notes');
    if(!$notes_old->num_rows()) { return; } else {
      
      //copy trs_notes nya
      $data = $notes_old->result_array()[0];
      unset($data['id_notes']);
      $this->db->insert('trs_notes', $data);
      $id_notes_new = $this->db->insert_id();
      $data = array(
        'user_created' => $this->mUser->username,
        'user_edited' => $this->mUser->username,
        'id_notes_parent' => $id_notes_old,
        'is_deleted' => 0,
      );
      $this->db->set('created_at', 'NOW()', FALSE);
      $this->db->set('updated_at', 'NOW()', FALSE);
      
      $this->db->where('id_notes', $id_notes_new);
      $this->db->update('trs_notes', $data);
      
      //update trs_notes lama dengan is_deleted
      $data = [
        'is_deleted' => 1,
        'user_edited' => $this->mUser->username,
      ];
      $this->db->set('updated_at', 'NOW()', FALSE);
      $this->db->where('id_notes', $id_notes_old);
      $this->db->update('trs_notes', $data);
      
      //sekarang versioning juga file nya...
      $unique_id = $this->wawan_lib->gen_uuid();
      $this->db->where('id_notes', $id_notes_old);
      $this->db->where('is_deleted', 0);
      $notes_files_old = $this->db->get('trs_notes_files');
      if ($notes_files_old->num_rows() > 0) {
        foreach ($notes_files_old->result() as $files) {
          $new_file_name = $this->mSiteConfig['file_upload_folder'].$unique_id.'--'.$files->file_name_original;
          $physical_location = $this->mSiteConfig['file_upload_real_folder'].$unique_id.'--'.$files->file_name_original;
          if (dirname($physical_location) == dirname($files->physical_file_location)) {
            copy($files->physical_file_location, $physical_location) or die("Failed to move file");  //this will only copy, keeping originak file intact
          } else {
            rename($files->physical_file_location, $physical_location) or die("Failed to move file"); //this will remove file in source directory
          }
          
          //copy juga row notes files nya juga ke database
          $data = array(
            'id_notes' => $id_notes_new,            
            'unique_name' => $unique_id,
            'file_name_original' => $files->file_name_original,
            'file_name_versioning' => $unique_id.'--'.$files->file_name_original,
            'url' => $new_file_name,
            'physical_file_location' => $physical_location,
            'is_deleted' => 0,
          );
          $this->db->insert('trs_notes_files',$data);
          
        }
      }
    }
    
    return $id_notes_new;
  }
  
  private function newNoteDifferentFromOldNote($notes_old,$varGet,$files) {
    if ($notes_old->title != $varGet['title']) return true;
    if ($notes_old->content != $varGet['content']) return true;
    if ($notes_old->id_categories != $varGet['id_categories']) return true;
    if ($notes_old->id_sub_categories != $varGet['id_sub_categories']) return true;
    if ($files['file_name_input']['name'][0]!='') return true;
  }
  
  private function sendEmailNotification($id_notes) {
    if ($id_notes=='') return;
    
    $this->load->library('email');
    $this->load->model('Trs_notes_email_to_model', 'trs_notes_email_to');
    $this->load->model('Trs_notes_email_log_model', 'trs_notes_email_log');    
    $emails = $this->trs_notes_email_to->get_custom(['active' => 1],null,null,null);
    $notes = $this->trs_notes->get_custom(['id_notes' => $id_notes],null,null,null)[0];   
    
    foreach ($emails as $email) {
      $this->email->clear();
      $this->email->from('no_reply@oshop.co.id', 'One Notes');
      $this->email->to($email->email);
      //$this->email->cc('test2@gmail.com');
      $this->email->subject('[One Notes] '.$notes->title);
      $this->email->message('
        Hai! Ada perubahan di notes OShop, silahkan cek di: '.base_url().'admin/onenote/edit/'.$id_notes.'<br>
        Dan dibawah isi notesnya: <br>'.$notes->content.'
      ');
      //$this->email->attach('/path/to/file1.png'); // attach file
      //$this->email->attach('/path/to/file2.pdf');
      ini_set('max_execution_time', 500);
      $success = $this->email->send();
      if ($success) 
        $success = '1';
      else 
        $success = '0';
      $this->trs_notes_email_log->insert(['email_to' => $email->email,'subject' => '[One Notes] '.$notes->title, 'content' => $notes->content, 'success' => $success]);
    }

  }
  
  public function save() {
    
    $varGet = $this->input->post();
    
    $this->db->trans_start();
    
    $data = $varGet;
    unset($data['file_name_input']);
    
    //simpan notes
    if ($varGet['id_notes'] == '') {
      
      $unique_id = $this->wawan_lib->gen_uuid();
      $data['id_notes_parent'] = null;
      $data['unique_keys'] = $unique_id;
      $data['user_created'] = $this->mUser->username;
      $this->db->set('created_at', 'NOW()', FALSE);
      $data['user_edited'] = $this->mUser->username;
      $this->db->set('updated_at', 'NOW()', FALSE);
      
      $this->db->insert('trs_notes',$data);
      $id_notes = $this->db->insert_id();
      
      //insert file baru
      $files = $_FILES['file_name_input'];
      if ($files["name"][0] != '') {
        for ($i = 0; $i < count($files["name"]); $i++) {
          $new_file_name = $this->mSiteConfig['file_upload_folder'].$unique_id.'--'.$files["name"][$i];
          $physical_location = $this->mSiteConfig['file_upload_real_folder'].$unique_id.'--'.$files["name"][$i];
          rename($files['tmp_name'][$i], $physical_location) or die("Failed to move file");
          
          
          
          /*if ( ! $this->upload->do_upload('userfile')) {
            die($this->upload->display_errors());
          }*/
          
          
          
          $data = array(
            'id_notes' => $id_notes,            
            'unique_name' => $unique_id,
            'file_name_original' => $files["name"][$i],
            'file_name_versioning' => $unique_id.'--'.$files["name"][$i],
            'url' => $new_file_name,
            'physical_file_location' => $physical_location,
          );
          $this->db->insert('trs_notes_files',$data);
        }
      }
      
    } else {
      
      $id_notes = $varGet['id_notes'];
      
      //cek notes lama dengan yang disubmit ini beda nggak? kalo beda maka create new versioning
      $notes_old = $this->trs_notes->get_by('id_notes',$id_notes);
      if ($notes_old) {
        
        if ($this->newNoteDifferentFromOldNote($notes_old,$varGet,$_FILES)) {
          $result = $this->createNewVersion($id_notes);  //this is where the create versioning happens!
          if ($result) {
            $id_notes_new = $result;
            $data = [
              'title' => $varGet['title'],
              'content' => $varGet['content'],
              'id_categories' => $varGet['id_categories'],
              'id_sub_categories' => $varGet['id_sub_categories'],
              'is_deleted' => 0,
            ];
            $this->db->where('id_notes', $id_notes_new);
            $this->db->update('trs_notes', $data);
            $id_notes = $id_notes_new;
            
            //insert file baru
            $unique_id = $this->wawan_lib->gen_uuid();
            $files = $_FILES['file_name_input'];
            if ($files["name"][0] != '') {
              for ($i = 0; $i < count($files["name"]); $i++) {
                $new_file_name = $this->mSiteConfig['file_upload_folder'].$unique_id.'--'.$files["name"][$i];
                $physical_location = $this->mSiteConfig['file_upload_real_folder'].$unique_id.'--'.$files["name"][$i];
                rename($files['tmp_name'][$i], $physical_location) or die("Failed to move file");
                
                
                /*if ( ! $this->upload->do_upload('userfile')) {
                  die($this->upload->display_errors());
                }*/
                
                
                $data = array(
                  'id_notes' => $id_notes,            
                  'unique_name' => $unique_id,
                  'file_name_original' => $files["name"][$i],
                  'file_name_versioning' => $unique_id.'--'.$files["name"][$i],
                  'url' => $new_file_name,
                  'physical_file_location' => $physical_location,
                );
                $this->db->insert('trs_notes_files',$data);
              }
            }
            
            
          }
        }
      }
    }
    
    //update file update-an
    
    $this->db->trans_complete();
    
    $this->sendEmailNotification($id_notes);
    
    header('Location: '.base_url().'admin/onenote/edit/'.$id_notes);
    
  }

}
