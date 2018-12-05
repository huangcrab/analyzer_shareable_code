<?php 
    class Content {
        

        private $db;
        public function __construct(){
            $this->db = new Database;
        }
        
        public function saveFromFile($data){
            return true;
        }

        public function findContentByContentId($id){
            $this->db->query('SELECT * FROM content_main WHERE content_id = :id');
            $this->db->bind(':id', $id);

            $row = $this->db->single();

            if($this->db->rowCount() > 0){
                return true;
            } else {
                return false;
            }
        }

        public function findOptions($content_id){
            $this->db->query('SELECT * FROM content_options WHERE content_id = :id');
            $this->db->bind(':id', $content_id);

            $rows = $this->db->resultSet();

            if($this->db->rowCount() > 0){
                return $rows;

            } else {
                return false;
            }
        }



        public function load($json){
            $content_option_id="";
            $content_overlay_id="";
            $success_count = 0;
            if($json) {
                foreach ($json as $content) {
                    $existing_content = $this->findContentByContentId($content->contentId);
                    if($existing_content){
                        if($content->option!== null){
                            $existing_options = $this->findOptions($content->contentId);
                            if($existing_options){

                            }
                        }
                    }else{
                        if($content->options!==null){
                            $content_option_id="";
                            foreach($content->options as $option){
                                $additional_id = "";
                                
                                if(($option->infoTitleEn !== null && $option->infoTitleEn !== "") || ($option->infoTitleFr !== null && $option->infoTitleFr !== "")){
                                    
                                    $this->db->query('INSERT INTO content_additional_info(option_id,body_en, body_fr, title_en, title_fr) VALUES (:option_id, :body_en, :body_fr, :title_en, :title_fr)');
                                    $this->db->bind(':option_id', $option->option_id);
                                    $this->db->bind(':body_en', $option->infoLongDesE ? $option->infoLongDesE : "");
                                    $this->db->bind(':body_fr', $option->infoLongDesF ? $option->infoLongDesF : "");
                                    $this->db->bind(':title_en', $option->infoTitleEn ? $option->infoTitleEn : "");
                                    $this->db->bind(':title_fr', $option->infoTitleFr ? $option->infoTitleFr : "");
                                    if($this->db->execute()){
                                        $additional_id = $this->db->lastInsertId();
                                    }
                                    
                                }
                                if($option->titleEn === null){
                                    $option->titleEn = "MISSING ENGLISH CONTENT";
                                }
                                if($option->titleFr === null){
                                    $option->titleFr = "MISSING FRENCH CONTENT";
                                }
                                $this->db->query('INSERT INTO content_options(option_id,option_body_en, option_body_fr, option_title_en, option_title_fr, additional_id, option_signal,content_id) VALUES (:option_id, :option_body_en, :option_body_fr, :option_title_en, :option_title_fr, :additional_id, :option_signal, :content_id)');
                                $this->db->bind(':option_id', $option->option_id);
                                $this->db->bind(':option_body_en', $option->bodyEn ? $option->bodyEn : "");
                                $this->db->bind(':option_body_fr', $option->bodyFr ? $option->bodyFr : "");
                                $this->db->bind(':option_title_en', $option->titleEn);
                                $this->db->bind(':option_title_fr', $option->titleFr);
                                $this->db->bind(':additional_id', $additional_id);
                                $this->db->bind(':option_signal', $option->signal);
                                $this->db->bind(':content_id',$content->contentId);
                                if($this->db->execute()){
                                    $content_option_id = $this->db->lastInsertId();
                                }
                            }
                        }
    
                        if($content->overlay!==null){
                            $content_overlay_id="";
                            $this->db->query('INSERT INTO content_overlay (overlay_id) VALUES (:overlay_id)');
                            $this->db->bind(':overlay_id', $content->overlay);
                            if($this->db->execute()){
                                $content_overlay_id = $this->db->lastInsertId();
                            }
                        }
                        if($content->titleEn === null){
                            $content->titleEn = "MISSING ENGLISH CONTENT";
                        }
                        if($content->titleFr === null){
                            $content->titleFr = "MISSING FRENCH CONTENT";
                        }
                        $this->db->query('INSERT INTO content_main (block_id, step_id, step_name, snapshot_id,overlay_id, option_id, title_en, title_fr, content_id,body_en,body_fr,is_markdown ) VALUES (:block_id, :step_id, :step_name, :snapshot_id,:overlay_id, :option_id, :title_en, :title_fr, :content_id,:body_en,:body_fr,:is_markdown)');
                        $this->db->bind(':block_id', $content->blockId);
                        $this->db->bind(':step_id', $content->stepId);
                        $this->db->bind(':step_name', $content->stepName);
                        $this->db->bind(':snapshot_id', $content->snapshotId);
                        $this->db->bind(':overlay_id', $content_overlay_id);
                        $this->db->bind(':option_id', $content_option_id);
                        $this->db->bind(':title_en', $content->titleEn);
                        $this->db->bind(':title_fr', $content->titleFr);
                        $this->db->bind(':content_id', $content->contentId);
                        $this->db->bind(':body_en', $content->bodyEn ? $content->bodyEn : "");
                        $this->db->bind(':body_fr', $content->bodyFr ? $content->bodyFr : "");
                        $this->db->bind(':is_markdown', $content->isMarkdown);
                        if($this->db->execute()){
                            $success_count ++;
                        }
                    }
                }

            } else {
                die('File not found');
                return false;
            }

            if($success_count === sizeof($json)){
                return true;
            }else{
                return $success_count;
            }
        }

    }