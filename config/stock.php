<?php

return [
    // Quantidade abaixo da qual o produto é considerado 'baixo estoque'
    'low_threshold' => env('STOCK_LOW_THRESHOLD', 5),

    // Quantidade abaixo da qual o produto é considerado 'crítico' (praticamente esgotado)
    'critical_threshold' => env('STOCK_CRITICAL_THRESHOLD', 1),
];
