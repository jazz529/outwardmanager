<?php
    session_start();

    if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!=true){
        header("location: login.php");
        exit;
    }


    // Server and Database Credentials
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "outward";   

    // Default Values of Alerts
    $insert = false;
    $update = false;
    $delete = false;

    // Create a connection
    $conn = mysqli_connect($servername, $username, $password, $database);

    // Die if connection was not successfull
    if(!$conn){
        die("Sorry we failed to connect".mysqli_connect_error());
    }

    // Get method to delete the record
    if(isset($_GET['delete'])){
        $srno = $_GET['delete'];
        $sql = "DELETE FROM `records` WHERE `records`.`srno` = $srno";
        $result = mysqli_query($conn, $sql);
        $delete = true;
    }

    // Post method to update the record
    if($_SERVER['REQUEST_METHOD'] =='POST'){
        if (isset($_POST['srnoEdit'])){
            // Update the Record
            $srno = $_POST["srnoEdit"];
            $title = $_POST["titleEdit"];
            $description = $_POST["descriptionEdit"];
            $dept = $_POST["deptEdit"];
            $owdate = $_POST["owdateEdit"];
            $ownum = $_POST["ownumEdit"];
            // $owfile = $_FILES["owfileEdit"]["name"];
            // $owfile_tmp = $_FILES["owfileEdit"]["tmp_name"];
            // $owfile_folder = move_uploaded_file($owfile_tmp,'outward_files/'.$owfile);

            //sql query to update the record
            $sql = "UPDATE `records` SET `title` = '$title', `description` = '$description', `dept` =  '$dept', `owdate` =  '$owdate', `ownum` = '$ownum' WHERE `records`.`srno` = '$srno'";
            $result = mysqli_query($conn, $sql);
            $update = true;
        }
        else{
            $title = $_POST["title"];
            $description = $_POST["description"];
            $dept = $_POST["dept"];
            $loc = $_POST["loc"];
            $owdate = $_POST["owdate"];
            $ownum = $_POST["ownum"];
            $owfile = $_FILES["owfile"]["name"];
            $owfile_tmp = $_FILES["owfile"]["tmp_name"];
            $owfile_folder = move_uploaded_file($owfile_tmp,'outward_files/'.$owfile);
            //sql query to be executed
            $sql = "INSERT INTO `records` (`title`, `description`, `dept`, `loc`, `owdate`, `ownum`, `owfile`) VALUES ('$title', '$description', '$dept', '$loc', '$owdate', '$ownum', '$owfile')";
            $result = mysqli_query($conn, $sql);

            // Checking the result
            if($result){
                $insert = true;
            }
            else{
                echo "The Record is not successfully updated";
            }
        }
    }
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- DataTables CSS and JS-->
    <!-- <link rel="stylesheet" href="//cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css"> -->

    <!-- Data table Bootstart theme -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">


    <title>Outward Management System</title>

    <!-- Extra Internal Css -->
    <style>
        .ownew {
            background: none;
            border: none;
            color: white;
        }
        * {
            transition: 2s ease;
        }

        .logo {
            width: 120px;
        }
        #myTable{
            text-align: center;
        }
        .view_btn{
            text-decoration: none;
            color: white;
        }
        .logout{
            margin-left: 1150px;
        }
    </style>

</head>

