<?php
require 'dbClass.php';
require 'validateClass.php';


class Blog{
  private $title;
  private $content;
  private $image;

  public  function __construct($title = '',$content = '',$image = ''){
    $this->title     = $title;
    $this->content    = $content;
    $this->image = $image;
  }

  // create blog
  public function create(){
          $validate = new Validator();
          $messages     = [];
          $title        = $validate->clean($this->title,'string'); 
          $description  = $validate->clean($this->content,'string'); 

          //title
          if(!$validate->validate($title,'empty')){
            $messages[] = 'Title cannot be empty!';  
          }
          //description 
          if(!$validate->validate($description,'empty')){
            $messages[] = 'Description cannot be empty!';  
          }
          elseif(!$validate->validate($description,'min',50)){
            $messages[] = 'Description Min char is 50!';  
          }

              
          // image Progress
          $img_name = $this->image['name'];
          $img_tmp  = $this->image['tmp_name'];
          $img_err  = $this->image['error'];
          $img_size = $this->image['size'];

        
          if(!$validate->validate($img_name,'empty')){
            $messages[] = "Image cannot be empty!"; 
          }
          else{
            $extension = explode('.',$img_name);
            $extension = strtolower(end($extension));
            $allowed   = ['png','jpg','jpeg'];
              // size
            if($img_size > 5000000) {
              $messages[] = "Image cannot be more than 5mb!"; 
            //error
            }elseif($img_err !== 0){
              $messages[] = "Oops, error accured in image, please try again!"; 
            }else{

                if(!in_array($extension,$allowed)){
                    $messages[] = "Accepted Extensions png / jpg / jpeg!"; 
                }
            }
          }

          if(count($messages) > 0){
            foreach($messages as $msg){
              $notifications[] = "<div class='alert alert-danger' role='alert'>$msg</div>";
            }
            return $notifications;
          }else{
          
            $newName_db = md5(uniqid().'_'.time()).'.'.$extension;
            $newName    = './upload/'.$newName_db;
            // upload img
            if(!move_uploaded_file($img_tmp,$newName)){
              $notifications[] = "<div class='alert alert-danger' role='alert'>Oops, image didnt upload properly, please try again!</div>"; 
            }else{

                // db INSERT
                $sql   = "INSERT INTO `blog` 
                                            (`title`,
                                             `content`,
                                             `image`) 
                                      VALUES (
                                            '{$title}',
                                            '{$description}',
                                            '{$newName_db}'
                                            )";
                $result = new Database();
                $result->doQuery($sql);
                if($result){
      
                  $validate->setMessage("Content Added Successfully!",'success');
                  $validate->redirectHeader('index.php');
                }else{
                  $notifications[] = "<div class='alert alert-danger' role='alert'>Error try again!</div>";
                }              
            }
          }
  }

  //delete blog
  public function delete(){
      $validate = new Validator();
      $db       = new Database();
          // redirect if didnt come from click
      if(!isset($_SERVER['HTTP_REFERER'])){
        $validate->setMessage('Access Denied!', 'danger');
        $validate->redirectHeader('index.php');
      }

      if(isset($_GET['id']) && $_GET['id'] !== ''){
    
        $id    = intval($_GET['id']);
        $sql   = "SELECT `id`,`image` FROM `blog` WHERE `id` = {$id} LIMIT 1";
        $query = $db->doQuery($sql);

        if($query){
          $count = mysqli_num_rows($query);
            if($count !== 1){
              $validate->setMessage('Something went wrong, please try again', 'danger');
              $validate->redirectHeader('index.php');
            }else{
              $image = mysqli_fetch_assoc($query);
              // DELETE
              $path  = "./upload/".$image['image']; 
              unlink($path);
              $sql   = "DELETE FROM `blog` WHERE `id` = {$id} LIMIT 1";
              $query = $db->doQuery($sql);
              if($query){
                $validate->setMessage('Row Deleted Successfully!','success');
                $validate->redirectHeader('index.php');
              }else{
                $validate->setMessage('Something went wrong, please try again', 'danger');
                $validate->redirectHeader('index.php');
              }
            }    
    
        }else{
          $validate->setMessage('Something went wrong, please try again', 'danger');
          $validate->redirectHeader('index.php');
        }
      }else{
        $validate->setMessage('Something went wrong, please try again', 'danger');
        $validate->redirectHeader('index.php');
      }
  }

