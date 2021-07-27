<?php

namespace App\Traits;

use App\Models\Users;

trait GlobalRelations {
    public function createdByUser() {
        return $this->belongsTo(Users::class, 'created_by');
    }

    public function updatedByUser() {
        return $this->belongsTo(Users::class, 'updated_by');
    }

    public function deletedByUser() {
        return $this->belongsTo(Users::class, 'deleted_by');
    }
}
