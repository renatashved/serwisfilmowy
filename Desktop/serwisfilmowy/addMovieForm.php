<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Movie</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style>
        .wrapper {
            width: 500px;
            margin: 0 auto;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="text-center">Add New Movie</h2>
                    <form action="addMovie.php" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Categories</label>
                                <?php include 'fetchCategories.php'; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Actor</label>
                            <input type="text" name="actor_name" id="actor_name" class="form-control" required>
                            <input type="hidden" name="actor_id" id="actor_id" value="">
                            <div id="actorList"></div>
                        </div>
                        <div class="form-group">
                            <label>Selected Actors</label>
                            <div id="selected_actors"></div>
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="img" class="form-control" accept="image/*" required>
                        </div>
                        <div class="form-group">
                            <label>Year</label>
                            <input type="number" name="year" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Country</label>
                            <input type="text" name="country" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" value="Add Movie">
                            <a href="index.php" class="btn btn-default">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    $(document).ready(function(){
        $('#actor_name').keyup(function(){
            var query = $(this).val();
            if(query != '')
            {
                $.ajax({
                    url:"searchActors.php",
                    method:"POST",
                    data:{query:query},
                    success:function(data)
                    {
                        $('#actorList').fadeIn();
                        $('#actorList').html(data);
                    }
                });
            }
        });

        $(document).on('click', 'li', function(){
        $('#actor_name').val($(this).text());
        $('#actor_id').val($(this).data('actor_id'));
        $('#actorList').fadeOut();
    });

    $('#actor_name').keydown(function(e){
            if (e.keyCode === 13) {
                e.preventDefault();
                var actorName = $(this).val();
                if (actorName.trim() !== '') {
                    addActorCheckbox(actorName);
                    $(this).val('');
                }
            }
        });

        function addActorCheckbox(actorName) {
            var checkboxId = 'actor_checkbox_' + actorName.replace(/\s+/g, '_');
            var checkboxHtml = '<div class="checkbox"><label><input type="checkbox" name="actors[]" value="' + actorName + '" id="' + checkboxId + '" checked> ' + actorName + '</label></div>';
            $('#selected_actors').append(checkboxHtml);
        }
    });
</script>

</html>
