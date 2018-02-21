<?php
namespace App\Models;

use \App\System\App;
use \App\Models\Model;

class RevisionsModel extends Model {

    protected $table = "revisions";

    public function revisions($id, $type) {
        return $this->query("SELECT * FROM {$this->table} WHERE type = ? AND type_id = ? ORDER BY date DESC LIMIT 5", [
            $type,
            $id
        ]);
    }

}
