<?php

include '../config.php';


if (isset($_POST['submit'])) {

    // Check if image file is a actual image or fake image

  

        $newCategory = array(                    
            "Value" => $_POST['Category'],
            
        );

        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            "Category",
            implode(", ", array_keys($newCategory)),
            ":" . implode(", :", array_keys($newCategory))
        );        

        $statement = $dbh->prepare($sql);
        $statement->execute($newCategory);

        echo "<script>alert('Post successful')</script>"; 
        echo "<script>document.location='../system/get_store.php'</script>";
    
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css" integrity="sha384-SI27wrMjH3ZZ89r4o+fGIJtnzkAnFs3E4qz9DIYioCQ5l9Rd/7UAa8DHcaL8jkWt" crossorigin="anonymous">
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="#"><img src="../images/indoormap.png" height="50" width="50"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="http://localhost/indoorPositioning/admin/home.php">Home <span class="sr-only">(current)</span></a>
      </li>
    
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Dropdown
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="http://localhost/indoorPositioning/system/get_store.php">View shop data</a>
          <a class="dropdown-item" href="http://localhost/indoorPositioning/comment/index.php">View user comment</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="http://localhost/indoorPositioning/admin/create_category.php">Create new category</a>
          <a class="dropdown-item" href="http://localhost/indoorPositioning/admin/create_store.php">Create new shop</a>
        </div>
      </li>
    
    </ul>

  </div>
</nav>


<table class="table">
 
<thead class="thead-dark">
    <tr>
      <th scope="col">#</th>
      <th scope="col">Category</th>
    
    </tr>
  </thead>



    <form method="POST" style="max-width:500px" class="mx-auto d-flex flex-column" enctype="multipart/form-data">

    <?php
                $sql = "SELECT * FROM category";            
                $query = $dbh->prepare($sql);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);

                foreach ($results as $result) {
            ?>


    <tr>
      <th scope="row"><?php echo $result->ID ?></th>
      <td><?php echo $result->Value ?></td>
      
    </tr>
    

 
  

                         
            <?php
                }
            ?>
            </table>
                 
            <label for="Category">Category</label>
        <input type="text" name="Category" />

     
          
        <input type="submit" name="submit" value="Add" />

       
    </form>

     <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/js/bootstrap.min.js" integrity="sha384-3qaqj0lc6sV/qpzrc1N5DC6i1VRn/HyX4qdPaiEFbn54VjQBEU341pvjz7Dv3n6P" crossorigin="anonymous"></script>
</body>

</html>