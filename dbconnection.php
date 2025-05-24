<?php
    class connectionParams {}
    $param = new connectionParams;

    // 'host' for the PostgreSQL server
    $param->host = "localhost";

    // default port for PostgreSQL is "5432"
    $param->port = "5432";

    // set the database name for the connection
    $param->dbname = "komuni40_komunitas";

    // set the username for PostgreSQL database
    $param->user = "komuni40_rakhmat";

    // password for the PostgreSQL database
    $param->password = "Postgresp3w3d3^_^";

    $hostString = "";
    // use an iterator to concatenate a string to connect to PostgreSQL
    foreach ($param as $key => $value) {
      // concatenate the connect params with each iteration
      $hostString = $hostString . $key . "=" . $value . " ";
    }
        //echo $hostString . "\n";

    $conn = pg_connect($hostString);

?>