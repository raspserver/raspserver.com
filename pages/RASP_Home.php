<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Project/PHP/PHPProject.php to edit this template
-->

<?php session_start(); ?>

<html>
    <head>
        <meta charset="UTF-8" name='viewport' content='width=device-width, initial-scale=1'>
        <link rel='stylesheet' href='https://www.w3schools.com/w3css/4/w3.css'>
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
        <title>RASPserver</title>
        <?php
            $toroot = str_repeat(".", substr_count(str_replace("/RASPserver", "", htmlspecialchars($_SERVER["PHP_SELF"])), "/"));
            require $toroot."/class/REQUIRED_RASPBERRY_PHP.php";
            require $toroot."/class/REQUIRED_RASPBERRY_JS.php";
            $REQUIRED_RASPBERRY_PHP = new REQUIRED_RASPBERRY_PHP($toroot);
            $REQUIRED_RASPBERRY_JS = new REQUIRED_RASPBERRY_JS($toroot);
            $PAGES = new PAGES();
        ?>
    </head>
    <body class='w3-light-grey'>
        <?php
            $PAGES->RASP_Home();
        ?>
    </body>
</html>
