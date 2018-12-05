<?php
  class Pages extends Controller {
    public function __construct(){
      
    }

    public function index(){
      //if(isLoggedIn()){
      //  redirect('posts');
      //}
      $data=[
          'title' => 'TWX Analyzer',
          'description' => 'Lazy TWX file Analyzing Tool'
      ];
     $this->view('pages/index',$data);
    }

    public function about(){
      $data=[
        'title' => 'About',
        'description' => 'App to analyze errors in twx file'
      ];
      $this->view('pages/about',$data);
    }

    
  }