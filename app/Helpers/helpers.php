<?php
    if(!function_exists("calculate_tax_amount")){
        function calculate_product_amount_with_tax($price,$tax_rate){
            return (floatval($price * 0.08) + $price) * 100 ;
        }
    }     
?>  