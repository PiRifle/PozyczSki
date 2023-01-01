<?php
session_start();
if (!isset($_SESSION["loggedin"])) {
    header("location: /");
    exit();
}
require("header.php");
require('lib/helper.php');
$conn = init_db();
$user = $_SESSION["loggedin"];
if (isset($_POST["remove_id"])) {
    $id = $_POST["remove_id"];
    $sql = "UPDATE ski_owners SET owner_name = NULL WHERE id = $id";
    $result = mysqli_query($conn, $sql);
}

$sql = "SELECT * FROM ski_owners o INNER JOIN ski_info i ON o.ski_info_id = i.id WHERE o.owner_name = '$user'";
$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_all($result);
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/skeleton.css">
    <title>Moje Konto</title>
</head>

<body>
    <?php echo draw_header() ?>
    <div class="container" style="top: 80px">
        <h5><b>Wypożyczone narty</b></h5>
        <?php if (count($data) > 0) { ?>
            <form method="post">
                <table class="u-full-width">
                    <thead>
                        <tr>
                            <th>Marka</th>
                            <th>Model</th>
                            <th>Rozmiar</th>
                            <th>Zarządzaj</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($data as $row) {
                        ?>
                            <tr>
                                <td><?php echo $row[5]; ?></td>
                                <td><?php echo $row[7]; ?></td>
                                <td><?php echo $row[2]; ?></td>
                                <td>
                                    <button name="remove_id" value="<?php echo $row[0] ?>">Oddaj</button>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </form>
        <?php } else {
        ?><h2 style="text-align: center">Nie masz żadnych nart do oddania</h2><?php } ?>
        <h5><b>Zarządzaj kontem</b></h5>
                <a href="logout.php"  class="button button-primary u-full-width">Wyloguj się</a>
            

    </div>
</body>

</html>