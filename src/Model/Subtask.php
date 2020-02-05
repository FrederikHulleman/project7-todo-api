<?php


namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Subtask extends Model
{
    //the only allowable columns to be updated
    protected $fillable = ['task_id','name','status'];

    public function tasks()
    {
        return $this->belongsTo('App\Model\Task')->orderBy('id','asc');
    }
}