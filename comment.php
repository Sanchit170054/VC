<?php
session_start();
$postID = $_GET['recordId'];
$_SESSION['uploadPostID'] = $postID;
$userid = $_SESSION['ID'];
$host = "localhost: 3306";
    $dbUsername = "root";
    $dbPassword = "";
	$dbname = "communityDB";

        $connect = new mysqli($host,$dbUsername,$dbPassword, $dbname);
        if($connect->connect_error){
            echo "<script type='text/javascript'>alert('Error: Connection is failed..!');</script>";
           
        }

		
				$N_postID = $_SESSION['uploadPostID'];
				$N_userID = $_SESSION['ID']; 
				$N_touserID = $_SESSION['to_userID'];
				$N_view = 0;
				$N_comment = $_SESSION['comment'];

				echo $N_postID. " " .$N_userID. " " .$N_touserID. " " .$N_view. " ".$N_comment;
				
				
				$sql = "INSERT INTO notification (post_id, user_id, comment, to_user_id, viewed)
								VALUES ($N_postID, $N_userID, '$N_comment', $N_touserID, $N_view)";

								if ($connect->query($sql) === TRUE) {
									echo "New record created successfully";
										
								} else {
									echo "Error: " . $sql . "<br>" . $connect->error;
}

?>




<!DOCTYPE html>
<html>
 <head>
 
  <title>Comment System using PHP and Ajax</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
 </head>
 
 <body>
 
 
  <br />
  <h2 align="center">Comment System using PHP and Ajax</h2>
  <br />
  <div class="col-md-8 results " style="margin-left: 20%";>
                <table class="table"style="margin-top: 20px; font-size: 20px">
                    <thead id="thead" style="color: rgb(0,123,255)">
                    <tr>
                        <th><center> POST </center></th>
                    </tr>
                    </thead>
                <tbody>
                <?php
                    include_once "CRUD.php";
                    $common = new CRUD();
					
                    $records = $common->fetchRecordById($connect, $postID);
                    if ($records->num_rows>0){
                        $sr = 1;
                        while ($record = $records->fetch_object()) {
							
				            $recordId = $record->id;
							$personID = $record->userID;
							$p_tittle = $record->tittle;
							$p_description = $record->description;
							$p_cate = $record->category;
							$p_image = $record->image;
							$p_time = $record->posttime;
							$p_date = $record->postdate
							
							
							?>
                            
                            <tr>
                                <!-- <td>
                                <th><?php echo $sr;?></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th><?php echo $department;?></th>
                                <a href="delete-script.php?recordId=<?php echo $recordId?>" class="btn btn-danger btn-sm">Delete</a>
                                <a href="delete-script.php?recordId=<?php echo $recordId?>" class="btn btn-danger btn-sm">Create</a>
                                </td> -->
                            <td >
                                <div style='position:center;background-color:lightgrey; text-align:center'>
                                    <h3 style="text-align:center"> <?php echo "Post by: ".$personID;
									$_SESSION['to_userID'] = $personID;  ?></h3>
									<p style="font-size: 14px; text-align:center"> <?php echo "on ".$p_date . " at " .$p_time;?></p> 
									
                                    <h4 style="text-align: center"> <?php echo $p_tittle;?></h4><br>
									<img src="<?php echo $p_image;?>" style="height: 250px; width: 250px"><br>
									<p style="text-align:left; margin-left: 20px"> <?php echo $p_description;?></p><br>
									
                                   </div>
                            </td>
                            </tr>
                            <?php
                            $sr++;
                        }
                    }
                    ?>
                    </tbody>
                </table>
        </div>
        </div>
		
  <div class="container">
	
  
   <form method="POST" id="comment_form">
    <div class="form-group">
     <textarea name="comment_content" id="comment_content" class="form-control" placeholder="Enter Comment" rows="5"></textarea>
    </div>
    <div class="form-group">
     <input type="hidden" name="comment_id" id="comment_id" value="0" />
     <input type="submit" name="submit" id="submit" class="btn btn-info" value="Submit" />
    </div>
   </form>
   <span id="comment_message"></span>
   <br />
   <div id="display_comment"></div>
  </div>
 </body>
</html>

<script>
$(document).ready(function(){
 
 $('#comment_form').on('submit', function(event){
  event.preventDefault();
  var form_data = $(this).serialize();
  $.ajax({
   url:"add_comment.php",
   method:"POST",
   data:form_data,
   dataType:"JSON",
   
   success:function(data)
   {
    if(data.error != '')
    {
     $('#comment_form')[0].reset();
     $('#comment_message').html(data.error);
     $('#comment_id').val('0');
     load_comment();
    }
	
   }
  })
 });

 load_comment();

 function load_comment()
 {
  $.ajax({
   url:"fetch_comment.php",
   method:"POST",
   success:function(data)
   {
    $('#display_comment').html(data);
   }
   
  })
 }

 $(document).on('click', '.reply', function(){
  var comment_id = $(this).attr("id");
  $('# ').val(comment_id);
  $('#comment_name').focus();
 });
 
 
});
</script>


<?php 


		
?>
