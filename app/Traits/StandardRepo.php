<?php

namespace App\Traits;

use Prettus\Repository\Criteria\RequestCriteria;
use Exception;

trait StandardRepo {
    public function columns() {
        return $this->model->Columns();
    }

    public function validateColumns($name) {
        $valid = false;
        foreach ($this->columns() as $col) {
            $mixCol = $col.'!';
            if ($name == $col || $name == $mixCol) {
                $valid = true;
                break;
            }
            else $valid = false;
        }
        return $valid;
    }

    public function initModel($id = null) {
        $model = $this->model;
        if (!empty($id)) $model = $this->model->where($this->model->getKeyName(), $id)->first();
        return $model;
    }

    public function boot() {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function totalData($raw_request) {
        $payload = $raw_request->all();
        $data = $this->model;
        if (H_hasRequest($payload, 'trash')) $data = $data->onlyTrashed();
        return $data->count();
    }

    /**
     * @return array
     * 
     * @raw_request : raw data from controller
     * @$raw : true / false : raw query or executed query
     *  
     * Search Usage : 
     * #Related search
     * - single : search={columName}:{value}
     * - multiple : search={columName}:{value}|{columName2}:{value2}
     * - separator : |
     * 
     * #Exact search 
     * - add `!` before `:` , ex : search={columName}!:{value}
     * 
     */
    public function findAll($raw_request, $raw = false) {
        try {
            $payload = $raw_request->all();
            $data = $this->model;
            if (H_hasRequest($payload, 'trash')) $data = $data->onlyTrashed();
            
            $search = [];
            if (isset($payload['search'])) $search = H_extractParamsAttribute($payload['search']);
            
            $order = [];
            if (isset($payload['order'])) $order = H_extractParamsAttribute($payload['order']);

            if (count($search) != 0) { // filter search
                foreach ($search as $key => $params) {
                    if ($this->validateColumns($params['key'])) {
                        $key = H_escapeStringTable($params['key']);
                        $value = H_escapeString($params['value']);

                        $importantCheck = explode('!', $key);
                        $column = $importantCheck[0];

                        if (isset($importantCheck[1])) {
                            $data =    $data->whereRaw(''.$column.' = ? ', [$value]);
                        } else {
                            $data =    $data->whereRaw(''.$column.' = ? OR '.$column.' like ?', [$value,'%'.$value.'%']);
                        }
                    }
                }
            }

            if (count($order) != 0) { // order
                foreach ($order as $key => $params) {
                    if ($this->validateColumns($params['key'])) {
                        $key = H_escapeString($params['key']);
                        $value = H_escapeString($params['value']);
                        $value = strtoupper($value);
                        if ($value == 'ASC' || $value == 'DESC') $data = $data->orderBy($key, $value);
                    }
                }
            }

            if ($raw) return $data;
            else return $data->get();
        } catch (Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function findById($raw_request, $id) {
        $payload = $raw_request->all();
        $data = $this->findAll($raw_request, true);
        $data = $data->where($this->model->getKeyName(), $id)->first();
        return !empty($data) ? $data : null;
    }

    public function getList($raw_request) {
        try {
            $payload = $raw_request->all();
            $data = $this->findAll($raw_request, true);

            $limit = env('PAGINATION_LIMIT', 5);
            if (H_hasRequest($payload, 'limit') && $payload['limit'] != '0') $limit = $payload['limit'];

            if (isset($payload['table'])) return $data->paginate($limit)->withQueryString();
            else {
                if (H_hasRequest($payload, 'limit') && $payload['limit'] == '0') return $data->get();
                else return $data->limit($limit)->get();
            }
        } catch (Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function remove($raw_request, $id) {
        try {
                $request = $raw_request->all();
                $data = $this->model->where($this->model->getKeyName(), $id);

                if(isset($request['permanent'])) {
                    if ($data->first() == null) $data = $data->onlyTrashed();
                }
                $data = $data->first();

                if ($data) {
                    if(isset($request['permanent'])) $data->forceDelete();
                    else $data->delete();
                    return $data;
                } else {
                    return null;
                }

        } catch (Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function restore($raw_request, $id) {
        try {

            $data = $this->model->whereId($id)->onlyTrashed()->first();
            if ($data) return $data->restore();
            else return null;

        } catch (Exception $e){
                throw new Exception($e->getMessage());
        }
    }
}
