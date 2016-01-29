<?php
if($modx->context->get('key') != "mgr"){
    //grab the current domain from the http_host option
    switch ($modx->getOption('http_host')) {
        case 'www.meteo-shv.ch':
            //switch the context
            $modx->switchContext('web');
            //set the cultureKey
            $modx->setOption('cultureKey', 'de');
            break;

        case 'www.meteo-fsvl.ch':
            $modx->switchContext('fr');
            $modx->setOption('cultureKey', 'fr');
            break;

        case 'meteo-shv.ch':
            //switch the context
            $modx->switchContext('web');
            //set the cultureKey
            $modx->setOption('cultureKey', 'de');
            break;

        case 'meteo-fsvl.ch':
            $modx->switchContext('fr');
            $modx->setOption('cultureKey', 'fr');
            break;

        default:
            // Set the default language/context here
            $modx->switchContext('web');
            $modx->setOption('cultureKey', 'de');
            break;
    }
}
