<?php

require_once '../system/function.php';

if ($_POST) {
    $action = post('action');

    if ($action == "add") {
        $title = post('title');
        $desc = post('desc');

        $userId = $_SESSION["id"];

        try {
            $query = $db->prepare("INSERT INTO note (Title, Date, Description, User_Id) VALUES (:title, :date, :desc, :usrid)");
            $query->execute([
                ":title" => $title,
                ":date" => date("Y-m-d H:i:s"),
                ":desc" => $desc,
                ":usrid" => $userId
            ]);

            $noteId = $db->lastInsertId();
            echo json_encode([
                "header" => "ok",
                "message" => "Note added successfully.",
                "noteId" => $noteId
            ]);
        } catch (PDOException $e) {
            echo json_encode([
                "header" => "error",
                "message" => $e->getMessage()
            ]);
        }
    } elseif ($action == "delete") {
        $noteId = post('note_id');
        $userId = $_SESSION["id"];

        try {

            $db->beginTransaction();

            $query = $db->prepare("DELETE FROM note WHERE Id = :noteId AND User_Id = :usrid");
            $query->execute([
                ":noteId" => $noteId,
                ":usrid" => $userId
            ]);

            if ($query->rowCount() > 0) {
                $db->commit();

                echo json_encode([
                    "header" => "ok"
                ]);
            } else {
                $db->rollBack();

                echo json_encode([
                    "header" => "error",
                    "message" => "Note not found or you do not have permission to delete this note."
                ]);
            }
        } catch (PDOException $e) {

            $db->rollBack();

            echo json_encode([
                "header" => "error",
                "message" => "An error occurred: " . $e->getMessage()
            ]);
        }
    } else if ($action == "modify") {
        $noteId = post('noteId');
        $title = post('title');
        $desc = post('desc');
        $userId = $_SESSION["id"];
        $currentDate = date('Y-m-d H:i:s');

        try {
            $db->beginTransaction();

            $query = $db->prepare("UPDATE note SET Title = :title, Description = :desc, Date = :updatedAt WHERE Id = :noteId AND User_Id = :usrid");
            $query->execute([
                ":title" => $title,
                ":desc" => $desc,
                ":updatedAt" => $currentDate,
                ":noteId" => $noteId,
                ":usrid" => $userId
            ]);

            if ($query->rowCount() > 0) {
                $db->commit();

                echo json_encode([
                    "header" => "ok",
                    "message" => "Note updated successfully."
                ]);
            } else {
                $db->rollBack();

                echo json_encode([
                    "header" => "error",
                    "message" => "Note not found or you do not have permission to modify this note."
                ]);
            }
        } catch (PDOException $e) {
            $db->rollBack();

            echo json_encode([
                "header" => "error",
                "message" => "An error occurred: " . $e->getMessage()
            ]);
        }
    }
}
