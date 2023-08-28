<?php

class ActionClass
{

    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }



    public function fetchAll() {
        $sql = "
        SELECT * FROM comments
        ORDER BY comment_date DESC
        ";
        $stmt = $this->db->query($sql);
        $results = [];
        while($row = $stmt->fetch()) {
            $results[] = $row;
        }
        return json_encode($results);
    }

   
    public function deleteComment($id) {
        $sql = "
        DELETE FROM comments
        WHERE id = :comment_id";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute(["comment_id" => $id]);

        if($result) {
            return json_encode($result);
        }

        

    }

    public function insertComment($userName,$userComment) {
        $sql = "
         INSERT INTO comments
        (id,user_name, user_comment,comment_date,pic_id) 
        VALUES (NULL,:user_name, :user_comment,CURRENT_TIMESTAMP,'pic-1')
        ";

        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            ':user_name' => $userName,
            ':user_comment' => $userComment
        ]);

        if($result) {
            return json_encode($result);
        }
    }
}