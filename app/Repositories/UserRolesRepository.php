<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface UserRolesRepository.
 *
 * @package namespace App\Repositories;
 */
interface UserRolesRepository extends RepositoryInterface
{
    public function initModel($id = null);
    public function totalData($request);
    public function findAll($request, $raw = false);
    public function findById($request, $id);
    public function getList($request);
    public function store($request, $id = null, $customRequest = null);
    public function remove($request, $id);
    public function restore($request, $id);
}
