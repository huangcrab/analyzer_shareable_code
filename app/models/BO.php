<?php 
    class BO {

        private $db;
        public function __construct(){
            
            $this->db = new Database;
        }
        
        public function getBOList($snapshotId){
            
        }

        public function getBOsFromJSON($snapshotId){
            $businessObjsSet = array();
            $businessObjsCheck = array();
            
            if(file_exists('.\\process\\'.$snapshotId.'-process.json') && file_exists('.\\process\\'.$snapshotId.'-0.json') && file_exists('.\\process\\'.$snapshotId.'-1.json')){
                $string = file_get_contents('.\\process\\'.$snapshotId.'-process.json');
                $processItem = json_decode($string, true);

                $string = file_get_contents('.\\process\\'.$snapshotId.'-0.json');
                $item0 = json_decode($string, true);

                $string = file_get_contents('.\\process\\'.$snapshotId.'-1.json');
                $item1 = json_decode($string, true);

                $process = [
                    'process' => $processItem,
                    'items' => array_merge($item0, $item1)
                ];

                
                if($process['process']['process_snapshot_id'] === $snapshotId){
                    $items = $process['items'];
                    
                    foreach($items as $item){
                        if(sizeof($item['items']['bos']) > 0){
                            foreach($item['items']['bos'] as $bo){
                                $businessObj = new stdClass();
                                //$businessObj->action = '';
                                foreach($bo['mapping']['input'] as $input){
                                    // echo print_r($input);
                                    // echo '<br>';
                                    if($input['name'] == 'BOVariableName' || $input['name'] == 'variableToCheck'){ //looking for setBO and CheckIf in each Blocks, pull all the bo that being set or check
                                        
                                        $businessObj->name = $input['value'];
                                        if($input['name'] == 'variableToCheck'){
                                            $businessObj->action = 'check';
                                        }else{
                                            $businessObj->action = 'set';
                                        }
                                    }
                                    if($input['name'] == 'BOVariableValue'){
                                       
                                        $businessObj->value = $input['value'];
                                    }
                            
                                }
                                $businessObj->block = $item['name'];
                                if($businessObj->action === 'set'){
                                    $businessObjsSet[] = $businessObj;
                                }else{
                                    $businessObjsCheck[] = $businessObj;
                                }
                            }
                        }

                        if(sizeof($item['items']['gateways']) > 0){
                            foreach($item['items']['gateways'] as $gateway){
                                foreach($gateway['conditions'] as $condition){
                                    $temp = preg_replace('/tw.*!= null|tw.*!=null/', "", $condition['value']); //remote null checks
                                    $tempArray = preg_split('/\&\&|\|\|/',$temp);                               

                                }
                                foreach($tempArray as $logic){
                                    $businessObj = new stdClass();
                                    
                                    if($logic !== null && $logic != '' && $logic != ' ' && strlen($logic) > 5){
                                        $combo = preg_split('/\=\=|\!\=|\<|\>|\<\=|\>\=/', $logic);
                                        if(sizeof($combo) == 2){
                                            $left = $combo[0];
                                            $right = $combo[1];
                                            if(strpos($left, 'tw.local.answer') !== false){
                                                $businessObj->name = $left;
                                                $businessObj->value = $right;
                                                $businessObj->action = 'local';
                                                $businessObj->block = $item['name'];
                                                
                                                $businessObjsCheck[] = $businessObj;
                                            }else{
                                                $businessObj->name = $left;
                                                $businessObj->value = preg_replace('/\(|\)/', '',$right);
                                                $businessObj->action = 'gateway';
                                                $businessObj->block = $item['name']. ' -- ' .$gateway['name'];
                                                
                                                $businessObjsCheck[] = $businessObj;
                                            }
                                        }
                                        else{
                                            $businessObj->name = $gateway['name'];
                                            $businessObj->value = $temp;
                                            $businessObj->action = 'error';
                                            $businessObj->block = $item['name'];
                                            $businessObjsCheck[] = $businessObj;
                                        }
                                    }
                                }
                            }
                        }
                    };
                    $result = [
                        'process' => $snapshotId,
                        'setBo' => $businessObjsSet,
                        'checkBo' => $businessObjsCheck
                    ];
                    return $result;
                }else
                    return false;
            }else{
                return false;
            }
        }
    }
        