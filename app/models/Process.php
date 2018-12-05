<?php 
    class Process {

        private $process_size;
        private $db;
        public function __construct(){
            $this->db = new Database;
            $this->process_size = '';
        }
        public function setSize($size){
            $this->process_size = $size;
        }
        public function getProcesses(){
            $this->db->query('SELECT * FROM processes');

            $results = $this->db->resultSet();
            
            return $results;
        }
        public function getProcessById($id){
            $this->db->query('SELECT * FROM processes WHERE id = :id');
            $this->db->bind(':id', $id);

            $results = $this->db->single();
            
            return $results;
        }
        public function getProcessBySnapId($snapId){
            $this->db->query('SELECT * FROM processes WHERE process_snapshot_id = :snapId');
            $this->db->bind(':snapId', $snapId);

            $results = $this->db->single();
            
            return $results;
        }

        public function deleteProcess($id){
            $this->db->query('DELETE FROM processes WHERE id = :id');
            //bind values
            $this->db->bind(':id', $id);
            //excute
            if($this->db->execute()){
                return true;
            } else {
                return false;
            }
        }

        public function findBlocksFromFile(){
            if(file_exists('.\process\META-INF\package.xml')) {

                $blocks = array();
                $xml = simplexml_load_file('.\process\META-INF\package.xml');
                foreach($xml->objects->object as $obj){
                    if((string)$obj['id'] !== '62.9d62734e-05e2-42d9-b632-d8f50601ba58' && (string)$obj['id'] !== '63.bd816917-79bc-4fef-b431-17d142a944cd' && (string)$obj['id'] !== '2064.4c821f74-314b-42f0-bb52-1a9f3b8bd3e6.2101.34703'){
                        $blocks[] = [
                            'id' => $obj['id'],
                            'name' => $obj['name']
                        ];
                    }
                }
                return $blocks;
            } else {
                return false;
            }
        }

        public function findBOListFromFile(){
            if(file_exists('.\process\META-INF\package.xml')) {

                $blocks = array();
                $xml = simplexml_load_file('.\process\META-INF\package.xml');
                foreach($xml->objects->object as $obj){
                    if((string)$obj['id'] !== '62.9d62734e-05e2-42d9-b632-d8f50601ba58' && (string)$obj['id'] !== '63.bd816917-79bc-4fef-b431-17d142a944cd' && (string)$obj['id'] !== '2064.4c821f74-314b-42f0-bb52-1a9f3b8bd3e6.2101.34703'){
                        $blocks[] = [
                            'id' => $obj['id'],
                            'name' => $obj['name']
                        ];
                    }
                }
                return $blocks;
            } else {
                return false;
            }
        }

        public function loadFromJSON($snapId){
            if(file_exists('.\\process\\'.$snapId.'-process.json') && file_exists('.\\process\\'.$snapId.'-0.json') && file_exists('.\\process\\'.$snapId.'-1.json')) {
                $string = file_get_contents('.\\process\\'.$snapId.'-process.json');
                $process = json_decode($string, true);

                $string = file_get_contents('.\\process\\'.$snapId.'-0.json');
                $item0 = json_decode($string, true);

                $string = file_get_contents('.\\process\\'.$snapId.'-1.json');
                $item1 = json_decode($string, true);

                
                //unset($string);
                return array_merge( $item0, $item1 );
            } else {
                return false;
            }
        }

        

        public function saveFromFile(){
            if(file_exists('.\process\META-INF\package.xml')) {

                $xml = simplexml_load_file('.\process\META-INF\package.xml');
                foreach($xml->target->snapshot->attributes() as $a => $b){
                    if($a == 'id'){
                        $process_snapshot_id = $b;
                    } elseif($a == 'name') {
                        $process_name = $b;
                    } elseif($a == 'originalCreationDate'){
                        $process_created_at = $b;
                    }
                }
            //die($process_name.' @@ '.$process_snapshot_id.' @@ '.$process_created_at.' @@ '.$this->process_size);

            
            $process = [
                'process_name' => $process_name,
                'process_snapshot_id' => $process_snapshot_id,
                'process_created_at' => $process_created_at,
                'process_size' => $this->process_size,
            ];


            $this->db->query('INSERT INTO processes (process_name, process_snapshot_id, process_size, process_created_at) VALUES(:process_name, :process_snapshot_id, :process_size, :process_created_at)');
            $this->db->bind(':process_name', $process_name);
            $this->db->bind(':process_snapshot_id', $process_snapshot_id);
            $this->db->bind(':process_created_at', $process_created_at);
            $this->db->bind(':process_size', $this->process_size);
            

            if($this->db->execute()){
                return true;
            } else {
                return false;
            }


            } else {
                die('File not found');
                return false;
            }
        }

        public function findProcessBySnapId($snapId){
            $this->db->query('SELECT * FROM processes WHERE process_snapshot_id = :snapId');
            $this->db->bind(':snapId', $snapId);

            $row = $this->db->single();

            if($this->db->rowCount() > 0){
                return true;
            } else {
                return false;
            }
        }

        public function findProcessDetailsBySnapId($snapId){
            if(file_exists('.\process\META-INF\package.xml')){
                $xml = simplexml_load_file('.\process\META-INF\package.xml');
                if($xml->target->snapshot['id'] == $snapId){
                    return true;
                }
                else
                    return false;
            } else {
                return false;
            }
        }
    }