<!DOCTYPE html>
<!--
 October, 2020 Walter Newsome
 B.S. Software Systems
 -->
<html>
<body>
<style>
#code {
   width: 900px;
   font-family: "Lucida Console", Courier, monospace;
   text-align: left;
   font-size: 18px;
}
#comment{
   color: green;
}
</style>
<head>
<title>Online Image Reformer</title>
</head>
<center>
<h1>Online Image Reformer</h1>
Convert PNG images into C code for use with the MSP430 Graphics Library.
</br>
</br>
</br>
<form action="" method="post" enctype="multipart/form-data">
  Select image:
  <input type="file" accept="image/png" name="image" id="image">
  <br /><br /><input type="submit" value="Process Image" name="submit">
</form>

</body>
</html>
<script>
function forceDownload(name, href) {
	var anchor = document.createElement('a');
	anchor.href = href;
	anchor.download = name+".c";
	document.body.appendChild(anchor);
	anchor.click();
}
</script>

<?php
   if(isset($_FILES['image'])){
      $errors= array();
      $file_name = $_FILES['image']['name'];
      $file_size = $_FILES['image']['size'];
      $file_tmp = $_FILES['image']['tmp_name'];
      $file_type = $_FILES['image']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));
      
      $extensions= array("png");
      
      if(in_array($file_ext,$extensions)=== false){
         $errors[]="Extension not allowed. For now, only PNG images are allowed.";
      }
      
      if($file_size > 2097152) {
         $errors[]='The image is too big!';
      }
      
      if(empty($errors)==true) {
         move_uploaded_file($file_tmp,"uploads/".$file_name);
         $c_name = substr($file_name, 0, -4);
         ?>

         <script>
          forceDownload("<?php echo $c_name ?>","get_c_file.php?name=<?php echo $c_name ?>&file=uploads/<?php echo $file_name ?>");
         </script>
         <br>
         <br>
         <div id ="code">
         <p id="comment">// 1. Download <a href="get_c_file.php?name=<?php echo $c_name ?>&file=uploads/<?php echo $file_name ?>" target="_blank"><?php echo $c_name ?>.c</a><br><br>
         // 2. Include the image </p>
         extern Graphics_Image <?php echo $c_name ?>8BPP_UNCOMP;<br>
         <p id="comment">// 3. Draw image <p>
         Graphics_drawImage(&g_sContext, &<?php echo $c_name ?>8BPP_UNCOMP, 0, 0);
         <div>
         <?php
      }else{
         print_r($errors);
      }
   }
?>
</center>