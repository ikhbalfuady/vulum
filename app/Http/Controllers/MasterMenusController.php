<?php

namespace App\Http\Controllers;

use Exception;
use Validator;
use App\Exports\ExportFromArray;
use Illuminate\Http\Request;
use App\Repositories\MasterMenusRepository;
use App\Providers\HelperProvider;
use App\Providers\AuthProvider;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Users;
use App\Repositories\MenuItemsRepository;
use App\Repositories\MenusRepository;
use App\Repositories\PermissionsRepository;
use Illuminate\Support\Facades\DB;

class MasterMenusController extends Controller
{
    protected $repository;
    protected $menuItemRepository;
    protected $menuRepository;
    protected $permissionRepository;
    protected $request;

    public function __construct(
        Request $request,
        MasterMenusRepository $repository,
        MenuItemsRepository $menuItemRepository,
        MenusRepository $menuRepository,
        PermissionsRepository $permissionRepository
    ){
        $this->request = $request;
        $this->repository = $repository;
        $this->menuItemRepository = $menuItemRepository;
        $this->menuRepository = $menuRepository;
        $this->permissionRepository = $permissionRepository;
    }
    
    public function index(Request $request) {
        AuthProvider::has($request, 'master-menus-browse');
        try {
            $payload = $request->all();
            $data = $this->repository->getListFormater($request);
            if (isset($payload['csv'])) return $this->exportCSV($data);
            else return H_apiResponse($data);
        } catch (Exception $e){
            return H_apiResError($e);
        }
    }

    public function findById(Request $request, $id) {
        AuthProvider::has($request, 'master-menus-read');
        try {
            $data = $this->repository->getMenu($request, $id);
            return H_apiResponse($data);
        } catch (Exception $e){
            return H_apiResError($e);
        }
    }

    public function store(Request $request, $id = null) {
        DB::beginTransaction();
        // if ($id) AuthProvider::has($request, 'master-menus-update');
        // else AuthProvider::has($request, 'master-menus-create');

        try {
            $validate = $this->validateStore($request, $id);
            if($validate['result']) {
                $del = $request->del_menu ?? [];
                $this->menuRepository->deleteMultiple($id, $del);
                $data = $this->repository->store($request, $id);
                $msg = 'succes saving data';
                if ($id) $msg = 'success update data';
                DB::commit();
                return H_apiResponse($data, $msg);
            } else {
                return H_apiResponse(null, $validate['message'], 400);
            }
        } catch (Exception $e){
            DB::rollback();
            return H_apiResError($e);
        }
    }

