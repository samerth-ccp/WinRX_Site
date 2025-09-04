<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Color extends Model
{
    //use SoftDeletes;

    protected $guarded = ['color_id'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_colors';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'color_id';


    protected $deletedAt = 'color_deleted_at';

    const CREATED_AT = 'color_created_at';
    const UPDATED_AT = 'color_updated_at';

    /** Return Delete Column */
    public function getDeletedAtColumn(){
        return $this->deletedAt;
    }

    // Override newQuery method to always exclude soft-deleted records by default
    public function newQuery($excludeDeleted = true, $withTrashed = false){
        // Exclude soft-deleted records by default
        $query = parent::newQuery($excludeDeleted);

        // Include soft-deleted records if explicitly requested
        if (!$excludeDeleted || $withTrashed) {
            $query->withTrashed();
        }

        return $query;
    }
}
