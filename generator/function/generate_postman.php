<?php

function generatePostman ($list, $outputDir = '', $host ='http://192.168.43.227:8090', $path ='') {

$_modul = '';
$last = count($list) - 1;
foreach($list as $index =>  $item){
    $coma = ','."\r\n";
    if($index == $last) $coma = '';

    $name = $item->name;
    $selector =  strtolower(splitUppercaseToUnderscore($name));

    $columList = '';
    $last1 = count($item->column) - 1;
    $no = 1;
    foreach ($item->column as $index1 =>  $col) {
        $coma1 = ','."\r\n";
        if($index1 == $last1) $coma1 = '';

        $tab = '                                '; 
        if($no == 1) $tab = '';
        
        $value = '';
        if ($col->type == 'boolean') $value = "false";
        if ($col->type == 'json') $value = "[]";
        if ($col->type == 'dateTimeTz') $value = "2020-11-04T00:00:00.000000Z";
        if ($col->type == 'foreignId') $value = "1";
        if ($col->name == 'id') $value = "";
        $columList .= $tab.'{
                                    "key": "'.$col->name.'",
                                    "value": "'.$value.'",
                                    "type": "text"
                                }'.$coma1;
    $no++;
    }

    $extractPath = '';
    if ($path != '') {
        $pathList = explode('/', $path);
        $last2 = count($pathList) - 1;
        $no1 = 1;
        foreach ($pathList as $index2 => $pathName) {
            $coma2 = ','."\r\n";
            if($index2 == $last2) $coma2 = ',';

            $tab1 = '                                '; 
            if($no1 == 1) $tab1 = '';  
            $extractPath .= $tab1.'"'.$pathName.'"'.$coma2;

        $no1++;}
    }
 
    $_modul .= '        {
            "name": "'.$name.'",
            "item": [
                {
                    "name": "List '.$name.'",
                    "request": {
                        "auth": {
                            "type": "bearer",
                            "bearer": [
                                {
                                    "key": "token",
                                    "value": "",
                                    "type": "string"
                                }
                            ]
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "token",
                                "type": "text",
                                "value": "",
                                "disabled": true
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": ""
                        },
                        "url": {
                            "raw": "{{host}}'. $path . $selector.'",
                            "host": [
                                "{{host}}"
                            ],
                            "path": [
                                '.$extractPath.'
                                "'.$selector.'"
                            ],
                            "query": [
                                {
                                    "key": "table",
                                    "value": null,
                                    "description": "show format table",
                                    "disabled": true
                                },
                                {
                                    "key": "limit",
                                    "value": "10",
                                    "description": "set 0 for all data",
                                    "disabled": true
                                },
                                {
                                    "key": "search",
                                    "value": "name:iqbal",
                                    "description": "related search example",
                                    "disabled": true
                                },
                                {
                                    "key": "search",
                                    "value": "email!:ikhbalfuady@gmail.com",
                                    "description": "exact search example",
                                    "disabled": true
                                },
                                {
                                    "key": "search",
                                    "value": "address:bogor|status:1",
                                    "description": "multiple search example",
                                    "disabled": true
                                },
                                {
                                    "key": "order",
                                    "value": "name:ASC",
                                    "description": "order data example : ASC/DESC",
                                    "disabled": true
                                }
                            ]
                        },
                        "description": "search specific column <br>\r\n?search={columnName}:{value}<br>\r\nmultiple column search separate query with : |<br>\r\nex : ?search=name:john|group:1<br>\r\nexact search use `!` before `:` ex : ?search=name!:john<br>\r\n<hr>\r\norder result<br>\r\n?order={columnName}:{ASC/DESC}<br>\r\n<hr>\r\nreturn table format<br>\r\nadd query params: ?list<br>\r\nexample /modul-page?list<br>\r\n<hr>\r\nLimit fethcing : default 5, use 0 for unlimited<br>\r\nadd query params: ?lmit=5<br>\r\nexample /modul-page?limit=5<br>\r\n<hr>\r\ndownload CSV<br>\r\n/modul-page?csv<br>\r\n<hr>\r\nDeleted Data<br>\r\n/modul-page?trash=true<br>"
                    },
                    "response": []
                },
                {
                    "name": "Detail '.$name.'",
                    "request": {
                        "auth": {
                            "type": "bearer",
                            "bearer": [
                                {
                                    "key": "token",
                                    "value": "",
                                    "type": "string"
                                }
                            ]
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "token",
                                "type": "text",
                                "value": "",
                                "disabled": true
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": ""
                        },
                        "url": {
                            "raw": "{{host}}'. $path . $selector.'/1",
                            "host": [
                                "{{host}}"
                            ],
                            "path": [
                                '.$extractPath.'
                                "'.$selector.'",
                                "1"
                            ]
                        }
                    },
                    "response": []
                },
                {
                    "name": "Save '.$name.'",
                    "request": {
                        "auth": {
                            "type": "bearer",
                            "bearer": [
                                {
                                    "key": "token",
                                    "value": "",
                                    "type": "string"
                                }
                            ]
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "name": "Content-Type",
                                "value": "application/x-www-form-urlencoded",
                                "type": "text"
                            }
                        ],
                        "body": {
                            "mode": "formdata",
                            "formdata": [
                                '.$columList.'
                            ]
                        },
                        "url": {
                            "raw": "{{host}}'. $path . $selector.'",
                            "host": [
                                "{{host}}"
                            ],
                            "path": [
                                '.$extractPath.'
                                "'.$selector.'"
                            ]
                        }
                    },
                    "response": []
                },
                {
                    "name": "Update '.$name.'",
                    "request": {
                        "auth": {
                            "type": "bearer",
                            "bearer": [
                                {
                                    "key": "token",
                                    "value": "",
                                    "type": "string"
                                }
                            ]
                        },
                        "method": "PUT",
                        "header": [
                            {
                                "key": "Content-Type",
                                "name": "Content-Type",
                                "value": "application/x-www-form-urlencoded",
                                "type": "text"
                            }
                        ],
                        "body": {
                            "mode": "urlencoded",
                            "urlencoded": [
                                '.$columList.'
                            ]
                        },
                        "url": {
                            "raw": "{{host}}/'. $path . $selector.'/1",
                            "host": [
                                "{{host}}"
                            ],
                            "path": [
                                '.$extractPath.'
                                "'.$selector.'",
                                "1"
                            ]
                        }
                    },
                    "response": []
                },
                {
                    "name": "Delete '.$name.'",
                    "request": {
                        "auth": {
                            "type": "bearer",
                            "bearer": [
                                {
                                    "key": "token",
                                    "value": "",
                                    "type": "string"
                                }
                            ]
                        },
                        "method": "DELETE",
                        "header": [
                            {
                                "key": "Content-Type",
                                "name": "Content-Type",
                                "value": "application/x-www-form-urlencoded",
                                "type": "text"
                            }
                        ],
                        "body": {
                            "mode": "urlencoded",
                            "urlencoded": [
                                {
                                    "key": "permanent",
                                    "value": "true",
                                    "type": "text"
                                }
                            ]
                        },
                        "url": {
                            "raw": "{{host}}/'. $path . $selector.'/1",
                            "host": [
                                "{{host}}"
                            ],
                            "path": [
                                '.$extractPath.'
                                "'.$selector.'",
                                "1"
                            ]
                        },
                        "description": "default is soft delete\nif want to permanent add body \npermanent : true"
                    },
                    "response": []
                },
                {
                    "name": "Restore '.$name.'",
                    "request": {
                        "auth": {
                            "type": "bearer",
                            "bearer": [
                                {
                                    "key": "token",
                                    "value": "",
                                    "type": "string"
                                }
                            ]
                    	},
						"method": "PUT",
						"header": [
							{
								"key": "Content-Type",
								"name": "Content-Type",
								"value": "application/x-www-form-urlencoded",
								"type": "text"
							}
						],
						"url": {
                            "raw": "{{host}}/'. $path . $selector.'/1",
							"host": [
								"{{host}}"
							],
							"path": [
								'.$extractPath.'
								"'.$selector.'",
                                "1"
							]
						},
						"description": ""
					},
					"response": []
				}
			]
		}'.$coma;

}


   $script = '{
	"info": {
		"_postman_id": "",
		"name": "VuLum API",
		"schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
	},
	"item": [
'.$_modul.',
	    {
			"name": "LOGIN",
			"request": {
				"method": "POST",
				"header": [],
				"body": {
					"mode": "formdata",
					"formdata": [
						{
							"key": "email",
							"value": "boss",
							"type": "text"
						},
						{
							"key": "password",
							"value": "boss",
							"type": "text"
						}
					]
				},
				"url": {
					"raw": "{{host}/login",
					"protocol": "http",
					"host": [
						"{{host}}"
					],
					"path": [
						"login"
					]
				}
			},
			"response": []
		}
	],
	"auth": {
		"type": "bearer",
		"bearer": [
			{
				"key": "token",
				"value": "{{session_token}}",
				"type": "string"
			}
		]
	},
	"event": [
		{
			"listen": "prerequest",
			"script": {
				"id": "6d33cb41-e48a-4661-a82a-4c7346328abf",
				"type": "text/javascript",
				"exec": [
					""
				]
			}
		},
		{
			"listen": "test",
			"script": {
				"id": "6c5c0fcd-0140-46c4-b612-820ac1deba07",
				"type": "text/javascript",
				"exec": [
					""
				]
			}
		}
	],
	"variable": [
		{
			"id": "ba2fd1b1-88a9-4128-8ab3-f4e1aa36569b",
			"key": "host",
			"value": "'.$host.'"
		}
	],
	"protocolProfileBehavior": {}
}
   ';

    if($name != null || $name != ''){
        if (!file_exists($outputDir."")) mkdir($outputDir."", 0777, true); // generate folder module
        $create = fopen($outputDir."/doc.postman_collection.json", "w") or die("Unable to open file!");
        fwrite($create, $script);
        fclose($create);     
    }  


}