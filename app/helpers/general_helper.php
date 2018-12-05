<?php

    function hasErrors($errors,$stepName){
        foreach($errors as $error){
            foreach($error as $errorName){
                if(gettype($errorName)==='array'){
                    if($errorName[0] === $stepName){
                        return true;
                    }
                } else {
                    if($errorName === $stepName){
                        return true;
                    }
                }
                
            }
        }
        return false;
    }

    function mapError($errors,$mapName){
        foreach($errors as $error){
            foreach($error as $errorName){
                if(gettype($errorName)==='array'){
                    if($errorName[1] === $mapName){
                        return true;
                    }
                }
            }
        }
        return false;
    }