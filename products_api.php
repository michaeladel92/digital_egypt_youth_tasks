<?php
/*
http://shopping.marwaradwan.org/api/Products/1/1/0/100/atoz
   * Fetch the following data for all products from the previous API.    
   products_id 
   products_name,
   products_description,
   products_quantity ,
   products_model,
   products_image,
   products_date_added ,
   products_liked  


   * Insert each raw in Text File  , then Create A page to Diplay the Inserted data from  Text File .

*/ 

// get json API
$reciveJsonTxt = file_get_contents("http://shopping.marwaradwan.org/api/Products/1/1/0/100/atoz");
$data = json_decode($reciveJsonTxt,true);


// get needed array only
$filterArray = [];
foreach($data['data'] as $key => $val){
  $filterArray[$key]['products_id']          = $val['products_id'];
  $filterArray[$key]['products_name']        = $val['products_name'];
  $filterArray[$key]['products_description'] = $val['products_description'];
  $filterArray[$key]['products_quantity']    = $val['products_quantity'];
  $filterArray[$key]['products_model']       = $val['products_model'];
  $filterArray[$key]['products_image']       = $val['products_image'];
  $filterArray[$key]['products_date_added']  = $val['products_date_added'];
  $filterArray[$key]['products_liked']       = $val['products_liked'];
}

$file =  fopen('products.txt','w') or die('unable to open file');
//each array in a row seperated by('__' & '|') add content inside proucts.txt  
foreach($filterArray as $item){
  $row =  $item['products_id'].'__'.$item['products_name'].'__'.$item['products_description'].'__'.$item['products_quantity'].'__'.$item['products_model'].'__'.$item['products_image'].'__'.$item['products_date_added'].'__'.$item['products_liked'] . "|";
    fwrite($file,$row."\n");
}
fclose($file);

// read file
$file =  fopen('products.txt','r') or die('unable to open file');
$arr_a = [];
while(!feof($file)){$arr_a[] = fgets($file);}
fclose($file);

$arr_b = [];
// seperate txt to array 1
foreach($arr_a as $item){
  $item = explode('|',$item);
  $arr_b[] = $item[0]; 
}
array_pop($arr_b); //remove last array as its extra and empty;

$arr_c = [];
// seperate txt to array 2
foreach($arr_b as $item){
  $item = explode('__',$item);
  $arr_c[] = $item;

}

$final_arr = [];
foreach($arr_c as $key => $val){
$final_arr[$key]['products_id']          = $val[0];
$final_arr[$key]['products_name']        = $val[1];
$final_arr[$key]['products_description'] = $val[2];
$final_arr[$key]['products_quantity']    = $val[3];
$final_arr[$key]['products_model']       = $val[4];
$final_arr[$key]['products_image']       = $val[5];
$final_arr[$key]['products_date_added']  = $val[6];
$final_arr[$key]['products_liked']       = $val[7];
}
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>products</title>
  </head>
  <body>
  <table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">id</th>
      <th scope="col">name</th>
      <th scope="col">description</th>
      <th scope="col">quantity</th>
      <th scope="col">model</th>
      <th scope="col">image</th>
      <th scope="col">date_added</th>
      <th scope="col">liked</th>
    </tr>
  </thead>
  <tbody>
    <?php
    foreach($final_arr as $item):
      // i didnt use empty() bec. number [0] reads as empty param
      $products_id = $item['products_id'] === '' ? '-' : $item['products_id'];
      $products_name = $item['products_name'] === '' ? '-' : $item['products_name'];
      $products_description = $item['products_description'] === '' ? '-' : $item['products_description'];
      $products_quantity = $item['products_quantity'] === '' ? '-' : $item['products_quantity'];
      $products_model = $item['products_model'] === '' ? '-' : $item['products_model'];
      $products_image = $item['products_image'] === '' ? '-' : $item['products_image'];
      $products_date_added = $item['products_date_added'] === '' ? '-' : $item['products_date_added'];
      $products_liked = $item['products_liked'] === '' ? '-' : $item['products_liked'];
    ?>
    <tr>
      <th scope="row"><?=$products_id?></th>
      <td><?=$products_name?></td>
      <td><?=$products_description?></td>
      <td><?=$products_quantity?></td>
      <td><?=$products_model?></td>
      <td><?=$products_image?></td>
      <td><?=$products_date_added?></td>
      <td><?=$products_liked?></td>
    </tr>
 <?php endforeach; ?>
  </tbody>
</table>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  </body>
</html>

