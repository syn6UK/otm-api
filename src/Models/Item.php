<?php

namespace App\Models;

class Item extends \Illuminate\Database\Eloquent\Model{

    protected $table = 'item';

    protected $fillable = ['name', 'description'];

    const CREATED_AT = 'dateCreated';
    const UPDATED_AT = 'dateUpdated';

}