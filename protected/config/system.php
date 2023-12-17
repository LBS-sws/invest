<?php

return array(

    'drs'=>array(
        'webroot'=>'http://192.168.0.5/swoper',
        'name'=>'Daily Report',
        'icon'=>'fa fa-pencil-square-o',
    ),
    'acct'=>array(
        'webroot'=>'http://192.168.0.5/acct',
        'name'=>'Accounting',
        'icon'=>'fa fa-money',
    ),
    'ops'=>array(
        'webroot'=>'http://192.168.0.5/operation',
        'name'=>'Operation',
        'icon'=>'fa fa-gears',
    ),
    'hr'=>array(
        'webroot'=>'http://192.168.0.5/hr',
        'name'=>'Personnel',
        'icon'=>'fa fa-users',
    ),
    'sp'=>array(
        'webroot'=>'http://192.168.0.5/integral',
        'name'=>'Integral',
        'icon'=>'fa fa-cubes',
    ),
    'ch'=>array(
        'webroot'=>'http://192.168.0.5/charity',
        'name'=>'Charity',
        'icon'=>'fa fa-cubes',
    ),
    'quiz'=>array(
        'webroot'=>'http://192.168.0.5/examina',
        'name'=>'Examina',
        'icon'=>'fa fa-leaf',
    ),
    'sev'=>array(
        'webroot'=>'http://192.168.0.5/several',
        'name'=>'Several',
        'icon'=>'fa fa-leaf',
    ),
    'invest'=>array(
        'webroot'=>'http://192.168.0.5/invest',
        'name'=>'Investment',
        'icon'=>'fa fa-balance-scale',
    ),
    'sal'=>array(
        'webroot'=>'http://192.168.0.5/sales',
        'name'=>'Sales',
        'icon'=>'fa fa-suitcase',
    ),
    'nu'=>array(
        'webroot'=>'https://dms.lbsapps.cn/nu',
        'name'=>'New United',
        'icon'=>'fa fa-suitcase',
        'param'=>'/admin',
        'script'=>'goNewUnited',
    ),
    'onlib'=>array(
        'webroot'=>'https://onlib.lbsapps.com/seeddms',
        'script'=>'remoteLoginOnlib',
        'name'=>'Online Library',
        'icon'=>'fa fa-book',
        'external'=>array(
            'layout'=>'onlib',
            'update'=>'saveOnlib',		//function defined in UserFormEx.php
            'fields'=>'fieldsOnlib',
        ),
    ),
    /*
        'apps'=>array(
            'webroot'=>'https://app.lbsgroup.com.tw/web',
            'script'=>'remoteLoginTwApp',
            'name'=>'Apps System',
            'icon'=>'fa fa-rocket',
            'external'=>true,
        ),
    */
);

?>