  // Update Blog
  public function update(){
    $validate = new Validator();
    $db       = new Database();

    if(isset($_GET['id']) && $_GET['id'] !== ''){
  
      $id    = intval($_GET['id']);
      $sql   = "SELECT * FROM `blog` WHERE `id` = {$id}";
      $query = $db->doQuery($sql);
      if($query){
        $count = mysqli_num_rows($query);
          if($count !== 1){
            $validate->setMessage('Something went wrong, please try again', 'danger');
            $validate->redirectHeader('index.php');
          }else{
           return mysqli_fetch_assoc($query);
          }
      }else{
        $validate->setMessage('Something went wrong, please try again', 'danger');
        $validate->redirectHeader('index.php');
      }
    }else{
      $validate->setMessage('Something went wrong, please try again', 'danger');
      $validate->redirectHeader('index.php');
    }
  }
  // update process
  public function updateProcess($id){
      $validate     = new Validator();
      $db           = new Database();
      
      $messages     = [];
      $title        = $validate->clean($this->title,'string'); 
      $description  = $validate->clean($this->content,'string'); 
      //title
      if(!$validate->validate($title,'empty')){
        $messages[] = 'Title cannot be empty!';  
      }
      //description 
      if(!$validate->validate($description,'empty')){
        $messages[] = 'Description cannot be empty!';  
      }
      elseif(!$validate->validate($description,'min',50)){
        $messages[] = 'Description Min char is 50!';  
      }
    
      // image Progress
      $img_name = $this->image['name'];
      $img_tmp  = $this->image['tmp_name'];
      $img_err  = $this->image['error'];
      $img_size = $this->image['size'];

      
      if(!$validate->validate($img_name,'empty')){
        $messages[] = "Image cannot be empty!"; 
      }
      else{
        $extension = explode('.',$img_name);
        $extension = strtolower(end($extension));
        $allowed   = ['png','jpg','jpeg'];
          // size
        if($img_size > 5000000) {
          $messages[] = "Image cannot be more than 5mb!"; 
        //error
        }elseif($img_err !== 0){
          $messages[] = "Oops, error accured in image, please try again!"; 
        }else{
            if(!in_array($extension,$allowed)){
                $messages[] = "Accepted Extensions png / jpg / jpeg!"; 
            }
        }
      }
      

      
        if(count($messages) > 0){
          foreach($messages as $msg){
            $notifications[] = "<div class='alert alert-danger' role='alert'>$msg</div>";
          }
          return $notifications;
        }else{

          $newName_db = md5(uniqid().'_'.time()).'.'.$extension;
          $newName    = './upload/'.$newName_db;
          // upload img
          if(!move_uploaded_file($img_tmp,$newName)){
            $notifications[] = "<div class='alert alert-danger' role='alert'>Oops, image didnt upload properly, please try again!</div>"; 
          }else{
            // unlink image
              $sql   = "SELECT `image` FROM `blog` WHERE `id` = {$id} LIMIT 1";
              $query = $db->doQuery($sql);
              $image = mysqli_fetch_assoc($query);
              $path  = "./upload/".$image['image']; 
              unlink($path);
              // db update
              $sql   = "UPDATE `blog` SET 
                                          `title`= '{$title}',
                                          `content`= '{$description}',
                                          `image` = '{$newName_db}'
                                      WHERE
                                          `id` = {$id}      
                                            ";
              $query = $db->doQuery($sql);
              if($query){

                $validate->setMessage("Content Updated Successfully!",'success');
                $validate->redirectHeader('index.php');
              }else{
                $notifications[] = "<div class='alert alert-danger' role='alert'>Error try again!</div>";
              }              
            }
        }
  }

}