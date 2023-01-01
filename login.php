<?php
session_start();
include("./header.php");
include("./lib/helper.php");
if (isset($_SESSION["loggedin"])){
    header("location: /");
}
if (isset($_POST["name"]) && isset($_POST["surname"])) {
    // logujemy użytkownika
    $_SESSION["loggedin"] = gen_userid($_POST["name"], $_POST["surname"]);
    $_SESSION["loggedin.name"] = $_POST["name"];
    $_SESSION["loggedin.surname"] = $_POST["surname"];
    header("location: /");
}

?>
<!DOCTYPE html>
<html lang="pl">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/skeleton.css">
        <title>Zaloguj</title>
    </head>

    <body>
        <?php
        echo draw_header()
        ?>
        <div class="container" style="width: 20em">
            <div style="background: rgb(244,244,244); padding: 15px; margin: 100% 0 0 0; border-radius: 4px">
            <h4 style="width: 100%; text-align: center;"><b>Zaloguj Się</b></h4>
                <form method="post">
                    <div class="row">
                        <label for="name">Imię</label>
                        <input type="text" class="u-full-width" name="name" id="name">
                    </div>
                    <div class="row">
                        <label for="surname">Nazwisko</label>
                        <input type="text" class="u-full-width" name="surname" id="surname">
                    </div>
                    <div class="row">
                        <input type="submit" class="button button-primary u-full-width" value="Zaloguj się">
                    </div>
                </form>
            </div>
        </div>
    </body>

</html>