    public function initMenuAdmin() {
        $menu = '{
            "id": null,
            "name": "Marketing",
            "detail": [
                {
                    "id": null,
                    "detail": { 
                        "id": 1,
                        "name": "Home"
                    }
                },
                {
                    "id": null,
                    "detail": { 
                        "id": 29,
                        "name": "Dashboard"
                    },
                    "children": [
                        {
                            "id": null,
                            "detail": { 
                                "id": 30,
                                "name": "Marketing"
                            }
                        }, {
                            "id": null,
                            "detail": { 
                                "id": 31,
                                "name": "PurchasingSupplyChain"
                            }
                        }, {
                            "id": null,
                            "detail": { 
                                "id": 32,
                                "name": "Warehouse"
                            }
                        }, {
                            "id": null,
                            "detail": { 
                                "id": 33,
                                "name": "ProductionPlanning"
                            }
                        }
                    ]
                },
                {
                    "id": null,
                    "detail": { 
                        "id": 34,
                        "name": "MarketingModules"
                    },
                    "children": [
                        {
                            "id": null,
                            "detail": { 
                                "id": 17,
                                "name": "Customers"
                            }
                        }, {
                            "id": null,
                            "detail": { 
                                "id": 27,
                                "name": "Products"
                            }
                        }, {
                            "id": null,
                            "detail": { 
                                "id": 35,
                                "name": "ProspectMarineForms"
                            }
                        }, {
                            "id": null,
                            "detail": { 
                                "id": 26,
                                "name": "Quotations"
                            }
                        }
                    ]
                },
                {
                    "id": null,
                    "detail": { 
                        "id": 36,
                        "name": "PurchasingAndSupplyChain"
                    },
                    "children": [
                        {
                            "id": null,
                            "detail": { 
                                "id": 25,
                                "name": "VendorGroups"
                            }
                        }, {
                            "id": null,
                            "detail": { 
                                "id": 24,
                                "name": "Vendors"
                            }
                        }, {
                            "id": null,
                            "detail": { 
                                "id": 37,
                                "name": "VendorRegistrations"
                            }
                        }
                    ]
                },
                {
                    "id": null,
                    "detail": { 
                        "id": 28,
                        "name": "MasterData"
                    },
                    "children": [
                        {
                            "id": null,
                            "detail": { 
                                "id": 10,
                                "name": "Countries"
                            }
                        }, {
                            "id": null,
                            "detail": { 
                                "id": 11,
                                "name": "Provinces"
                            }
                        }, {
                            "id": null,
                            "detail": { 
                                "id": 12,
                                "name": "Cities"
                            }
                        }, {
                            "id": null,
                            "detail": { 
                                "id": 13,
                                "name": "ClassSocieties"
                            }
                        }, {
                            "id": null,
                            "detail": { 
                                "id": 14,
                                "name": "Departments"
                            }
                        }, {
                            "id": null,
                            "detail": { 
                                "id": 18,
                                "name": "QuestionnaireGroups"
                            }
                        }, {
                            "id": null,
                            "detail": { 
                                "id": 19,
                                "name": "Questionnaires"
                            }
                        }, {
                            "id": null,
                            "detail": { 
                                "id": 20,
                                "name": "QuestionnaireSections"
                            }
                        }
                    ]
                },
                {
                    "id": null,
                    "detail": { 
                        "id": 38,
                        "name": "Setting"
                    },
                    "children": [
                        {
                            "id": null,
                            "detail": { 
                                "id": 42,
                                "name": "GeneralConfigurations"
                            }
                        },
                        {
                            "id": null,
                            "detail": { 
                                "id": 39,
                                "name": "UserManagement"
                            },
                            "children": [
                                {
                                    "id": null,
                                    "detail": { 
                                        "id": 5,
                                        "name": "Permissions"
                                    }
                                }, {
                                    "id": null,
                                    "detail": { 
                                        "id": 6,
                                        "name": "Roles"
                                    }
                                }, {
                                    "id": null,
                                    "detail": { 
                                        "id": 3,
                                        "name": "Users"
                                    }
                                }
                            ]
                        },
                        {
                            "id": null,
                            "detail": { 
                                "id": 40,
                                "name": "MenuManagement"
                            },
                            "children": [
                                {
                                    "id": null,
                                    "detail": { 
                                        "id": 7,
                                        "name": "MenuItems"
                                    }
                                }, {
                                    "id": null,
                                    "detail": { 
                                        "id": 8,
                                        "name": "MasterMenus"
                                    }
                                }
                            ]
                        },
                        {
                            "id": null,
                            "detail": { 
                                "id": 41,
                                "name": "ModuleManagement"
                            },
                            "children": [
                                {
                                    "id": null,
                                    "detail": { 
                                        "id": 15,
                                        "name": "ParameterGroups"
                                    }
                                }, {
                                    "id": null,
                                    "detail": { 
                                        "id": 16,
                                        "name": "Parameters"
                                    }
                                }, {
                                    "id": null,
                                    "detail": { 
                                        "id": 22,
                                        "name": "ProspectPhaseMapping"
                                    }
                                }
                            ]
                        }
                    ]
                }
            ]
        }';

        $data = $this->repository->store(null, null, json_decode($menu, true));

        $user = Users::find(1);
        $user->menu_id = $data->id;
        $user->save();

        $msg = 'succes init data';
        return H_apiResponse($data, $msg);
    }

    public function initMenu($menu) {
        $data = $this->repository->store(null, null, json_decode($menu, true));
        return $data;
    }

    public function restore(Request $request, $id = null) {
        AuthProvider::has($request, 'master-menus-restore');
        try {
            $data = $this->repository->restore($request, $id);
            return H_apiResponse($data, 'Data has successfully restored');
        } catch (Exception $e){
            return H_apiResError($e);
        }
    }

    public function remove(Request $request, $id) {
        AuthProvider::has($request, 'master-menus-delete');
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

        $fileName = 'MasterMenus-'.H_getCurrentDate();
        return Excel::download($export, ''.$fileName.'.csv');
    }

    public function addNewModule(Request $req) {
        DB::beginTransaction();
        try {
            if ($req->menu) {
                $menu = explode('|', $req->menu);
 
				$data = [];
				foreach ($menu as $key => $name) {
					$data[$name] = [
						"permissions" => $this->permissionRepository->generateNewModule([$name]),
						"menus" => $this->menuItemRepository->generateNewModule([$name])
					];
				}

                DB::commit();
                return H_apiResponse($data, 'Success init module (Only generate if data not available in every module listed)');
            } else {
                return H_apiResponse(null, 'Params menu is required', 400);
            }
        } catch (Exception $e) {
            DB::rollBack();
			return H_apiResError($e);
        }
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
                    'detail' => 'required',

                ],
                [
                    'name.required' => 'name is required',
                    'detail.required' => 'menu items is required' 

                ]
            );
            if ($validator->fails()) {
                $message = $validator->messages()->first();
                $result = false;
            }

            if ($id != null && empty($this->repository->findById($request, $id))) {
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
  
        