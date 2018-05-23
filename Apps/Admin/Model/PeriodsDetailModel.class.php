<?php
namespace Admin\Model;
use Think\Model\RelationModel;

class PeriodsDetailModel extends RelationModel {
    protected $_link=array(
        "User"=>array(
            "mapping_type"=>self::BELONGS_TO,
            "foreign_key"=>"userid",
            "as_fields"=>"nickname",
        ),    
        
    );    
    
}