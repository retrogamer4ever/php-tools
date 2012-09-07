<?php



/**
 * Description of MongoUtil
 *
 * @author dennislong5
 */
class MongoUtil 
{
  
  /**
   *
   * This will create a new mongo id for the object passed
   * and create a new property called "id" (Or set existing) 
   * with the newely created id.
   * 
   * @param mixed $objectRef object to create mongo id for
   * @return the objectRef with the new mongo id on it 
   * 
   */
  public static function setMongoId( $objectRef )
  {
    $mongoId = new MongoId();
    $objectRef['_id'] = $mongoId;
    $objectRef['id'] = $mongoId->__toString();
    
    return $objectRef;
  }

  public static function setMongoIdForClasses( $objectRef )
  {
    $mongoId = new MongoId();
    $objectRef->_id = $mongoId;
    $objectRef->id = $mongoId->__toString();
    
    return $objectRef;
  }
}


?>
