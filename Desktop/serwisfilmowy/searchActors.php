<?php
require_once "config.php";

if(isset($_POST['query']))
{
    $query = $_POST['query'];
    $output = '';
    $query = "SELECT id, CONCAT(name, surname) as name FROM actors WHERE CONCAT(name, surname) LIKE '%$query%'";
    $result = mysqli_query($conn, $query);

    $output .= '<ul class="list-unstyled">';
    if(mysqli_num_rows($result) > 0)
    {
        while($row = mysqli_fetch_array($result))
        {
            $output .= '<li>'.$row["name"].'</li>';
        }
    }
    else
    {
        $output .= '<li>Actor not found</li>';
    }
    $output .= '</ul>';
    echo $output;
}
?>
