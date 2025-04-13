<?php
require_once "./system/config.php";


$userId = $_SESSION['id'];

$query = $db->prepare("SELECT * FROM note WHERE User_Id = :usrid");
$query->execute([":usrid" => $userId]);

$notes = $query->fetchAll(PDO::FETCH_OBJ);

foreach ($notes as $note) {
    $noteId = $note->Id;
    $cardHeading = $note->Title;
    $cardText = $note->Description;
    $cardDate = date('d.m.Y H:i', strtotime($note->Date));
?>


    <div class="card shadow-sm mb-3" style="border: none;" id="note-<?php echo $noteId; ?>">
        <div class="card-body pb-2">
            <h3 class="card-title m-0"><?php echo htmlspecialchars($cardHeading); ?></h3>

            <p class="card-text my-2" style="max-width: 100%; height: 3rem; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                <?php echo htmlspecialchars($cardText); ?>
            </p>
            <div class="d-flex justify-content-between">
                <h6 class="card-subtitle text-muted m-0"><?php echo $cardDate; ?></h6>
                <div>
                    <a href="#" class="card-link"><i class="fa-solid fa-eye" style="color: #2ea4ff;"></i></a>
                    <a href="javascript:void(0);" class="card-link mr-2" onclick="deleteNote(<?php echo $noteId; ?>)"><i class="fa-solid fa-trash" style="color: #2ea4ff;"></i></a>
                </div>
            </div>
        </div>
    </div>

<?php
}
?>