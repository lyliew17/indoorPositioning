<?php
  session_start();

?>
<!DOCTYPE html>
<head>
    
    <title>I-Mall Finder </title>
    <meta name="viewport" id="vp" content="initial-scale=1.0,user-scalable=no,maximum-scale=1,width=device-width" />
    <meta charset="utf-8" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://api.mazemap.com/js/v2.0.19/mazemap.min.css">
    <script type='text/javascript' src='https://api.mazemap.com/js/v2.0.19/mazemap.min.js'></script>

    <script type='text/javascript' src='screenfull.min.js'></script>

    <style>
        /* Some basic page styling */
        html, body { position: absolute; top: 0px; bottom: 0px; left: 0px; right: 0px; margin:0px; padding:0px; font-family: "Helvetica Neue",Helvetica,Arial,sans-serif; font-size: 12px; }

        /* Some custom styling overrides */

            /* bigger zoom-buttons */
            .mapboxgl-ctrl-group > button{
                width: 60px;
                height: 60px;
            }

            /* custom size and color of the floor selector */
            .mazemap-control-zlevel-bar.custom-zlevel-bar{
                background: rgb(255, 255, 255);
                border-radius: 8px;
                box-shadow: 0px 0px 0px 1px #5d5d5d;
                width: 60px;
                margin-right: 20px;
            }

            .mazemap-control-zlevel-bar.custom-zlevel-bar button{
                width: 100%;
                height: 60px;
                padding: 10px;
                color: black;
                font-size: 2em;
                font-weight: bold;
            }
            .mazemap-control-zlevel-bar .z-scroll.scroll-down{
                border-bottom-right-radius: 10px;
                border-bottom-left-radius: 10px;
            }


            /* Styling the search control bigger */
            .search-control-default{
                position: absolute;
                margin-left: calc( (100% - 20px)*(-1/2) );
                top: 10px;
                width: calc(100% - 20px);
                left: 50%;
                max-width: 500px;
                z-index: 999;
            }

            .search-input{
                font-size: 2em;
                height: 80px;
                border-radius: 8px;
                border: 4px solid rgb(48, 152, 253);
            }

            .search-suggestions.default{
                font-size: 2em;
                max-height: calc(100vh - 130px);
            }

            ul.search-suggestions-list.default .item{
                padding: 25px 15px;
            }


            /* Custom you-are-here marker styling */
            .you-are-here-marker {
                display: block;
                border: none;
                border-radius: 50%;
                cursor: pointer;
                padding: 0;
                background: none;
                width: 100px;
                height: 111px;

            }

            /* custom gui elements for this example only */
            .bottom-buttons{
                position: absolute;
                left: 50%;
                transform: translateX(-50%);
                bottom: 10px;
                display: flex;
            }

            .btn{
                background-color: white;
                box-shadow: 0px 0px 3px 0px black;
                padding: 10px 10px;
                border-radius: 4px;
                text-transform: uppercase;
                font-weight: bold;
                cursor: pointer;
                margin: 0px 10px;
            }

            .btn-primary, .btn-secondary
            {
                border: none;
                padding: 1em;
            }

    </style>
</head>
<body>
    
      
      <!-- Modal -->
      <div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalScrollableTitle">Information</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              
            </div>
            <div class="modal-footer">
              <button type="button" class="btn-secondary" data-dismiss="modal">Close</button>              
            </div>
          </div>
        </div>
      </div>
    <div id="map" aria-label="map view" tabindex="1" class="mazemap"></div>
    <div class="bottom-buttons">
        <div onclick="screenfull.toggle();" class="btn" id="fullscreen-btn" style="display: none;">Toggle fullscreen</div>
        <div onclick="resetMap()" class="btn" id="reset-btn">Reset</div>
        <!-- Button trigger modal -->
        <button type="button" style=" z-index: 99;" class="btn-primary" data-toggle="modal" data-target="#exampleModalScrollable">
            Check info
          </button>
          <button type="button" style=" z-index: 99;" class="ml-2 demoBtn btn-primary">
                Demo route
            </button>
            <?php

            if(!isset($_SESSION['user']))
            {
            ?>
            <a href="images/login.php" type="button" style=" z-index: 99;background-color:red;" class="ml-2 demoBtn btn-primary">
            log in
            </a> 
            <?php
            }else
            {
                ?>
                 <a href="comment/index.php" type="button" style=" z-index: 99;" class="ml-2 demoBtn btn-primary">
            leave comment
            </a>
               <a href="logout.php" type="button" style=" z-index: 99;background-color:red;" class="ml-2 demoBtn btn-primary">
            log out
            </a>
            
            <?php
            }
            ?>
            
    </div>

    <div id="search-input-container" style="display: none;" class='search-control-default'>
        <input tabindex="0" id="searchInput" class="search-input" autocomplete="off" type="text" name='search' placeholder='Search'>

        <div id="suggestions" class="search-suggestions default"></div>
    </div>

    <script src="index.js"></script>
     <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>