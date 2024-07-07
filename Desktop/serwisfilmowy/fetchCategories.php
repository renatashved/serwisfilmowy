<?php
include("session.php");
include("config.php");

$query = "SELECT id, name FROM categories";
$result = mysqli_query($conn, $query);

echo '<div class="row">';
while ($row = mysqli_fetch_assoc($result)) {
    echo '<div class="col-md-3">';
    echo '<div class="form-check">';
    echo '<input class="form-check-input" type="checkbox" name="categories[]" value="' . $row['id'] . '" id="category' . $row['id'] . '">';
    echo '<label class="form-check-label" for="category' . $row['id'] . '">' . $row['name'] . '</label>';
    echo '</div>';
    echo '</div>';
}
echo '</div>';
?>

<div id="selectedCategories"></div>