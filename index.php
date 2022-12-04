<?php 
    // initialize errors variable
	$errors = "";
   $incomplete = '';
	// connect to database
	$db = mysqli_connect("localhost", "root", "", "todo");

	// insert a quote if submit button is clicked
	if (isset($_POST['submit'])) {
		if (empty($_POST['task'])||empty($_POST['category'])) {
			$errors = "You must fill in the task and category";
		}else{
			$task = $_POST['task'];
         $category = $_POST['category'];
			$sql = "INSERT INTO task (task, category) VALUES ('$task', '$category')";
			mysqli_query($db, $sql);
			header('location: index.php');
		}
	}	

	// delete task
if (isset($_GET['del_task'])) {
	$id = $_GET['del_task'];

	mysqli_query($db, "DELETE FROM task WHERE id=".$id);
	header('location: index.php');
}
// complete task
if (isset($_GET['done_task'])) {
	$id = $_GET['done_task'];
   $cat = $_GET['cat'];


$check = mysqli_fetch_all(mysqli_query($db, "SELECT * FROM task WHERE category='$cat'"));
$che =  array_search($id, array_column($check, 0));
if($che==0){
mysqli_query($db, "UPDATE task SET completed=1 WHERE id=".$id);
header('location: index.php');
}else{
$be = $check[$che==0?0:$che-1];

if($be[3]==0){
   $incomplete='Complete earlier tasks in this category';
   
} else{
   mysqli_query($db, "UPDATE task SET completed=1 WHERE id=".$id);
header('location: index.php');
}
}

	
}
   ?>
<!DOCTYPE html>
<html>
<head>
	<title>ToDo App</title>
   <link rel="stylesheet" type="text/css" href="style.css">
   <!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

</head>
<body>
   
	<div class="heading">
		<h2 style="font-style: 'Hervetica';">ToDo Application PHP & MySQL</h2>
	</div>
	<form method="post" action="index.php" class="input_form">
   <?php if (isset($errors)) { ?>
	<p><?php echo $errors; ?></p>
<?php } ?>
<div class="mb-3">
  <label for="formGroupExampleInput" class="form-label">New task</label>
  <input type="text" name="task" class="form-control" id="formGroupExampleInput">
</div>
<div class="mb-3">
  <label for="formGroupExampleInput2" class="form-label">Category</label>
  <input type="text" class="form-control" name="category" id="formGroupExampleInput2">
</div>
<button type="submit" name = 'submit' class="btn btn-primary">Add Task</button>
		
	</form>
   <div class='error'>
   <?php if (isset($incomplete)) { ?>
	<p><?php echo $incomplete; ?></p>
<?php } ?></div> 
<table >
	<thead>
		<tr>
			<th>#</th>
			<th>Tasks</th>
         <th>Category</th>
			<th style="width: 60px;">Action</th>
		</tr>
	</thead>
   
	<tbody>
		<?php 
		// select all tasks if page is visited or refreshed
		$tasks = mysqli_query($db, "SELECT * FROM task");

		$i = 1; while ($row = mysqli_fetch_array($tasks)) { ?>
			<tr>
				<td> <?php echo $i; ?> </td>
				<td class="task">  <?php echo $row['task']; ?> </td>
            <td class="task"> <?php echo $row['category']; ?> </td>
				<td class="action">

            <div class="row complete" >
					<a href="index.php?done_task=<?php echo $row['id'] ?>&cat=<?php echo $row['category'] ?>"><?php echo  $row['completed']==0? 'Complete': 'Done' ?></a>
            
            </div>
               <div class="row delete" >
					<a href="index.php?del_task=<?php echo $row['id'] ?>">Delete</a></div>
              
				</td>
			</tr>
		<?php $i++; } ?>	
	</tbody>
</table>



<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</body>
</html>