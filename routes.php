<?php

return [
  '~^/$~' => [
    'controller' => MainController::class,
    'action' => 'main',
  ],

  '~^/about$~' => [
    'controller' => MainController::class,
    'action' => 'aboutMe',
  ],

  '~^/hello/([^/]+)$~' => [
    'controller' => MainController::class,
    'action' => 'sayHello',
  ],

  '~^/bye/([^/]+)$~' => [
    'controller' => MainController::class,
    'action' => 'sayBye',
  ],
];