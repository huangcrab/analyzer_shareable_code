<?php 
    class Block {
        
        public $name;
        public $id;
        public $color;
        public $items;
        public $mappings;
        public $modified;
        public $errors;
        
        public function __construct($name='',$id=''){
            $this->name = (string)$name;
            $this->id = (string)$id;
            $this->color = 'yellow';
            /*array of items
             * gateways
             * exits
             * scripts
             * bos
             * blocks
             * steps
             * others
             * 
             */
            $this->items = [
                'gateways' => array(),
                'exits' => array(),
                'scripts' => array(),
                'bos' => array(),
                'blocks' => array(),
                'steps' => array(),
                'others' => array(),
            ];
            $this->mappings = array();
            $this->modified = array();
            $this->errors = [
                '1' => array(), //empty gateway
                '2' => array(), //long signal
                '3' => array(), //missing mapping
                '4' => array(), //missing endlink
                '5' => array()  //has default
            ];
        }
        public function getItemsFromJSON($snapshotId,$blockId){
            $steps = array();
            $gateways = array();
            $inputMapping = array();
            $outputMapping = array();
            $bos = array();
            $processes = array();
            $scripts = array();
            $exits = array();
            $lastModified = array();
            $others = array();
            
            if(file_exists(".\\process\\".$snapshotId.'-process.json')&& file_exists(".\\process\\".$snapshotId.'-0.json')&& file_exists(".\\process\\".$snapshotId.'-1.json')){
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
                        if($item['id'] === $blockId){           
                            $steps = $item['items']['steps'];
                            $gateways = $item['items']['gateways'];
                            $input = $item['mappings']['input'];
                            $output = $item['mappings']['output'];
                            $exits = $item['items']['exits'];
                            $scripts = $item['items']['scripts'];
                            $processes = $item['items']['blocks'];
                            $modified = $item['modified'];  
                            $bos = $item['items']['bos'];
                            $others = $item['items']['others'];

                            
                            $block = [
                                'snapshotId' => $process['process']['process_snapshot_id'],
                                'snapshotName' => $process['process']['process_name'],
                                'name' => $item['name'],
                                'id' => $blockId,
                                'items' => $steps,
                                'bos' => $bos,
                                'gateways' => $gateways,
                                'input' => $inputMapping,
                                'output' => $outputMapping,
                                'exits' => $exits,
                                'scripts' => $scripts,
                                'processes' => $processes,
                                'modified' => $modified,
                                'color' => $item['color'],
                                'errors' => $item['errors'],
                                'others' => $others
                            ];     
                        }
                    };
                    return $block;
                }else
                    return false;

            }else{
                return false;
            }
        }
        public function getItemsFromFile($snapshotId,$blockId,$result='RETURN_AS_ARRAY'){
            $items = array();
            $gateways = array();
            $inputMapping = array();
            $outputMapping = array();
            $bos = array();
            $processes = array();
            $scripts = array();
            $exits = array();
            $lastModified = array();
            $errors = array();
            if(file_exists(".\\process\\objects\\".$blockId.'.xml')){
                $xml = simplexml_load_file(".\\process\\objects\\".$blockId.'.xml');
                if(strcmp($xml->process['id'],$blockId) === 0 && $xml->process['name']!=null && $xml->process['id']!=null){
                    $blockObject = new Block($xml->process['name'],$xml->process['id']);
                    foreach($xml->process->processParameter as $param){
                        if($param->parameterType == '1'){
                            $inputMapping[] = [
                                'name' => (string)$param['name'],
                                'hasDefault' => (string)$param->hasDefault
                            ];
                        } elseif($param->parameterType == '2'){
                            $outputMapping[] = [
                                'name' => (string)$param['name'],
                                'hasDefault' => (string)$param->hasDefault
                            ];
                        }
                    }
                    $lastModified['author'] = $blockObject->modified['author'] = (string)$xml->process->lastModifiedBy;
                    $lastModified['time'] = $blockObject->modified['time'] = (string)$xml->process->lastModified;

                    $blockObject->mappings = [
                        'input' => $inputMapping,
                        'output' => $outputMapping
                    ];
                    foreach($xml->process->item as $node){
                        /* processId is the filename in the folder
                         * attachedProcessRef can be used as identifier for forign blocks
                         * 
                         */
                        if($node->tWComponentName == 'Switch'){
                            $conditions = array();
                            $switch = new Gateway($node->name);
                            if(sizeof($node->TWComponent->SwitchCondition) == 0){
                                $blockObject->errors['1'][] = (string)$node->name;
                            }
                            foreach($node->TWComponent->SwitchCondition as $condition){
                                if((string)$condition->condition == ''){
                                    $blockObject->errors['1'][] = (string)$node->name;
                                }
                                $switch->addCondition($condition->seq,$condition->condition);
                                $conditions[] = [
                                    'seq' => $condition->seq,
                                    'value' => $condition->condition
                                ];
                            }
                            $gateways[] = [
                                'name' => $node->name,
                                'conditions' => $conditions
                            ];
                            
                            $blockObject->items['gateways'][] = $switch;
                        } 
                        elseif($node->tWComponentName == 'ExitPoint'){
                            $exit = new ExitPoint($node->name);
                            $blockObject->items['exits'][] = $exit;
                            
                            $exits[] = [
                                'name' => $node->name
                            ];
                        }elseif($node->tWComponentName == 'Script'){
                            $scriptO = new Script($node->name);
                            $scriptO->setValue(filter_var($node->TWComponent->script, FILTER_SANITIZE_STRING));
                            $scripts[] = [
                                'name' => $node->name,
                                'script' => $node->TWComponent->script
                            ];

                            $blockObject->items['scripts'][] = $scriptO;
                        }elseif($node->TWComponent->attachedProcessRef == 'f0396cbd-b91f-4467-80af-4f992138141d/1.3596e7a6-acc3-4ea0-b51a-0754325e47a8' || $node->TWComponent->attachedProcessRef == '1.13f35cfa-a45e-404b-adf5-506aad8cfe46' || $node->TWComponent->attachedProcessRef == 'f0396cbd-b91f-4467-80af-4f992138141d/1.acfefea4-e42e-48fd-ab81-5dc04d662cbe'){
                            // setBO and setBOs and checkif
                            $stepId = $node->TWComponent->attachedProcessRef;
                            $bo = new BO($node->name);
                            $mapping = getInnerBlockMapping($node, $blockObject);
                            if(($stepId == 'f0396cbd-b91f-4467-80af-4f992138141d/1.3596e7a6-acc3-4ea0-b51a-0754325e47a8' && (sizeof($mapping['input']) + sizeof($mapping['output'])) != 8) || ($stepId == 'f0396cbd-b91f-4467-80af-4f992138141d/1.acfefea4-e42e-48fd-ab81-5dc04d662cbe' && (sizeof($mapping['input']) + sizeof($mapping['output'])) != 7)){
                                $blockObject->errors['3'][] = (string)$node->name;
                            }
                            $bo->setMapping($mapping);
                            $bos[] = [
                                'name' => $node->name,
                                'mapping' => $mapping
                            ];
                            $blockObject->items['bos'][] = $bo;
                            
                        }elseif(substr($node->TWComponent->attachedProcessRef,0,1) === '/'){
                            $innerBlock = new InnerBlock($node->name); 
                            $mapping = getInnerBlockMapping($node,$blockObject);
                            $innerBlock->setMapping($mapping);
                            if(hasValue($mapping,'useDefault','true')!==false){
                                $blockObject->errors['5'][] = [(string)$node->name,hasValue($mapping,'useDefault','true')];
                            }
                            $realId = explode('/',$node->TWComponent->attachedProcessRef)[1];
                            $innerBlock->setProcessId($realId);
                            $processes[] = [
                                'name' => $node->name,
                                'processId' => $realId,
                                'mapping' => $mapping
                            ];
                            $blockObject->items['blocks'][] = $innerBlock;
                            $blockObject->color = 'green';

                        }elseif($node->TWComponent->attachedProcessRef == 'f0396cbd-b91f-4467-80af-4f992138141d/1.ae9d3ea1-1d3e-4633-aae5-d5799db5c55f'){
                            // yes/no
                            $step = new Step($node->name,'Yes/No Question');
                            $mapping = getInnerBlockMapping($node, $blockObject);
                            if((sizeof($mapping['input']) + sizeof($mapping['output'])) < 5 || !hasValue($mapping,'name','answer')){
                                $blockObject->errors['3'][] = (string)$node->name;
                            }  
                            $step->setMapping($mapping);
                            $step->setProcessId($node->TWComponent->attachedProcessRef);
                            $items[] = [
                                'name' => $node->name,
                                'processId' => $node->TWComponent->attachedProcessRef,
                                'mapping' => $mapping,
                                'type' => 'Yes/No Question'
                            ];
                            $blockObject->items['steps'][] = $step;

                        }elseif($node->TWComponent->attachedProcessRef == 'f0396cbd-b91f-4467-80af-4f992138141d/1.b1ca7fce-fca5-4029-9443-e65297430b99'){
                            // multiple
                            $step = new Step($node->name,'Multiple Choice Step');
                            $mapping = getInnerBlockMapping($node, $blockObject);
                            
                            if((sizeof($mapping['input']) + sizeof($mapping['output'])) < 5 || !hasValue($mapping,'name','answer')){
                                $blockObject->errors['3'][] = (string)$node->name;
                            }  
                            if($mapping['input'] != null && $mapping['output'] != null){
                                if(haslongSignal($mapping)){
                                    $blockObject->errors['2'][] = (string)$node->name;
                                }
                            } else {
                                $blockObject->errors['3'][] = (string)$node->name;
                            }
                            
                            $step->setProcessId($node->TWComponent->attachedProcessRef);
                            $step->setMapping($mapping);
                            $items[] = [
                                'name' => $node->name,
                                'processId' => $node->TWComponent->attachedProcessRef,
                                'mapping' => $mapping,
                                'type' => 'Multiple Choice Step'
                            ];
                            $blockObject->items['steps'][] = $step;

                        }elseif($node->TWComponent->attachedProcessRef == 'f0396cbd-b91f-4467-80af-4f992138141d/1.9f105ca9-91c2-42aa-bf22-cd743d13c28c'){
                            // info
                            $step = new Step($node->name,'Info Step');
                            $mapping = getInnerBlockMapping($node, $blockObject);
                            if((sizeof($mapping['input']) + sizeof($mapping['output'])) < 4){
                                $blockObject->errors['3'][] = (string)$node->name;
                            }  
                            $step->setProcessId($node->TWComponent->attachedProcessRef);
                            $step->setMapping($mapping);
                            $items[] = [
                                'name' => $node->name,
                                'processId' => $node->TWComponent->attachedProcessRef,
                                'mapping' => $mapping,
                                'type' => 'Info Step'
                            ];
                            $blockObject->items['steps'][] = $step;
                        }elseif($node->TWComponent->attachedProcessRef != '2abf24ee-1e20-4b70-8ac0-a904484beab9/1.aa2b60a0-ae3d-467b-b48f-780085835053'){
                            //ignore EndLoggers
                            $innerBlock = new InnerBlock($node->name);
                            $mapping = getInnerBlockMapping($node, $blockObject);
                            $innerBlock->setProcessId($node->TWComponent->attachedProcessRef);
                            $innerBlock->setMapping($mapping);
                            $items[] = [
                                'name' => $node->name,
                                'processId' => $node->TWComponent->attachedProcessRef,
                                'mapping' => $mapping,
                                'type' => 'Unknown Type'
                            ];
                            $blockObject->items['others'][] = $innerBlock;
                        } 
                        
                    }   
                    //echo json_encode($blockObject);
                    // $fp = fopen('results.json', 'w');
                    // fwrite($fp, json_encode($blockObject));
                    // fclose($fp);
                    $block = [
                        'snapshotId' => $snapshotId,
                        'name' => $xml->process['name'],
                        'id' => $blockId,
                        'items' => $items,
                        'bos' => $bos,
                        'gateways' => $gateways,
                        'input' => $inputMapping,
                        'output' => $outputMapping,
                        'exits' => $exits,
                        'scripts' => $scripts,
                        'processes' => $processes,
                        'modified' => $lastModified,
                        'errors' => $errors
                    ]; 
                    if($result === 'RETURN_AS_ARRAY'){
                        return $block;
                    } elseif($result === 'RETURN_AS_OBJECT') {
                        return $blockObject;
                    }else
                        return false;        
                }
            } else {
                return false;
            }
        }
    }

    function getInnerBlockMapping($node, $object){
        $input = array();
        $output = array();
        $result = [];
        foreach($node->TWComponent->parameterMapping as $param){
            if($param->isInput == 'true'){
                $input[] = [
                    'name' => (string)$param['name'],
                    'value' => (string)$param->value,
                    'useDefault' => (string)$param->useDefault
                ];
            } elseif($param->isInput == 'false'){
                $output[] = [
                    'name' => (string)$param['name'],
                    'value' => (string)$param->value,
                    'useDefault' => (string)$param->useDefault
                ];
            } else {
                $input[] = [
                    'name' => '',
                    'value' => '',
                    'useDefault' => ''
                ];
                $output[] = [
                    'name' => '',
                    'value' => '',
                    'useDefault' => ''
                ];
            } 
        }
        $result = [
            'input' => $input,
            'output' => $output
        ];
        return $result;
    }


    function getProcessType($refId){
        switch($refId){
            case 'f0396cbd-b91f-4467-80af-4f992138141d/1.ae9d3ea1-1d3e-4633-aae5-d5799db5c55f':
                return 'y/n';
            case 'f0396cbd-b91f-4467-80af-4f992138141d/1.3596e7a6-acc3-4ea0-b51a-0754325e47a8':
                return 'setBo';
            case 'f0396cbd-b91f-4467-80af-4f992138141d/1.3596e7a6-acc3-4ea0-b51a-0754325e47a8':
                return 'setBo';
            default:
                return 'Other';
        }
    }

    function haslongSignal($mapping){
        $all = array_merge($mapping['input'],$mapping['output']);
        foreach($all as $map){
            if(strpos($map['name'],'signal') !== false && strlen($map['value']) > 99){
                    return true;
            } 
        }
        return false;
    }

    function hasValue($mapping,$pos,$value){
        $all = array_merge($mapping['input'],$mapping['output']);
        foreach($all as $map){
            if(strpos($map[$pos],$value) !== false){
                if($pos === 'useDefault'){
                    return $map['name'];
                } else {
                    return true;
                }
                
            }
        }
        return false;
    }


    


    Class Item{

        public $name;
        public $type;
        public function __construct($name, $type=''){
            $this->name = (string)$name;
            $this->type = (string)$type;
        }
            
    }

    Class Gateway extends Item{

        public $conditions;
        public function __construct($name){
            parent::__construct($name,'gateway');
            $this->conditions = array();
        }

        public function addCondition($seq, $value){
            $this->conditions[] = [
                'seq' => (string)$seq,
                'value' => (string)$value
            ];
        }
    }

    Class ExitPoint extends Item{

        public function __construct($name){
            parent::__construct($name,'exitPoint');
        }
    }

    Class Script extends Item{
        public $value;

        public function __construct($name){
            parent::__construct($name,'script');
        }

        public function setValue($value){
            $this->value = $value;
        }
    }

    Class InnerBlock extends Item{
        public $mapping;
        public $processId;
        public $color;

        public function __construct($name,$type='process'){
            parent::__construct($name,$type);
            $processId = '';
            $color = '';
            $mapping = array();
        }
        public function setMapping($mapping){
            $this->mapping = $mapping;
        }
        public function setProcessId($id){
            $this->processId = (string)$id;
        }
        public function setColor($color){
            $this->color = $color;
        }
    }
    Class BO extends InnerBlock{
        public function __construct($name){   
            parent::__construct($name,'bo');
            $this->color = 'orange';
        } 
    }
    Class Step extends InnerBlock{
        public $stepType;
        public function __construct($name,$stepType){   
            parent::__construct($name);
            Item::__construct($name,'step');
            $this->stepType = $stepType;
            $this->color = 'blue';
        } 
    }







