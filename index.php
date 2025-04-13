<?php require_once './system/config.php';
checkUserLogin();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style></style>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>

<body class="container-fluid p-0">

    <!-- Navbar -->
    <nav class="navbar shadow-sm navbar-expand-sm bg-light mb-3" style="height: 5rem;">
        <!-- Logo on the left -->
        <a href="" class="navbar-brand">LOGO</a>

        <!-- Navbar items aligned to the right -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a href="javascript:void(0);" onclick="logout()" class="nav-link">Çıkış Yap</a>
            </li>
        </ul>
    </nav>

    <!-- Page body -->
    <div class="d-flex justify-content-center pt-3">
        <div style="visibility: hidden; width: 3rem;">BOŞ</div>
        <!-- Notes column-->
        <div id="notesContainer" class="notes-column d-flex flex-column justify-content-center" style="width: 30rem;">
            <?php
            $userId = $_SESSION['id'];

            // Query to fetch all notes for the user
            $query = $db->prepare("SELECT * FROM note WHERE User_Id = :usrid");
            $query->execute([":usrid" => $userId]);

            // Fetch all notes as an associative array
            $notes = $query->fetchAll(PDO::FETCH_OBJ);

            // Loop through each note and echo the HTML with dynamic content
            foreach ($notes as $note) {
                // Assign values from the note object
                $noteId = $note->Id; // Assuming id is a column
                $cardHeading = $note->Title; // Assuming title is a column
                $cardText = $note->Description; // Assuming description is a column
                $cardDate = date('d.m.y H:i', strtotime($note->Date)); // Assuming created_at is a timestamp column
            ?>

                <!-- Card HTML for each note -->
                <div class="card shadow-sm mb-3" style="border: none;" id="note-<?php echo $noteId; ?>">
                    <div class="card-body pb-2">
                        <h3 class="card-title m-0"><?php echo htmlspecialchars($cardHeading); ?></h3>

                        <p class="card-text my-2" style="max-width: 100%; height: 3rem; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                            <?php echo htmlspecialchars($cardText); ?>
                        </p>
                        <div class="d-flex justify-content-between">
                            <h6 class="card-date card-subtitle text-muted m-0"><?php echo $cardDate; ?></h6>
                            <div>
                                <a href="javascript:void(0);" class="card-link" data-toggle="modal" data-target="#modifyNoteModal" onclick="review(<?php echo $noteId; ?>)"><i class="fa-solid fa-eye" style="color: #2ea4ff;"></i></a>
                                <a href="javascript:void(0);" class="card-link mr-2" onclick="deleteNote(<?php echo $noteId; ?>)"><i class="fa-solid fa-trash" style="color: #2ea4ff;"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

            <?php
            }
            ?>
        </div>
        <!-- Open Add-Note Modal button -->
        <div class="d-flex flex-column align-items-start" style="display: inline-block; margin-left:1rem;">
            <a href="javascript:void(0);" id="addNoteModalBtn" data-toggle="modal" data-target="#addNoteModal"><i class="fa-solid fa-plus fa-2xl" style="color: #2ea4ff;"></i></a>
        </div>
    </div>

    <!-- Add-note modal -->
    <div class="modal fade" id="addNoteModal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Modal Heading</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <!-- Add-Note Form -->
                    <form id="addNoteForm" action="" method="POST" onsubmit="return false;">
                        <div class="form-group">
                            <label for="title">Note title:</label>
                            <input type="text" class="form-control" id="title" placeholder="Type a title..." name="title" required>
                            <div></div>
                        </div>
                        <div class="form-group">
                            <label for="desc">Type your note:</label>
                            <textarea class="form-control" rows="20" id="desc" placeholder="Type your note..." name="desc" required></textarea>
                            <div></div>
                        </div>
                    </form>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button id="addNoteButton" type="button" class="btn btn-primary" data-dismiss="modal" onclick="addNote()">Save note</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modify-note Modal -->
    <div class="modal fade" id="modifyNoteModal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Modal Heading</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <!-- Add-Note Form -->
                    <form id="modifyNoteForm" action="" method="POST" onsubmit="return false;">
                        <div class="form-group">
                            <label for="title">Note title:</label>
                            <input type="text" class="form-control" id="title" placeholder="Type a title..." name="title" required>
                            <div></div>
                        </div>
                        <div class="form-group">
                            <label for="desc">Type your note:</label>
                            <textarea class="form-control" rows="20" id="desc" placeholder="Type your note..." name="desc" required></textarea>
                            <div></div>
                        </div>
                    </form>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button id="modifyNoteButton" type="button" class="btn btn-primary" data-dismiss="modal">Save note</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery library -->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Popper JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>


    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Font awesome -->
    <script src="https://kit.fontawesome.com/82ff81cd62.js" crossorigin="anonymous"></script>

    <script src="./js/noteManager.js"></script>
    <script src="./js/userManager.js"></script>
</body>

</html>