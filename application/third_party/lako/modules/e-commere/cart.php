<?php

/**
 *  A Cart library
 *  
 *  @todo Allow option to store cart in cookies+DB so user could visit later.
 */
class lako_cart extends lako_lib_base{
  protected $version = '0.0.1';
  
  function __construct($config=array()){
    parent::__construct($config);
    //initialize cart
    if(is_null(lako::get('session')->get('lako_cart')))
      lako::get('session')->set('lako_cart',array());
    
  }
  
  /**
   * Add a product to cart.
   * 
   * @param String $key Required, Unique key for product, If key is added twice only the count will be updated
   * @param Mixed $data Optional, Some information for product (keep it short).
   * @param Int $qty Optional, Quantity Number, if zero or less product will be removed from cart.
   * @return void
   */
  function add($key, $data = null, $qty = null){
    //if key is present just update the cart
    $sess_cart = lako::get('session')->get('lako_cart');
    
    if(isset($sess_cart[$key])){
      if(is_null($qty))
        $this->update($key, $data, $sess_cart[$key]['qty']+1);
      else
        $this->update($key, $data, $qty);
      return;
    }
    
    $qty = is_null($qty)? 1 : intval($qty);
    if($qty < 1)
      return $this->remove($key);
      
    $sess_cart[$key] = array(
      'data'  => $data,
      'qty'   => $qty
    );
    lako::get('session')->set('lako_cart',$sess_cart);
    return;
  }
  
  /**
   * Add a product to cart.
   * 
   * @param String $key Required, Unique key for product
   * @param Mixed $data Optional, if null no update will happen.
   * @param Int $qty Optional,  if null no update will happen, if zero product will be removed from cart.
   * @return void
   */
  function update($key, $data=null, $qty=null){
    if(is_null($data) && is_null($qty))
      return;
    $product = array();
    if(!is_null($data))
      $product['data'] = $data;
    if(!is_null($qty))
      $product['qty'] = intval($qty);
      
    $qty = intval($product['qty']);
    if($qty < 1)
      return $this->remove($key);
    
    $sess_cart = lako::get('session')->get('lako_cart');
    $sess_cart[$key] = array(
      'data'  => $data,
      'qty'   => $qty
    );
    
    lako::get('session')->set('lako_cart',$sess_cart);
  }
  
  
  /**
   * Remove a product from cart or whole cart.
   * 
   * @param String $key Optional, Unique key for product, if not provided whole cart is emptied.
   * @return void
   */
  function remove($key = null){
    $sess_cart = lako::get('session')->get('lako_cart');
    
    if(is_null($key))
      $sess_cart = array();
    else
      unset($sess_cart[$key]);
      
    lako::get('session')->set('lako_cart',$sess_cart);
  }
  
  /**
   * Remove a product from.
   * 
   * @param String $key optional, Unique key for product, if not provided all the products in cart will be sent.
   * @return Mixed  Cart contents
   */
  function get($key=null){
    $sess_cart = lako::get('session')->get('lako_cart');
    if(is_null($key))
      return $sess_cart;
    return $sess_cart[$key];
  }
  
}