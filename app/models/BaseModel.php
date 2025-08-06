<?php
abstract class BaseModel{
    public function __get($property){
        if(property_exists($this,$property)){
            return $this->$property;
        }
        throw new Exception("Property $property does not exit");
    }
    public function __set($property,$value){
        if(property_exists($this,$property)){
            $this->$property=$value;
        }else{
        throw new Exception("Property $property does not exit");
        }
    }
    public function toArray(){
        return get_object_vars($this);
       
    }
}
?>