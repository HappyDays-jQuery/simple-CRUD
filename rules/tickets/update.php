<?php
use Respect\Validation\Validator as V;

return [
    'id' => V::notEmpty()->length(1, 10)->numeric()->noWhitespace(),
    'subject' => V::notEmpty(),
];
