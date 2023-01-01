<?php
function init_db($db_created = true)
// ułatwione łączenie z bazą
{
    $host = 'localhost';
    $user = 'root';
    $password = '';
    if($db_created){
        return mysqli_connect($host, $user, $password, "ski_database");
    }else{
        return mysqli_connect($host, $user, $password);
    }
}
function gen_userid($name, $surname)
// tworzenie identyfikatora użytkownika
{
    return urlencode($name . $surname);
}
function create_db()
// tworzymy bazę danych z tabelami
{
    $connection = init_db(false);
    $sql = "CREATE DATABASE ski_database;
        USE ski_database;
        CREATE TABLE ski_info (
            id INT NOT NULL AUTO_INCREMENT,
            brand VARCHAR(255) NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            model VARCHAR(255) NOT NULL,
            img VARCHAR(512) NOT NULL,
            PRIMARY KEY (id)
        );
        CREATE TABLE ski_owners (
            id INT NOT NULL AUTO_INCREMENT,
            ski_info_id INT NOT NULL,
            size INT NOT NULL,
            owner_name VARCHAR(255),
            PRIMARY KEY (id),
            FOREIGN KEY (ski_info_id) REFERENCES ski_info(id)
        );";
    mysqli_multi_query($connection, $sql);
}
function create_random($connection)
// dodajemy dane do bazy danych 
{
    // czytamy listę produktów z pliku json i dodajemy pojedyńcze narty do bazy danych.
    $jdoc = file_get_contents("data/models.json");
    $jparse = json_decode($jdoc);
    $sql = "INSERT INTO ski_info (brand, price, model, img) VALUES ";
    foreach($jparse as $brand){
        foreach($brand->skis as $ski){
            $sql .= "('$brand->brand', $ski->cost, '$ski->model', '$ski->img'), ";
        }
    }
    $sql = substr($sql, 0, -2);
    $sql .= ";";
    mysqli_query($connection, $sql);

    // dowiadujemy się o długości tabeli
    $sql = "SELECT COUNT(id) FROM ski_info";
    $sex = mysqli_query($connection, $sql);
    $entries = intval(mysqli_fetch_all($sex)[0][0]);
    
    // tworzymy różne rozmiary do każdej z nart
    for ($id=1; $id <= $entries; $id++) {
        $sql = "INSERT INTO ski_owners (ski_info_id, size) VALUES ";
        for ($size=150; $size < 200; $size+=4) {
            $sql .= "($id, $size), ";
        }
        $sql = substr($sql, 0, -2);
        $sql .= ";";
        echo $sql;
        mysqli_query($connection, $sql);
    }


}
?>