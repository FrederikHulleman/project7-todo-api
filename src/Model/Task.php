<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //the only allowable columns to be updated
    protected $fillable = ['task','status'];

    public function subtasks()
    {
        return $this->hasMany('App\Model\Subtask')->orderBy('id','asc');
    }

}