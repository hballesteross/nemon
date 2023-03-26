<?php

    require 'db.php';

    $dbconn = new Database("localhost", "scrapper", "root", "");
    $dbconn->conectar();

    $dbconn->cleanDB();

    header("Location: index.php");