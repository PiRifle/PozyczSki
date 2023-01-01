<?php 
    function draw_header(){
        // echo var_dump($_SESSION);
        if(isset($_SESSION["loggedin.name"])){
            $name = $_SESSION["loggedin.name"];
        }else{
            $name = "";
            
        }
        return <<<HTML
            <div style="top: 0; padding: 14px 10px; background: white; position: fixed; width: 100%;z-index: 20; box-shadow: 0px 0px 20px rgba(0,0,0,0.2); margin: 0 0 30px 0">
            <h4 style="width: min-content; margin: 0; display: inline-block"><b>PożyczSki.</b></h4>
                <div style="float: right; margin: 0 20px; display: flex; height: 40px; align-items: center">
                    <a style="margin: 4px" href="/">Główna</a>
                    <a style="margin: 4px" href="/account.php">$name</a>
                </div>
            </div>
        HTML;
    }
?>
