<?php
/*
**
** checks if the setting is selected
**
** $setting, $value and $type as parameter
** $cursettings
**
** returns the string to select the form input
**
*/

$output = "";

$allsettings = $modx->getPlaceholder('profilesettings');

if(strlen($allsettings) == 0) {
    $allsettings = $modx->getOption('stdprofilesettings');
}

$selected = false;

$preparedsettings = explode(',', $allsettings);

$settings = array();

foreach ($preparedsettings as $preparedsetting) {
    $cursetting = explode('=', $preparedsetting);
    $settings[$cursetting[0]] = $cursetting[1];
}

switch ($setting) {
    case 'easymode':
        $selected = ($settings[$setting] == $value);
        break;

    case 'period':
        $flag = (int)$settings[$setting];
        $value = (int)$value;
        $selected = ($flag & $value);
        break;
}

if($selected) {
    switch ($type) {
        case 'checkbox':
            $output = 'checked="checked"';
            break;

        case 'select':
            $output = 'selected="selected"';
            break;

        default:
            $output = $selected;
    }
}

return $output;