<?php
namespace App\Controllers;

use App\Models\EventModel;

class DebugController extends BaseController
{
    public function checkCategories()
    {
        $model = new EventModel();
        $categories = $model->distinct()->select('category')->findAll();
        echo "<pre>";
        print_r($categories);
        echo "</pre>";
    }
}
