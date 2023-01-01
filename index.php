<?php
session_start();
include("./header.php");
include("./lib/helper.php");

function print_listing($brand, $model, $image, $price, $id)
{
    return <<<HTML
        <div style="display: flex; align-items: center; justify-content: space-between; margin: 10px; padding: 8px; border: 3px solid rgb(244,244,244); border-radius: 4px" class="row">
            <div style="width: 150px; height: 150px; overflow: hidden; object-position: center;">
                <img style='width: 100%;height: 100%; object-fit: contain;' src="$image" alt="">
            </div>
            <div style="width: 100%; padding: 0 20px">
                <h5>$brand</h5>
                <h6>$model</h6>
            </div>
            
            <div>
                <h5><b>$price zł</b></h5>
                <a href="/ski.php?id=$id" class="button button-primary">Sprawdź</a>
            </div>
        </div>
    HTML;
}
// $narta = new narta();
?>

<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Witaj Świecie!</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/skeleton.css">
</head>

<body>
    <?php
    echo draw_header();

    try {
        $conn = init_db();
    } catch (Exception $exc) {
        create_db();
        $conn = init_db();
    }
    $sql = "SELECT * FROM ski_info";
    $result = mysqli_query($conn, $sql);
    ?>
    <div class="container" style="top: 90px">
        <h4>Nasze narty</h4>
        <?php
        while($row = mysqli_fetch_assoc($result)) {
            echo print_listing($row["brand"], $row["model"], $row["img"], $row["price"], $row["id"]);
        }
        // echo create_random($conn);
        ?>
        
    </div>
    
    
    

</body>

</html>