<?php

use App\Models\Category;

    function getCategories(){
        return Category::orderBy('name', 'ASC')
                        ->where('show_home', 'Yes')
                        ->where('status', 1)
                        ->with('getSubCategories')
                        ->get();
    }
?>