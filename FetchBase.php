<?php
/**
 *  the common fetch base class
 *  
 *  @version 0.1
 *  @author Jay Marcyes {@link http://marcyes.com}
 *  @since 5-15-10
 *  @project Fetch
 ******************************************************************************/      
abstract class FetchBase {

  /**
   *  hold values
   *
   *  @var  array   
   */
  protected $field_map = array();

  /**
   *  the path the response body was saved to
   *
   *  @return string
   */
  public function getPath(){
    return empty($this->field_map['response_path']) ? '' : $this->field_map['response_path'];
  }//method
  
  /**
   *  the filepath that the response will be saved to
   *  
   *  @param  string  $val  
   */
  public function setPath($val){
    $this->field_map['response_path'] = $val;
  }//method

  /**
   *  return all the headers
   *  
   *  @return array
   */
  public function getHeaders(){
    return empty($this->field_map['headers']) ? array() : $this->field_map['headers'];
  }//method
  
  /**
   *  get a specific header
   *  
   *  @param  string  $header_name  the name of the header (eg, Content-type)
   *  @param  mixed $default_val  the default value if $header_name is not found   
   *  @return string  the header value, or $default_val if not found
   */
  public function getHeader($header_name,$default_val = ''){
    return empty($this->field_map['headers'][$header_name]) ? $default_val : $this->field_map['headers'][$header_name];
  }//method

  /**
   *  parse raw headers into a key/val map
   *  
   *  @param  string  $headers  the raw headers that will be parsed
   */
  protected function parseHeaders($headers){
  
    $ret_map = array();
    $header_list = explode("\r\n",$headers);
    
    // the first header is the resuest/response...
    $ret_map['http'] = $header_list[0];
    
    // now parse the headers...
    foreach(array_slice($header_list,1) as $header){
    
      list($header_name,$header_val) = $this->parseHeader($header);
      
      if(!empty($header_name)){
        
        if(isset($ret_map[$header_name])){
          
          $ret_map[$header_name] = (array)$ret_map[$header_name];
          $ret_map[$header_name][] = $header_val;
          
        }else{
        
          $ret_map[$header_name] = $header_val;
        
        }//if/else
        
      }//if
    
    }//foreach
  
    return $ret_map;
  
  }//method

  /**
   *  parses the header into a name and a value
   *
   *  @param  string  $header the full header (eg, name: val)
   *  @return array array($header_name,$header_val)   
   */
  protected function parseHeader($header){
    
    // canary...
    if(ctype_space($header)){ return array('',''); }//if
    
    $header_parts = explode(':',$header,2);
  
    $header_name = $header_val = '';  
    if(isset($header_parts[1])){
    
      $header_name = trim($header_parts[0]);
      $header_val = trim($header_parts[1]);
    
    }else{
    
      if(empty($header_parts[1])){
      
        $header_val = trim($header_parts[0]);
      
      }else{
      
        $header_name = 'other';
        $header_val = trim($header_parts[0]);
        
      }//if
    
    }//if/else
    
    return array($header_name,$header_val);
    
  }//method
  
}//class
