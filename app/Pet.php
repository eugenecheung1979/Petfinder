<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    //table
    protected $table = 'pets';

    //primary key
    protected $primaryKey = 'pid';

    

}
