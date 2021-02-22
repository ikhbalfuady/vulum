<?php

namespace App\Http\Controllers;

use Exception;
use Validator;
use App\Exports\ExportFromArray;
use Illuminate\Http\Request;
use App\Repositories\MenuItemsRepository;
use App\Providers\HelperProvider;
use App\Providers\AuthProvider;
use Maatwebsite\Excel\Facades\Excel;

class MenuItemsController extends Controller
{
	protected $repository;
	protected $request;

	public function __construct(
        Request $request,
        MenuItemsRepository $repository
    ){
		$this->request = $request;
		$this->repository = $repository;
    }
    
    public function index(Request $request) {
		AuthProvider::has($request, 'menu-items-browse');
        try {
			$payload = $request->all();
			$data = $this->repository->getList($request);
			if (isset($payload['csv'])) return $this->exportCSV($data);
			else return H_apiResponse($data);
        } catch (Exception $e){
			return H_apiResError($e);
        }
	}

	public function findById(Request $request, $id) {
		AuthProvider::has($request, 'menu-items-read');
        try {
			$data = $this->repository->findById($request, $id);
			return H_apiResponse($data);
        } catch (Exception $e){
			return H_apiResError($e);
        }
	}

	public function store(Request $request, $id = null) {
		if ($id) AuthProvider::has($request, 'menu-items-update');
		else AuthProvider::has($request, 'menu-items-create');
		try {
			$validate = $this->validateStore($request, $id);
			if($validate['result']) {
				$data = $this->repository->store($request, $id);
				$msg = 'succes saving data';
				if ($id) $msg = 'success update data';
				return H_apiResponse($data, $msg);
			} else {
				return H_apiResponse(null, $validate['message'], 400);
			}
        } catch (Exception $e){
			return H_apiResError($e);
        }
	}

	public function restore(Request $request, $id = null) {
		AuthProvider::has($request, 'menu-items-restore');
		try {
			$data = $this->repository->restore($request, $id);
			return H_apiResponse($data, 'Data has successfully restored');
        } catch (Exception $e){
            return H_apiResError($e);
        }
	}

	public function remove(Request $request, $id) {
		AuthProvider::has($request, 'menu-items-delete');
		try {
			$payload = $request->all();
			$data = $this->repository->remove($request, $id);
			$msg = 'success deleted data';
			if(isset($payload['permanent'])) $msg = $msg . ' permanently';
			return H_apiResponse($data, $msg);
        } catch (Exception $e){
            return H_apiResError($e);
        }
	}

	public function exportCSV($data) {
		$data = H_toArrayObject($data);
		$export = new ExportFromArray($data);

        $fileName = 'MenuItems-'.H_getCurrentDate();
        return Excel::download($export, ''.$fileName.'.csv');
	}

	// Validator
	public function validateStore($request, $id = null) {
		try {
			$result = true;
			$message = '';
			$payload = $request->all();

			$validator = Validator::make( $request->all(),
				[
                    'name' => 'required',  
                    'slug' => 'required',  

				],
				[
                    'name.required' => 'name is required',  
                    'slug.required' => 'slug is required',  

				]
			);
			if ($validator->fails()) {
				$message = $validator->messages()->first();
				$result = false;
			}

			if (!$this->repository->findById($request, $id)) {
				$message = 'Data not found';
				$result = false;
			}

			return [
				'result' => $result,
				'message' => $message,
			];
        } catch (Exception $e){
			if(env('APP_DEBUG')) return H_apiResError($e);
			else {
				$msg = $e->getMessage();
				return H_apiResponse(null, $msg, 400);
			}
        }
	}

}
  
        