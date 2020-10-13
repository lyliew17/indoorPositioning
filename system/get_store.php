<?php

include '../config.php';
include '../images/functions.php';

$_SESSION['isAdmin'] = true;

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
<br />
  <h2 align="center"><a href="#">Store information</a></h2>
  <br />

<div class="container">
    <div class="d-flex flex-row flex-wrap justify-content-between">
        <?php
        $sql = "SELECT s.*, c.Value FROM Shop s LEFT JOIN Category c ON c.ID = s.Category";
        $query = $dbh->prepare($sql);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);

        foreach ($results as $result) {
            ?>
            <div class="card mx-2 my-3" style="width: 22rem; height:500px; overflow-y:auto;">
                
                    <img class="card-img-top" style="max-height:300px; max-width:300px; width:100%; height:100%;object-fit:contain;" src="../uploads/<?php echo $result->Image ?>" alt="" />

                <div class="card-body">
                    <h5 class="card-title"><?php echo $result->Name ?></h5>
                    <small>Floor <?php echo $result->Floor ?></small>
                    <p class="card-text"><?php echo $result->Description ?></p>
                    <p class="card-text"><?php echo $result->Value ?></p>
                    <?php
                        if ($_SESSION['isAdmin'] == true) {
                            ?>
                        <a href="../admin/edit_store.php?id=<?php echo $result->ID ?>">Edit</a>
                        
                        <a style="color:red;"href="../admin/delete_store.php?id=<?php echo $result->ID ?>">Delete</a>
                        
                    <?php
                        }
                        ?>
                </div>
            </div>

        <?php
        }
        ?>
    </div>

    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/js/bootstrap.min.js" integrity="sha384-3qaqj0lc6sV/qpzrc1N5DC6i1VRn/HyX4qdPaiEFbn54VjQBEU341pvjz7Dv3n6P" crossorigin="anonymous"></script>
 
</body>

</html>