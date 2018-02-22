<?php
namespace App\Models;

use \App\System\App;
use \App\Models\Model;
use \App\Models\ProductsModel;

class ReportsModel extends Model {

    protected $table = "reports";

    public function generate() {
        $name        = round(microtime(true)) . '.csv';
        $target      = __DIR__ . '/../../public/uploads/' . $name;
        $delimiter   = ',';
        $file        = fopen($target, 'w+');
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

        $model = new ProductsModel();
        $lines = $model->all();
        $lines = (array) $lines;

        foreach($lines as $key => $value){
            $lines[$key] = (array) $lines[$key];
        }

        foreach($lines as $line){
            fputcsv($file, $line, $delimiter);
        }

        fclose($file);

        return $name;
    }

}
