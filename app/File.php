<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    public function folder()
    {
        return $this->belongsTo('\App\Folder', 'folder_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('\App\User', 'user_id', 'id');
    }

}
