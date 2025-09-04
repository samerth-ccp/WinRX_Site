<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Size extends Model
{
    //use SoftDeletes;

    protected $guarded = ['size_id'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_size';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'size_id';


    protected $deletedAt = 'size_deleted_at';

    const CREATED_AT = 'size_created_at';
    const UPDATED_AT = 'size_updated_at';

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
