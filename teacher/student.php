<?php 
session_start();
if (isset($_SESSION['teacher_id']) && 
    isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'Teacher') {
       include "../DB_connection.php";
       include "data/student.php";
       include "data/teacher.php";
       include "data/class.php";
       include "data/section.php";

       $teacher_id = $_SESSION['teacher_id'];
       $teacher = getTeacherById($teacher_id, $conn);
       $class = getClassById($teacher['section'],$conn);
       $students = getAllSectionStudents($conn, $class['class_id'], $teacher['section']);
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Students</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/style.css">
	<link rel="icon" href="../logo.png">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <?php 
    include "header.php";
        include "inc/navbar.php";
        
      ?>
      
     <div class="container mt-5" style="min-height:70vh;">
      <?php
        if ($students != 0) {
     ?>
        
           <form action="student-search.php" 
                 class="mt-3 n-table"
                 method="get">
             <div class="input-group mb-3">
                <input type="text" 
                       class="form-control"
                       name="searchKey"
                       placeholder="Search...">
                <button class="btn btn-primary">
                        <i class="fa fa-search" 
                           aria-hidden="true"></i>
                      </button>
             </div>
           </form>

           <?php if (isset($_GET['error'])) { ?>
            <div class="alert alert-danger mt-3 n-table" 
                 role="alert">
              <?=$_GET['error']?>
            </div>
            <?php } ?>

          <?php if (isset($_GET['success'])) { ?>
            <div class="alert alert-info mt-3 n-table" 
                 role="alert">
              <?=$_GET['success']?>
            </div>
            <?php } ?>

           <div class="table-responsive">
              <table class="table table-bordered mt-3 n-table">
                <thead>
                  <tr>
                    <th scope="col">Roll No</th>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Username</th>
                    <th scope="col">Class</th>
                    <th scope="col">Section</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $i = 0; foreach ($students as $student ) { 
                    $i++;  ?>
                  <tr>
                    <td><?=$student['roll_no']?></td>
                    <td>
                      <a href="student-view.php?student_id=<?=$student['student_id']?>">
                        <?=$student['fname']?>
                      </a>
                    </td>
                    <td><?=$student['lname']?></td>
                    <td><?=$student['username']?></td>
                    <td>
                      <?php 
                          $class_id = $student["class_id"];
                          $cl = getClassById($class_id, $conn);
                          echo $cl["class_name"]; 
                        ?>
                    </td>
                    <td>
                      <?php 
                          $section_id = $student["section_id"];
                          $cl = getSectionById($section_id, $conn);
                          echo $cl["section"]; 
                        ?>
                    </td>
                    <td>
                        <a href="student-edit.php?student_id=<?=$student['student_id']?>"
                           class="btn btn-warning">Edit</a>
                    </td>
                  </tr>
                <?php } ?>
                </tbody>
              </table>
           </div>
         <?php }else{ ?>
             <div class="alert alert-info .w-450 m-5" 
                  role="alert">
                No data
              </div>
         <?php } ?>
     </div>
     
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>	
    <script>
        $(document).ready(function(){
             $("#navLinks li:nth-child(2) a").addClass('active');
        });
    </script>
<br/><br/>
    <?php
    include "footer.php"
    ?>
</body>
</html>
<?php 

  }else {
    header("Location: ../login.php");
    exit;
  } 
}else {
	header("Location: ../login.php");
	exit;
} 

?>