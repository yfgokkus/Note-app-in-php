<?php

require_once '../system/function.php';

header('Content-Type: application/json');

if ($_POST) {

    $action = isset($_POST['action']) ? $_POST['action'] : '';

    ob_clean();

    if ($action == "register") {
        $name    = post('name');
        $sname   = post('sname');
        $usrname = post('usrname');
        $pwd     = post('pwd');
        $pwd2    = post('pwd2');

        if (!$name || !$sname || !$usrname || !$pwd || !$pwd2) {
            echo json_encode(['header' => 'empty', 'message' => 'empty fields']);
            return;
        }

        if ($pwd !== $pwd2) {
            echo json_encode(['header' => 'match', 'message' => 'Passwords do not match']);
            return;
        }

        if (preg_match('/\s/', $usrname) || preg_match('/[^a-zA-Z0-9]/', $usrname) || preg_match('/\s/', $pwd)) {
            echo json_encode(['header' => 'format', 'message' => 'Invalid username or password format']);
            return;
        }

        $query = $db->prepare("SELECT Username FROM user_auth WHERE Username = :usn");
        $query->execute([':usn' => $usrname]);

        if ($query->rowCount()) {
            echo json_encode(['header' => 'already', 'message' => 'Username already exists']);
            return;
        }

        $pwdHashed = password_hash($pwd, PASSWORD_DEFAULT);

        try {
            $db->beginTransaction();

            $query1 = $db->prepare("INSERT INTO user SET Name = :name, Surname = :surname");
            $query1->execute([
                ':name' => $name,
                ':surname' => $sname
            ]);

            $userId = $db->lastInsertId();

            $query2 = $db->prepare("INSERT INTO user_auth SET Username=:usrname, Password=:pwd, User_Id=:usrid");
            $query2->execute([
                ':usrname' => $usrname,
                ':pwd' => $pwdHashed,
                ':usrid' => $userId
            ]);

            $db->commit();
            echo json_encode(['header' => 'ok']);
        } catch (Exception $e) {
            $db->rollBack();
            echo json_encode(['header' => 'error', 'message' => $e->__toString()]);
        }
    } else if ($action == "login") {
        $usrname = post('usrname');
        $pwd     = post('pwd');

        $query = $db->prepare("SELECT User_Id, Username, Password FROM user_auth WHERE Username = :usrname");
        $query->execute([':usrname' => $usrname]);

        $user = $query->fetch(PDO::FETCH_OBJ);

        if ($user && password_verify($pwd, $user->Password)) {
            session_regenerate_id(true);
            $_SESSION['id'] = $user->User_Id;
            $_SESSION['username'] = $user->Username;
            echo json_encode(['header' => 'ok']);
        } else {
            echo json_encode(['header' => 'wrong', 'message' => 'Invalid credentials']);
        }
    } else if ($action == "logout") {

        session_unset();
        session_destroy();

        echo json_encode(['header' => 'ok']);
        exit();
    } else {
        echo json_encode(['header' => 'error', 'message' => 'Invalid action']);
    }
}
