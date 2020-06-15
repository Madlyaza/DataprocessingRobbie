<?php
function databaseConnection()
{
    // De functie die wordt aangeroepen als de RestApi een Get oproep krijgt. 
    // Deze is gesplitst van de Post, Delete en Put omdat deze heel andere dingen moet doen
    $host = "localhost"; //Localhost if using Xampp
    $user = "root"; //User login
    $password = "123"; //database password login
    $databaseName = "dataprocessing"; //database name

    $conn = mysqli_connect($host, $user, $password, $databaseName);

    if($conn)
    {
        echo "<p>Connection succesfull</p>";
        return true;
    }
    else
    {
        echo "<p>Unable to connect to the database.</p><p> error: " . mysqli_errno() . ": " . mysqli_error() . "</p>";
        return false;
    }
}
?>