<?php

$connect = new PDO("mysql:host=localhost;dbname=db_url", "root", "BZ424869");
$slug = '';
if(isset($_POST["crear_slug"]))
{
 $slug = preg_replace('/[^a-z0-9]+/i', '-', trim(strtolower($_POST["titulo"])));

 $query = "SELECT slug_url FROM slug WHERE slug_url LIKE '$slug%'";
 
 $statement = $connect->prepare($query); 
 if($statement->execute())
 {
  $total_row = $statement->rowCount();
  if($total_row > 0)
  {
   $result = $statement->fetchAll();
   foreach($result as $row)
   {
    $data[] = $row['slug_url'];
   }
   
   if(in_array($slug, $data))
   {
    $count = 0;
    while( in_array( ($slug . '-' . ++$count ), $data) );
    $slug = $slug . '-' . $count;
   }
  }
 }

 $insert_data = array(
  ':slug_titulo'  => $_POST['titulo'],
  ':slug_url'   => $slug
 );
 $query = "INSERT INTO slug (slug_titulo, slug_url) VALUES (:slug_titulo, :slug_url)";
 $statement = $connect->prepare($query);
 $statement->execute($insert_data);
}

?>
<!DOCTYPE html>
<html>
 <head>
  <title>Cómo crear URL única en PHP</title>
<!-- CSS only -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"  crossorigin="anonymous">

<!-- JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <style>
  .box
  {
   max-width:600px;
   width:100%;
   margin: 0 auto;;
  }
  </style>
 </head>
 <body>
  <div class="container box">
   <br />
   <h3 align="center">Cómo crear URL única en PHP</h3>
   <br />
   <form method="post">
    <div class="form-group">
     <label>Ingrese titulo a crear slug</label>
     <input type="text" name="titulo" class="form-control" required />
    </div>
    <br />
    <div class="form-group">
    <button name="crear_slug" class="btn btn-primary btn-block">Crear Slug</button>
    </div>
    <br />
   </form>
   <?php
if(isset($_POST["crear_slug"]))
{	
	echo'<h4> Titulo Ingresado:</h4>';
	echo'<div class="alert alert-primary" role="alert">
	'.$_POST['titulo'].' 
	</div>';
	
	echo'<h4> Slug Generado:</h4>';
	echo'<div class="alert alert-success" role="alert">
	'.$slug.' 
	</div>';
}
?>
	
  </div>
 </body>
</html>
