<?php

app('router')->setCompiledRoutes(
    array (
  'compiled' => 
  array (
    0 => false,
    1 => 
    array (
      '/api/v1/auth/register' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::c3Pg6e42CdL7NoAb',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/auth/login' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::gKEkUyPZfzviXEmY',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/auth/logout' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::WNKpH5S50Ox8Eo5X',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/auth/refresh' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::BOLlloALCF5pUS1K',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/auth/me' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::xnGX5Plg4LefXOqM',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/patient/profile' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::3iD4CKNOBjVoc81c',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::PmqecpqI1ASV6hLq',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/patient/appointments' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::0YOuQjl2x6iTP4gQ',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::PjxudKXBmLczI69v',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/patient/raqis' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::An97mVl8VaVxulkf',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/patient/reviews' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::y9voky8lZZP7kstg',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/patient/notifications' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::R0TCOrm8QGbAy7DL',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/raqi/profile' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::2c9qFZz64Ng6w6NA',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::S4J4hLvYud6n6vkv',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/raqi/availability' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'availability.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'availability.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/raqi/appointments' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::fny7kPxQCu9jTq2Y',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/raqi/notifications' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::1s9Z0nssbYGOakHC',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/admin/users' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::gyYvBHeEWtOKJT3m',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/admin/raqis/pending' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::w3WxODxtqs1eAaal',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/admin/raqis/approved' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::52ff2iI8DSThssO5',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/admin/appointments' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::gFp6sgLmkwm9sjLv',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/admin/stats' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::8X172hRYikILcBPX',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/up' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::rbXdbwtVXqiAyT4a',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
    ),
    2 => 
    array (
      0 => '{^(?|/api/v1/(?|patient/(?|appointments/([^/]++)(?|(*:53))|raqis/([^/]++)(?|(*:78)|/available\\-slots(*:102))|notifications/([^/]++)/read(*:138))|raqi/(?|a(?|vailability/([^/]++)(?|(*:182))|ppointments/([^/]++)/(?|accept(*:221)|decline(*:236)|complete(*:252)|invite(*:266)|participants(*:286)|notes(?|(*:302))|followup(*:319)))|notifications/([^/]++)/read(*:356))|admin/(?|users/([^/]++)(?|(*:391)|/activate(*:408))|raqis/([^/]++)/(?|approve(*:442)|suspend(*:457))|appointments/([^/]++)(*:487))|invites/([^/]++)/(?|accept(*:522)|decline(*:537)))|/(.*)(*:552)|/broadcasting/auth(*:578)|/storage/(.*)(?|(*:602)))/?$}sDu',
    ),
    3 => 
    array (
      53 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::WhUf4SrLlhOfJY9f',
          ),
          1 => 
          array (
            0 => 'appointment',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::IMQIBJauj5OmasGL',
          ),
          1 => 
          array (
            0 => 'appointment',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      78 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::TQ14RZX8DxSQLdAP',
          ),
          1 => 
          array (
            0 => 'raqi',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      102 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::H217xx80abcAqZw0',
          ),
          1 => 
          array (
            0 => 'raqi',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      138 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::M7Bgnfy2I4vEFLyW',
          ),
          1 => 
          array (
            0 => 'notification',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      182 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'availability.show',
          ),
          1 => 
          array (
            0 => 'availability',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'availability.update',
          ),
          1 => 
          array (
            0 => 'availability',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        2 => 
        array (
          0 => 
          array (
            '_route' => 'availability.destroy',
          ),
          1 => 
          array (
            0 => 'availability',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      221 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::FZQIjc8nPkemxN0B',
          ),
          1 => 
          array (
            0 => 'appointment',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      236 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::qRv4nPVAGFcTDcCY',
          ),
          1 => 
          array (
            0 => 'appointment',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      252 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::2ayMlAEPTQQinbp6',
          ),
          1 => 
          array (
            0 => 'appointment',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      266 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::pkwcoy8qLwT2SJhA',
          ),
          1 => 
          array (
            0 => 'appointment',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      286 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::mZvZyqA729HSEgzb',
          ),
          1 => 
          array (
            0 => 'appointment',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      302 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::LndMUrA6lrSzmq2n',
          ),
          1 => 
          array (
            0 => 'appointment',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::LmW3M4GTpr8gUzSQ',
          ),
          1 => 
          array (
            0 => 'appointment',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      319 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::TsyYzOd4BZnN5IdX',
          ),
          1 => 
          array (
            0 => 'appointment',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      356 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::FWZNO1pJc9f9TtRL',
          ),
          1 => 
          array (
            0 => 'notification',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      391 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::jUrUuZxNotKDrb6g',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      408 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::JqaY3KIqX3CC5QN7',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      442 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::tULlmeY3465YTUrc',
          ),
          1 => 
          array (
            0 => 'raqi',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      457 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::8Kk6jxq5mpcHJl6o',
          ),
          1 => 
          array (
            0 => 'raqi',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      487 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::eRZkSYbAPWLRGunv',
          ),
          1 => 
          array (
            0 => 'appointment',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      522 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::3H6r1JXDmzMsIVnk',
          ),
          1 => 
          array (
            0 => 'token',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      537 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::HqWMNOTaoYwZHoyJ',
          ),
          1 => 
          array (
            0 => 'token',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      552 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::MtIwpVLOGr6s3a4y',
          ),
          1 => 
          array (
            0 => 'any',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      578 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::XZjzfp9WT9Z6MvwN',
          ),
          1 => 
          array (
          ),
          2 => 
          array (
            'GET' => 0,
            'POST' => 1,
            'HEAD' => 2,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      602 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'storage.local',
          ),
          1 => 
          array (
            0 => 'path',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'storage.local.upload',
          ),
          1 => 
          array (
            0 => 'path',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        2 => 
        array (
          0 => NULL,
          1 => NULL,
          2 => NULL,
          3 => NULL,
          4 => false,
          5 => false,
          6 => 0,
        ),
      ),
    ),
    4 => NULL,
  ),
  'attributes' => 
  array (
    'generated::c3Pg6e42CdL7NoAb' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/auth/register',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AuthController@register',
        'controller' => 'App\\Http\\Controllers\\Api\\AuthController@register',
        'namespace' => NULL,
        'prefix' => 'api/v1/auth',
        'where' => 
        array (
        ),
        'as' => 'generated::c3Pg6e42CdL7NoAb',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::gKEkUyPZfzviXEmY' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/auth/login',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AuthController@login',
        'controller' => 'App\\Http\\Controllers\\Api\\AuthController@login',
        'namespace' => NULL,
        'prefix' => 'api/v1/auth',
        'where' => 
        array (
        ),
        'as' => 'generated::gKEkUyPZfzviXEmY',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::WNKpH5S50Ox8Eo5X' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/auth/logout',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AuthController@logout',
        'controller' => 'App\\Http\\Controllers\\Api\\AuthController@logout',
        'namespace' => NULL,
        'prefix' => 'api/v1/auth',
        'where' => 
        array (
        ),
        'as' => 'generated::WNKpH5S50Ox8Eo5X',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::BOLlloALCF5pUS1K' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/auth/refresh',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AuthController@refresh',
        'controller' => 'App\\Http\\Controllers\\Api\\AuthController@refresh',
        'namespace' => NULL,
        'prefix' => 'api/v1/auth',
        'where' => 
        array (
        ),
        'as' => 'generated::BOLlloALCF5pUS1K',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::xnGX5Plg4LefXOqM' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/auth/me',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AuthController@me',
        'controller' => 'App\\Http\\Controllers\\Api\\AuthController@me',
        'namespace' => NULL,
        'prefix' => 'api/v1/auth',
        'where' => 
        array (
        ),
        'as' => 'generated::xnGX5Plg4LefXOqM',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::3iD4CKNOBjVoc81c' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/patient/profile',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:patient',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PatientProfileController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\PatientProfileController@show',
        'namespace' => NULL,
        'prefix' => 'api/v1/patient',
        'where' => 
        array (
        ),
        'as' => 'generated::3iD4CKNOBjVoc81c',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::PmqecpqI1ASV6hLq' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/v1/patient/profile',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:patient',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PatientProfileController@update',
        'controller' => 'App\\Http\\Controllers\\Api\\PatientProfileController@update',
        'namespace' => NULL,
        'prefix' => 'api/v1/patient',
        'where' => 
        array (
        ),
        'as' => 'generated::PmqecpqI1ASV6hLq',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::0YOuQjl2x6iTP4gQ' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/patient/appointments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:patient',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppointmentController@patientIndex',
        'controller' => 'App\\Http\\Controllers\\Api\\AppointmentController@patientIndex',
        'namespace' => NULL,
        'prefix' => 'api/v1/patient',
        'where' => 
        array (
        ),
        'as' => 'generated::0YOuQjl2x6iTP4gQ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::PjxudKXBmLczI69v' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/patient/appointments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:patient',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppointmentController@store',
        'controller' => 'App\\Http\\Controllers\\Api\\AppointmentController@store',
        'namespace' => NULL,
        'prefix' => 'api/v1/patient',
        'where' => 
        array (
        ),
        'as' => 'generated::PjxudKXBmLczI69v',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::WhUf4SrLlhOfJY9f' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/patient/appointments/{appointment}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:patient',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppointmentController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\AppointmentController@show',
        'namespace' => NULL,
        'prefix' => 'api/v1/patient',
        'where' => 
        array (
        ),
        'as' => 'generated::WhUf4SrLlhOfJY9f',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::IMQIBJauj5OmasGL' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/v1/patient/appointments/{appointment}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:patient',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppointmentController@cancel',
        'controller' => 'App\\Http\\Controllers\\Api\\AppointmentController@cancel',
        'namespace' => NULL,
        'prefix' => 'api/v1/patient',
        'where' => 
        array (
        ),
        'as' => 'generated::IMQIBJauj5OmasGL',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::An97mVl8VaVxulkf' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/patient/raqis',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:patient',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\RaqiController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\RaqiController@index',
        'namespace' => NULL,
        'prefix' => 'api/v1/patient',
        'where' => 
        array (
        ),
        'as' => 'generated::An97mVl8VaVxulkf',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::TQ14RZX8DxSQLdAP' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/patient/raqis/{raqi}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:patient',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\RaqiController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\RaqiController@show',
        'namespace' => NULL,
        'prefix' => 'api/v1/patient',
        'where' => 
        array (
        ),
        'as' => 'generated::TQ14RZX8DxSQLdAP',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::H217xx80abcAqZw0' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/patient/raqis/{raqi}/available-slots',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:patient',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\RaqiController@availableSlots',
        'controller' => 'App\\Http\\Controllers\\Api\\RaqiController@availableSlots',
        'namespace' => NULL,
        'prefix' => 'api/v1/patient',
        'where' => 
        array (
        ),
        'as' => 'generated::H217xx80abcAqZw0',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::y9voky8lZZP7kstg' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/patient/reviews',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:patient',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ReviewController@store',
        'controller' => 'App\\Http\\Controllers\\Api\\ReviewController@store',
        'namespace' => NULL,
        'prefix' => 'api/v1/patient',
        'where' => 
        array (
        ),
        'as' => 'generated::y9voky8lZZP7kstg',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::R0TCOrm8QGbAy7DL' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/patient/notifications',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:patient',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\NotificationController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\NotificationController@index',
        'namespace' => NULL,
        'prefix' => 'api/v1/patient',
        'where' => 
        array (
        ),
        'as' => 'generated::R0TCOrm8QGbAy7DL',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::M7Bgnfy2I4vEFLyW' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/v1/patient/notifications/{notification}/read',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:patient',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\NotificationController@markRead',
        'controller' => 'App\\Http\\Controllers\\Api\\NotificationController@markRead',
        'namespace' => NULL,
        'prefix' => 'api/v1/patient',
        'where' => 
        array (
        ),
        'as' => 'generated::M7Bgnfy2I4vEFLyW',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::2c9qFZz64Ng6w6NA' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/raqi/profile',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:raqi',
          3 => 'raqi.approved',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\RaqiProfileController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\RaqiProfileController@show',
        'namespace' => NULL,
        'prefix' => 'api/v1/raqi',
        'where' => 
        array (
        ),
        'as' => 'generated::2c9qFZz64Ng6w6NA',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::S4J4hLvYud6n6vkv' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/v1/raqi/profile',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:raqi',
          3 => 'raqi.approved',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\RaqiProfileController@update',
        'controller' => 'App\\Http\\Controllers\\Api\\RaqiProfileController@update',
        'namespace' => NULL,
        'prefix' => 'api/v1/raqi',
        'where' => 
        array (
        ),
        'as' => 'generated::S4J4hLvYud6n6vkv',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'availability.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/raqi/availability',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:raqi',
          3 => 'raqi.approved',
        ),
        'as' => 'availability.index',
        'uses' => 'App\\Http\\Controllers\\Api\\AvailabilityController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\AvailabilityController@index',
        'namespace' => NULL,
        'prefix' => 'api/v1/raqi',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'availability.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/raqi/availability',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:raqi',
          3 => 'raqi.approved',
        ),
        'as' => 'availability.store',
        'uses' => 'App\\Http\\Controllers\\Api\\AvailabilityController@store',
        'controller' => 'App\\Http\\Controllers\\Api\\AvailabilityController@store',
        'namespace' => NULL,
        'prefix' => 'api/v1/raqi',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'availability.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/raqi/availability/{availability}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:raqi',
          3 => 'raqi.approved',
        ),
        'as' => 'availability.show',
        'uses' => 'App\\Http\\Controllers\\Api\\AvailabilityController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\AvailabilityController@show',
        'namespace' => NULL,
        'prefix' => 'api/v1/raqi',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'availability.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'api/v1/raqi/availability/{availability}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:raqi',
          3 => 'raqi.approved',
        ),
        'as' => 'availability.update',
        'uses' => 'App\\Http\\Controllers\\Api\\AvailabilityController@update',
        'controller' => 'App\\Http\\Controllers\\Api\\AvailabilityController@update',
        'namespace' => NULL,
        'prefix' => 'api/v1/raqi',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'availability.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/v1/raqi/availability/{availability}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:raqi',
          3 => 'raqi.approved',
        ),
        'as' => 'availability.destroy',
        'uses' => 'App\\Http\\Controllers\\Api\\AvailabilityController@destroy',
        'controller' => 'App\\Http\\Controllers\\Api\\AvailabilityController@destroy',
        'namespace' => NULL,
        'prefix' => 'api/v1/raqi',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::fny7kPxQCu9jTq2Y' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/raqi/appointments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:raqi',
          3 => 'raqi.approved',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppointmentController@raqiIndex',
        'controller' => 'App\\Http\\Controllers\\Api\\AppointmentController@raqiIndex',
        'namespace' => NULL,
        'prefix' => 'api/v1/raqi',
        'where' => 
        array (
        ),
        'as' => 'generated::fny7kPxQCu9jTq2Y',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::FZQIjc8nPkemxN0B' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/v1/raqi/appointments/{appointment}/accept',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:raqi',
          3 => 'raqi.approved',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppointmentController@accept',
        'controller' => 'App\\Http\\Controllers\\Api\\AppointmentController@accept',
        'namespace' => NULL,
        'prefix' => 'api/v1/raqi',
        'where' => 
        array (
        ),
        'as' => 'generated::FZQIjc8nPkemxN0B',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::qRv4nPVAGFcTDcCY' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/v1/raqi/appointments/{appointment}/decline',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:raqi',
          3 => 'raqi.approved',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppointmentController@decline',
        'controller' => 'App\\Http\\Controllers\\Api\\AppointmentController@decline',
        'namespace' => NULL,
        'prefix' => 'api/v1/raqi',
        'where' => 
        array (
        ),
        'as' => 'generated::qRv4nPVAGFcTDcCY',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::2ayMlAEPTQQinbp6' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/v1/raqi/appointments/{appointment}/complete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:raqi',
          3 => 'raqi.approved',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppointmentController@complete',
        'controller' => 'App\\Http\\Controllers\\Api\\AppointmentController@complete',
        'namespace' => NULL,
        'prefix' => 'api/v1/raqi',
        'where' => 
        array (
        ),
        'as' => 'generated::2ayMlAEPTQQinbp6',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::pkwcoy8qLwT2SJhA' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/raqi/appointments/{appointment}/invite',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:raqi',
          3 => 'raqi.approved',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SessionParticipantController@invite',
        'controller' => 'App\\Http\\Controllers\\Api\\SessionParticipantController@invite',
        'namespace' => NULL,
        'prefix' => 'api/v1/raqi',
        'where' => 
        array (
        ),
        'as' => 'generated::pkwcoy8qLwT2SJhA',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::mZvZyqA729HSEgzb' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/raqi/appointments/{appointment}/participants',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:raqi',
          3 => 'raqi.approved',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SessionParticipantController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\SessionParticipantController@index',
        'namespace' => NULL,
        'prefix' => 'api/v1/raqi',
        'where' => 
        array (
        ),
        'as' => 'generated::mZvZyqA729HSEgzb',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::LndMUrA6lrSzmq2n' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/raqi/appointments/{appointment}/notes',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:raqi',
          3 => 'raqi.approved',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SessionNoteController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\SessionNoteController@index',
        'namespace' => NULL,
        'prefix' => 'api/v1/raqi',
        'where' => 
        array (
        ),
        'as' => 'generated::LndMUrA6lrSzmq2n',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::LmW3M4GTpr8gUzSQ' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/raqi/appointments/{appointment}/notes',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:raqi',
          3 => 'raqi.approved',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SessionNoteController@store',
        'controller' => 'App\\Http\\Controllers\\Api\\SessionNoteController@store',
        'namespace' => NULL,
        'prefix' => 'api/v1/raqi',
        'where' => 
        array (
        ),
        'as' => 'generated::LmW3M4GTpr8gUzSQ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::TsyYzOd4BZnN5IdX' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/raqi/appointments/{appointment}/followup',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:raqi',
          3 => 'raqi.approved',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\FollowUpController@store',
        'controller' => 'App\\Http\\Controllers\\Api\\FollowUpController@store',
        'namespace' => NULL,
        'prefix' => 'api/v1/raqi',
        'where' => 
        array (
        ),
        'as' => 'generated::TsyYzOd4BZnN5IdX',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::1s9Z0nssbYGOakHC' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/raqi/notifications',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:raqi',
          3 => 'raqi.approved',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\NotificationController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\NotificationController@index',
        'namespace' => NULL,
        'prefix' => 'api/v1/raqi',
        'where' => 
        array (
        ),
        'as' => 'generated::1s9Z0nssbYGOakHC',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::FWZNO1pJc9f9TtRL' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/v1/raqi/notifications/{notification}/read',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:raqi',
          3 => 'raqi.approved',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\NotificationController@markRead',
        'controller' => 'App\\Http\\Controllers\\Api\\NotificationController@markRead',
        'namespace' => NULL,
        'prefix' => 'api/v1/raqi',
        'where' => 
        array (
        ),
        'as' => 'generated::FWZNO1pJc9f9TtRL',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::gyYvBHeEWtOKJT3m' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/admin/users',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\Admin\\AdminUserController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\Admin\\AdminUserController@index',
        'namespace' => NULL,
        'prefix' => 'api/v1/admin',
        'where' => 
        array (
        ),
        'as' => 'generated::gyYvBHeEWtOKJT3m',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::jUrUuZxNotKDrb6g' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/admin/users/{user}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\Admin\\AdminUserController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\Admin\\AdminUserController@show',
        'namespace' => NULL,
        'prefix' => 'api/v1/admin',
        'where' => 
        array (
        ),
        'as' => 'generated::jUrUuZxNotKDrb6g',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::JqaY3KIqX3CC5QN7' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/v1/admin/users/{user}/activate',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\Admin\\AdminUserController@activate',
        'controller' => 'App\\Http\\Controllers\\Api\\Admin\\AdminUserController@activate',
        'namespace' => NULL,
        'prefix' => 'api/v1/admin',
        'where' => 
        array (
        ),
        'as' => 'generated::JqaY3KIqX3CC5QN7',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::w3WxODxtqs1eAaal' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/admin/raqis/pending',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\Admin\\AdminRaqiController@pending',
        'controller' => 'App\\Http\\Controllers\\Api\\Admin\\AdminRaqiController@pending',
        'namespace' => NULL,
        'prefix' => 'api/v1/admin',
        'where' => 
        array (
        ),
        'as' => 'generated::w3WxODxtqs1eAaal',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::52ff2iI8DSThssO5' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/admin/raqis/approved',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\Admin\\AdminRaqiController@approved',
        'controller' => 'App\\Http\\Controllers\\Api\\Admin\\AdminRaqiController@approved',
        'namespace' => NULL,
        'prefix' => 'api/v1/admin',
        'where' => 
        array (
        ),
        'as' => 'generated::52ff2iI8DSThssO5',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::tULlmeY3465YTUrc' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/v1/admin/raqis/{raqi}/approve',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\Admin\\AdminRaqiController@approve',
        'controller' => 'App\\Http\\Controllers\\Api\\Admin\\AdminRaqiController@approve',
        'namespace' => NULL,
        'prefix' => 'api/v1/admin',
        'where' => 
        array (
        ),
        'as' => 'generated::tULlmeY3465YTUrc',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::8Kk6jxq5mpcHJl6o' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/v1/admin/raqis/{raqi}/suspend',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\Admin\\AdminRaqiController@suspend',
        'controller' => 'App\\Http\\Controllers\\Api\\Admin\\AdminRaqiController@suspend',
        'namespace' => NULL,
        'prefix' => 'api/v1/admin',
        'where' => 
        array (
        ),
        'as' => 'generated::8Kk6jxq5mpcHJl6o',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::gFp6sgLmkwm9sjLv' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/admin/appointments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\Admin\\AdminAppointmentController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\Admin\\AdminAppointmentController@index',
        'namespace' => NULL,
        'prefix' => 'api/v1/admin',
        'where' => 
        array (
        ),
        'as' => 'generated::gFp6sgLmkwm9sjLv',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::eRZkSYbAPWLRGunv' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/v1/admin/appointments/{appointment}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\Admin\\AdminAppointmentController@update',
        'controller' => 'App\\Http\\Controllers\\Api\\Admin\\AdminAppointmentController@update',
        'namespace' => NULL,
        'prefix' => 'api/v1/admin',
        'where' => 
        array (
        ),
        'as' => 'generated::eRZkSYbAPWLRGunv',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::8X172hRYikILcBPX' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/admin/stats',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
          2 => 'role:admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\Admin\\AdminStatsController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\Admin\\AdminStatsController@index',
        'namespace' => NULL,
        'prefix' => 'api/v1/admin',
        'where' => 
        array (
        ),
        'as' => 'generated::8X172hRYikILcBPX',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::3H6r1JXDmzMsIVnk' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/invites/{token}/accept',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\InviteController@accept',
        'controller' => 'App\\Http\\Controllers\\Api\\InviteController@accept',
        'namespace' => NULL,
        'prefix' => 'api/v1',
        'where' => 
        array (
        ),
        'as' => 'generated::3H6r1JXDmzMsIVnk',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::HqWMNOTaoYwZHoyJ' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/invites/{token}/decline',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\InviteController@decline',
        'controller' => 'App\\Http\\Controllers\\Api\\InviteController@decline',
        'namespace' => NULL,
        'prefix' => 'api/v1',
        'where' => 
        array (
        ),
        'as' => 'generated::HqWMNOTaoYwZHoyJ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::rbXdbwtVXqiAyT4a' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'up',
      'action' => 
      array (
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:832:"function () {
                    $exception = null;

                    try {
                        \\Illuminate\\Support\\Facades\\Event::dispatch(new \\Illuminate\\Foundation\\Events\\DiagnosingHealth);
                    } catch (\\Throwable $e) {
                        if (app()->hasDebugModeEnabled()) {
                            throw $e;
                        }

                        report($e);

                        $exception = $e->getMessage();
                    }

                    return response(\\Illuminate\\Support\\Facades\\View::file(\'E:\\\\laravel\\\\rukaiyah\\\\rukaiyah\\\\vendor\\\\laravel\\\\framework\\\\src\\\\Illuminate\\\\Foundation\\\\Configuration\'.\'/../resources/health-up.blade.php\', [
                        \'exception\' => $exception,
                    ]), status: $exception ? 500 : 200);
                }";s:5:"scope";s:54:"Illuminate\\Foundation\\Configuration\\ApplicationBuilder";s:4:"this";N;s:4:"self";s:32:"00000000000009420000000000000000";}}',
        'as' => 'generated::rbXdbwtVXqiAyT4a',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::MtIwpVLOGr6s3a4y' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{any}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:40:"function () {
    return \\view(\'app\');
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000009570000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::MtIwpVLOGr6s3a4y',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'any' => '.*',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::XZjzfp9WT9Z6MvwN' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'POST',
        2 => 'HEAD',
      ),
      'uri' => 'broadcasting/auth',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => '\\Illuminate\\Broadcasting\\BroadcastController@authenticate',
        'controller' => '\\Illuminate\\Broadcasting\\BroadcastController@authenticate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'excluded_middleware' => 
        array (
          0 => 'Illuminate\\Foundation\\Http\\Middleware\\PreventRequestForgery',
        ),
        'as' => 'generated::XZjzfp9WT9Z6MvwN',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'storage.local' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'storage/{path}',
      'action' => 
      array (
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:3:{s:4:"disk";s:5:"local";s:6:"config";a:5:{s:6:"driver";s:5:"local";s:4:"root";s:48:"E:\\laravel\\rukaiyah\\rukaiyah\\storage\\app/private";s:5:"serve";b:1;s:5:"throw";b:0;s:6:"report";b:0;}s:12:"isProduction";b:0;}s:8:"function";s:323:"function (\\Illuminate\\Http\\Request $request, string $path) use ($disk, $config, $isProduction) {
                    return (new \\Illuminate\\Filesystem\\ServeFile(
                        $disk,
                        $config,
                        $isProduction
                    ))($request, $path);
                }";s:5:"scope";s:47:"Illuminate\\Filesystem\\FilesystemServiceProvider";s:4:"this";N;s:4:"self";s:32:"00000000000009910000000000000000";}}',
        'as' => 'storage.local',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'path' => '.*',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'storage.local.upload' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'storage/{path}',
      'action' => 
      array (
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:3:{s:4:"disk";s:5:"local";s:6:"config";a:5:{s:6:"driver";s:5:"local";s:4:"root";s:48:"E:\\laravel\\rukaiyah\\rukaiyah\\storage\\app/private";s:5:"serve";b:1;s:5:"throw";b:0;s:6:"report";b:0;}s:12:"isProduction";b:0;}s:8:"function";s:325:"function (\\Illuminate\\Http\\Request $request, string $path) use ($disk, $config, $isProduction) {
                    return (new \\Illuminate\\Filesystem\\ReceiveFile(
                        $disk,
                        $config,
                        $isProduction
                    ))($request, $path);
                }";s:5:"scope";s:47:"Illuminate\\Filesystem\\FilesystemServiceProvider";s:4:"this";N;s:4:"self";s:32:"00000000000009930000000000000000";}}',
        'as' => 'storage.local.upload',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'path' => '.*',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
  ),
)
);
