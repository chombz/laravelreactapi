<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__);

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,

        // Put opening brace on new line for everything:
        'braces' => [
            'position_after_functions_and_oop_constructs' => 'next',
            'position_after_control_structures' => 'next',
            'position_after_anonymous_constructs' => 'next',
        ],
    ])
    ->setFinder($finder);
