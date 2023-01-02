<?php
session_start();

// sprawdzamy czy jest podane id
if (!isset($_GET["id"])) {
    header("location:/");
    exit();
}

include("./header.php");
include("./lib/helper.php");
$conn = init_db();

//gdy zostanie nam wysłany request post (w tym przypadku prośba wypożyczenia narty to rejestrujemy użytkownika do podanej narty)
if(isset($_POST["size_id"]) && isset($_SESSION["loggedin"])){
    $id = intval($_POST["size_id"]); 
    $owner = $_SESSION['loggedin'];
    $sql = "UPDATE ski_owners SET owner_name = '$owner' WHERE id = $id AND owner_name IS NULL";
    mysqli_query($conn, $sql);
    $requested_lend = true;
}

// wyciągamy z bazy danych wszystkie rozmiary oraz dane wybranej narty
$ski_id = $_GET["id"];
$sql = "SELECT * FROM ski_owners o INNER JOIN ski_info i ON o.ski_info_id = i.id WHERE i.id = $ski_id";
$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_all($result);
if (count($data) == 0) {
    header("location:/");
    exit();
}

// ułatwiamy życie układając te dane w sensowny schemat
$data_display = [
    "brand" => $data[0][5],
    "price" => $data[0][6],
    "model" => $data[0][7],
    "img" => $data[0][8],
    "sizes" => array_map(function ($a) {
        return ["id" => $a[0], "size" => $a[2], "has_owner" => !is_null($a[3])];
    }, $data)
];
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wypożycz <?php echo $data_display["brand"] . " " . $data_display["model"] ?></title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/skeleton.css">
    <style>
        .switch-field {
            display: flex;
            flex-wrap: wrap;
            overflow: hidden;
            align-items: flex-end;
            align-content: start;
        }

        .switch-field input {
            position: absolute !important;
            clip: rect(0, 0, 0, 0);
            height: 1px;
            width: 1px;
            border: 0;
            overflow: hidden;
        }

        .switch-field label {
            margin: 4px;
            transition: all 0.3s ease-in-out;
            display: inline-block;
            height: 38px;
            padding: 0 10px;
            color: #555;
            text-align: center;
            font-size: 11px;
            font-weight: 600;
            line-height: 38px;
            letter-spacing: .1rem;
            text-transform: uppercase;
            text-decoration: none;
            white-space: nowrap;
            background-color: transparent;
            border-radius: 4px;
            border: 1px solid #bbb;
            cursor: pointer;
            box-sizing: border-box;
        }

        .switch-field label:hover {
            cursor: pointer;
        }

        .switch-field input:checked+label {
            color: #FFF;
            background-color: #33C3F0;
            border-color: #33C3F0;
        }
    </style>
</head>

<body>
    <?php echo draw_header() ?>
    <div class="container" style="top: 86px; padding: 25px; border: 3px solid rgb(244,244,244)">
        <h3>Narty <?php echo $data_display["model"] ?></h3>
        <h5><?php echo $data_display["brand"] ?></h5>
        <form action="" method="post">
            <div style="display:flex; width: 100%; justify-content: space-around; flex-wrap: wrap">
                <div style="min-width: 300px; width: 500px; height: 500px; overflow: hidden; object-position: center;">
                    <img style='width: 100%;height: 100%; object-fit: contain;' src="<?php echo $data_display["img"] ?>" alt="">
                </div>
                <div style="width: calc( 100% - 500px ); min-width: 340px; margin: 20px 0 0 0">
                    <h5>Cena</h5>
                    <h4><?php echo intval(floatval($data_display["price"]) / 35);?> zł</h4>
                    <h5>Wybierz Rozmiar</h5>
                    <div class="switch-field">
                        <?php
                        function draw_radio($id, $size, $has_owner)
                        {
                            if (!isset($_SESSION["loggedin"])) {
                                return <<<HTML
                                    <input type="radio" id="radio-$id" name="size_id" value="$id" disabled/>
                                    <label for="radio-$id">$size</label>
                                HTML;
                            }
                            if ($has_owner) {
                                return <<<HTML
                                        <input type="radio" id="radio-$id" name="size_id" value="$id" disabled/>
                                        <label for="radio-$id" style="color: red; border-color: red">$size</label>
                                        HTML;
                                    } else {
                                        return <<<HTML
                                        <input type="radio" id="radio-$id" name="size_id" value="$id"/>
                                        <label for="radio-$id">$size</label>
                                        HTML;
                                    }
                        }
                        foreach ($data_display["sizes"] as $size) {
                            echo draw_radio($size["id"], $size["size"], $size["has_owner"]);
                        }
                        
                        ?>

                    </div>
                    <br>
                        <?php 
                        if(!isset($_SESSION["loggedin"])){
                            ?>
                            <p>Aby móc kontynuować, Musisz sie zalogować</p>
                            <a href="/login.php" class="button button-primary">Zaloguj się</a>
                            <?php
                        }else{
                            ?>
                            <button class="button-primary" style="margin: 15px">Wypożycz</button>
                            <?php
                            if ($requested_lend){
                                echo "Dziękujemy za wypożyczenie! Miłej jazdy :D"
                            }
                        }
                        ?>
                </div>
            </div>
        </form>
    </div>
</body>

</html>