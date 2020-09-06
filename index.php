<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
</head><body>
 
<?php
 
 $conn=new mysqli('localhost','root','','image');
if (count($_POST) && (strpos($_POST['img'], 'data:image') === 0)) {
     
  $img = $_POST['img'];
   
  if (strpos($img, 'data:image/jpeg;base64,') === 0) {
      $img = str_replace('data:image/jpeg;base64,', '', $img);  
      $ext = '.jpg';
  }
  if (strpos($img, 'data:image/png;base64,') === 0) {
      $img = str_replace('data:image/png;base64,', '', $img); 
      $ext = '.png';
  }
   
  $img = str_replace(' ', '+', $img);
  $data = base64_decode($img);
  $file = 'uploads/img'.date("YmdHis").$ext;
  $name='img'.date("YmdHis").$ext;
  
   
  if (file_put_contents($file, $data)) {
	  $sql="INSERT INTO upload(image)VALUES('$name')";
	  $query=$conn->query($sql) or die(mysqli_error($conn));
	  if($query){
      echo "<p>The image was saved as $file.</p>";
	  }
	  else{
		  echo 'failure';
	  }
  } else {
      echo "<p>The image could not be saved.</p>";
  } 
   
}
                      
?>
 
 
<input id="inp_file" type="file">
 
<form method="post" action="">
  <input id="inp_img" name="img" type="hidden" value="">
  <input id="bt_save" type="submit" value="Upload">
</form>
 <?php
 $sql=$conn->query("SELECT * FROM upload") or die(mysqli_error($conn));
 for ($i=0; $i=$row=$sql->fetch_array(); $i++){
	 ?>
	  <img src="uploads/<?php echo  $i['image'];  ?>"><br/>
	  <?php
 }
 ?>
 
<script>
 
  function fileChange(e) { 
     document.getElementById('inp_img').value = '';
     
     var file = e.target.files[0];
 
     if (file.type == "image/jpeg" || file.type == "image/png") {
 
        var reader = new FileReader();  
        reader.onload = function(readerEvent) {
   
           var image = new Image();
           image.onload = function(imageEvent) {    
              var max_size = 400;
              var w = image.width;
              var h = image.height;
             
              if (w > h) {  if (w > max_size) { h*=max_size/w; w=max_size; }
              } else     {  if (h > max_size) { w*=max_size/h; h=max_size; } }
             
              var canvas = document.createElement('canvas');
              canvas.width = w;
              canvas.height = h;
              canvas.getContext('2d').drawImage(image, 0, 0, w, h);
                 
              if (file.type == "image/jpeg") {
                 var dataURL = canvas.toDataURL("image/jpeg", 1.0);
              } else {
                 var dataURL = canvas.toDataURL("image/png");   
              }
              document.getElementById('inp_img').value = dataURL;   
           }
           image.src = readerEvent.target.result;
        }
        reader.readAsDataURL(file);
     } else {
        document.getElementById('inp_file').value = ''; 
        alert('Please only select images in JPG- or PNG-format.');  
     }
  }
 
  document.getElementById('inp_file').addEventListener('change', fileChange, false);    
         
</script>
 
</body></html>