<?php
  include "header.php";

  if(!isset($_SESSION['patient_id'])){
    die(print_r("<hr><br>The page " . $_SERVER['REQUEST_URI'] . " you are trying to reach cannot be reached.")
  );

  }
  else {





    $patient_id = $_SESSION['patient_id'];
    //var_dump($_SESSION);
    include('dbConnect.php');


    if(isset($_REQUEST['submit']))
    {
    try
    {
      $name = $_REQUEST['submit']; //textbox name "txt_name"

      $image_file = "patient_id_".$patient_id."-".$_FILES["fileToUpload"]["name"];
      $type  = $_FILES["fileToUpload"]["type"]; //file name "txt_file"
      $size  = $_FILES["fileToUpload"]["size"];
      $temp  = $_FILES["fileToUpload"]["tmp_name"];
      $file_error = $_FILES["fileToUpload"]["error"];


      $finfo = finfo_open(FILEINFO_MIME_TYPE);
      $mime = finfo_file($finfo, $_FILES['fileToUpload']['tmp_name']);


      $path="upload/".$image_file; //set upload folder path

      //echo "name: $name,image_file: $image_file, type: $type, size: $size,temp:  $temp, path: $path, file error: $file_error\n";

      if(empty($name)){
      $errorMsg="Please Enter Name";
      }
      else if(empty($image_file)){
      $errorMsg="Please Select Image";
      }
      else if($type=="image/jpg" || $type=='image/jpeg' || $type=='image/png' || $type=='image/gif'|| $mime=='application/pdf') //check file extension
      {
      if(!file_exists($path)) //check file not exist in your upload folder path
      {
        if($size < 5000000) //check file size 5MB
        {
        move_uploaded_file($temp, "upload/" .$image_file); //move upload file temperory directory to your upload folder
        }
        else
        {
        $errorMsg="Your File To large Please Upload 5MB Size"; //error message file size not large than 5MB
        }
      }
      else
      {
        $errorMsg="File Already Exists...Check Upload Folder"; //error message file not exists your upload folder path
      }
      }
      else
      {
      $errorMsg="Upload JPG , JPEG , PNG & GIF File Formate.....CHECK FILE EXTENSION"; //error message file extension
      }

      if(!isset($errorMsg))
      {
      $insert_stmt=$conn->prepare('INSERT INTO patient_documents(patient_id, uploadDateTime, document_name, path_name)
                            VALUES(:pid, GETDATE(),:fname,:fimage)'); //sql insert query

        //bind all parameter
        $insert_stmt->bindParam(':pid',$patient_id);
      $insert_stmt->bindParam(':fname',$image_file);
      $insert_stmt->bindParam(':fimage',$path);


      if($insert_stmt->execute())
      {
        echo "<hr>File Upload Successfully........<hr>"; //execute query success message
        header("refresh:2;patientAddFinish.php"); //refresh 2 second and redirect to index.php page
      }
      }
    }
    catch(PDOException $e)
    {
      echo $e->getMessage();
    }
  }
}
?>
