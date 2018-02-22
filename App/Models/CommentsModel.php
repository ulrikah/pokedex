<?php
namespace App\Models;

use \App\System\App;
use \App\Models\Model;

class CommentsModel extends Model {

    protected $table = "comments";

    public function getShoutBox(){
        return $this->query("SELECT t.id, t.created_at, t.user, t.text FROM (SELECT comments.id, comments.created_at, comments.user, comments.text FROM comments
                            ORDER BY comments.id DESC
                            LIMIT 5) AS t
                            ORDER BY t.id DESC");
    }
}
