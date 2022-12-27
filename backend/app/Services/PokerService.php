<?php

namespace App\Services;

class PokerService {
   
    public function alerts() {
       
            return response(rand(10, 100), 200);
      
    }
}