<body>

    <!-- Navigation bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="home.php"><img src="MCL logo without text.svg" class="logo"></a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="home.php">Home</a>
                    </li>
                    <button type='button' class='ownew' data-bs-toggle='modal'
                        data-bs-target='#exampleModal'>New</button>
                    <li class="nav-item">
                        <a class="nav-link active logout" aria-current="page" name="logout" href="logout.php" type="button">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Modal Form User Creation Form-->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Outward</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <form action="/outwardmanager/home.php" method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" id="title">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="dept" class="form-label">Department</label>
                                <select class="form-select" name="dept" id="dept">
                                    <option>Open this select menu</option>
                                    <option value="HPD">HPD</option>
                                    <option value="IT">IT</option>
                                    <option value="MECH">MECH</option>
                                    <option value="RMD">RMD</option>
                                    <option value="OPRS">OPRS</option>
                                    <option value="ADM">ADM</option>
                                    <option value="LGL">LGL</option>
                                    <option value="CVL">CVL</option>
                                    <option value="PRGS">PRGS</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="loc" class="form-label">Location</label>
                                <select class="form-select" name="loc" id="loc">
                                    <option>Open this select menu</option>
                                    <option value="01">Palm View</option>
                                    <option value="02">Geetanjali</option>
                                    <option value="03">Neelam</option>
                                    <option value="S1">Kolad</option>
                                    <option value="S2">Roha</option>
                                    <option value="S3">Dhatav</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="owdate" class="form-label">Outward Date</label>
                                <input type="date" name="owdate" class="form-control" id="owdate">
                            </div>
                            <div class="mb-3">
                                <label for="ownum" class="form-label">Outward Number</label>
                                <input type="text" name="ownum" class="form-control" id="ownum">
                            </div>
                            <div class="mb-3">
                                <label for="owfile" class="form-label">Outward File</label>
                                <input type="file" name="owfile" class="form-control" id="owfile">
                            </div>
                            <div class="modal-footer">
                                <button type="Submit" class="btn btn-primary">Create</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Modal Form User Update  -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Outward</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/outwardmanager/home.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="srnoEdit" id="srnoEdit">
                        <div class="mb-3">
                            <label for="titleEdit" class="form-label">Title</label>
                            <input type="text" name="titleEdit" class="form-control" id="titleEdit">
                        </div>
                        <div class="mb-3">
                            <label for="descriptionEdit" class="form-label">Description</label>
                            <textarea class="form-control" name="descriptionEdit" id="descriptionEdit"
                                rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="deptEdit" class="form-label">Department</label>
                            <select class="form-select" name="deptEdit" id="deptEdit">
                                <option>Open this select menu</option>
                                <option value="HPD">HPD</option>
                                <option value="IT">IT</option>
                                <option value="MECH">MECH</option>
                                <option value="RMD">RMD</option>
                                <option value="OPRS">OPRS</option>
                                <option value="ADM">ADM</option>
                                <option value="LGL">LGL</option>
                                <option value="CVL">CVL</option>
                                <option value="PRGS">PRGS</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="owdateEdit" class="form-label">Outward Date</label>
                            <input type="date" name="owdateEdit" class="form-control" id="owdateEdit">
                        </div>
                        <div class="mb-3">
                            <label for="ownumEdit" class="form-label">Outward Number</label>
                            <input type="text" name="ownumEdit" class="form-control" id="ownumEdit">
                        </div>
                        <!-- <div class="mb-3">
                            <label for="owfileEdit" class="form-label">Outward File</label>
                            <input type="file" name="owfileEdit" class="form-control" id="owfileEdit">
                        </div> -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save changes</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts for Insert, Update and Delete -->
    <?php
         if($insert){
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
            <strong>Success!</strong> Your record has been <strong>inserted</strong> successfully.
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
        }
    ?>
    <?php
         if($update){
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
            <strong>Success!</strong> Your record has been <strong>updated</strong> successfully.
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
        }
    ?>
    <?php
         if($delete){
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
            <strong>Success!</strong> Your record has been <strong>deleted</strong> successfully.
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
        }
    ?>

    <!-- Data Table to display Records -->
    <div class="container my-5">

        <table class="table" id="myTable">
            <thead>
                <tr>
                    <th scope="col">Sr.No.</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Department</th>
                    <th scope="col">Date</th>
                    <th scope="col">Number</th>
                    <th scope="col">Timestamp</th>
                    <th scope="col">File</th>
                    <th scope="col">Update</th>
                    <th scope="col">Delete</th>

                </tr>
            </thead>
            <tbody>
                <?php
                    $sql = "SELECT * FROM `records`";
                    $result = mysqli_query($conn, $sql);
                    $srno = 0;
                    while($row = mysqli_fetch_assoc($result))
                        {
                            $srno = $srno + 1;
                            echo "<tr>
                                    <th scope='row'>". $srno. "</th>"
                                    ?>
                    <td>
                        <?php echo $row['title']; ?>
                    </td>
                    <td>
                        <?php echo $row['description']; ?>
                    </td>
                    <td>
                        <?php echo $row['dept']; ?>
                    </td>
                    <td>
                        <?php echo $row['owdate']; ?>
                    </td>
                    <td>
                        <?php echo $row['ownum']; ?>
                    </td>
                    <td>
                        <?php echo $row['tstamp']; ?>
                    </td>
                    
                    <td> <button type='button' class='edit btn btn-info btn-sm'> <a href='outward_files/<?php echo $row['owfile']; ?>' target='_blank' rel='noopener noreferrer' class='view_btn'>View</a> </button> 
                    </td>
                    <?php
                        echo"
                            <td> <button type='button' class='edit btn btn-primary btn-sm' id=".$row['srno'].">Edit</button> 
                            </td>
                            <td> <button
                            type='button' class='delete btn btn-danger btn-sm' id=d".$row['srno'].">Delete</button> </td>
                        </tr>";
                ?>
           <?php } ?>
            </tbody>
        </table>
    </div>


    <!-- Optional JavaScript; choose one of the two! -->
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>


    <!-- Option 2: Separate Popper and Bootstrap JS -->

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#myTable').DataTable();
        });
    </script>

    <!-- Script to update data -->
    <script>
        edits = document.getElementsByClassName('edit');
        Array.from(edits).forEach((element) => {
            element.addEventListener("click", (e) => {
                console.log("edit",);
                tr = e.target.parentNode.parentNode;
                title = tr.getElementsByTagName("td")[0].innerText;
                description = tr.getElementsByTagName("td")[1].innerText;
                dept = tr.getElementsByTagName("td")[2].innerText;
                owdate = tr.getElementsByTagName("td")[3].innerText;
                ownum = tr.getElementsByTagName("td")[4].innerText;
                // owfile = tr.getElementsByTagName("td")[5].innerText;
                console.log(title, description, dept, owdate, ownum, owfile);
                titleEdit.value = title;
                descriptionEdit.value = description;
                deptEdit.value = dept;
                owdateEdit.value = owdate;
                ownumEdit.value = ownum;
                srnoEdit.value = e.target.id;
                console.log(e.target.id);
                $('#editModal').modal('toggle');
            })
        })


        // script to delete data
        deletes = document.getElementsByClassName('delete');
        Array.from(deletes).forEach((element) => {
            element.addEventListener("click", (e) => {
                console.log("deletes",);
                srno = e.target.id.substr(1,);

                if (confirm("Are you sure, you want to delete this Outward.")) {
                    console.log("yes");
                    window.location = `/outwardmanager/home.php?delete=${srno}`;
                }
                else {
                    console.log("no");
                }
            })
        })
    </script>
    <!-- Data Table Bootstrap Js -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

</body>

</html>