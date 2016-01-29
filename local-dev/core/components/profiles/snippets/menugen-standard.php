<?php
/*
**
** This is the standard Menu generator snippet
** this is defined by the Menu->generator property
**
** $menuid as parameter
**
** returns the compiled menu data
**
** requires getResources and Wayfinder snippets
*/

$getresourcessnippet = isset($getresourcessnippet) ? $getresourcessnippet : 'getResources';
$wayfindersnippet = isset($wayfindersnippet) ? $wayfindersnippet : 'Wayfinder';


$package_path = $modx->getOption('core_path').'components/profiles/model/';

if(!$modx->addPackage('profiles', $package_path)) {
    return 'Konnte Package nicht laden';
}

$menuentries = $modx->getIterator('MenuEntry', array('menu' => $menuid));

$output = '';
$resids = array();
$startId = $modx->getOption('site_start');

// collect ressource ids
foreach ($menuentries as $menuentry) {
	if($menuentry->get('type') == 'resource-rule') {
		
		// collect data for getresources call
		$entry = json_decode($menuentry->get('entry'), true);

		// violate a bit the structure: TODO, solve that with a more flexible menu
		if(!$entry['startid']) {
			$entry['startid'] = $startId;
		} else {
			$startId = $entry['startid'];
		}

		$entry['tpl'] = '@INLINE [[+id]],';
		$entry['tplLast'] = '@INLINE [[+id]]';

		$iddata = $modx->runSnippet(
				$getresourcessnippet, 
				$entry);

		$resids = array_merge($resids, explode(',',$iddata));

		$iddata = '';
	}
}

// now we have the matching ids collected inside resids array

// get all parent ids

$parents = array();
foreach ($resids as $resid) {
	$parents = array_merge($parents, $modx->getParentIds($resid));
}

// remove duplicates
$parents = array_unique($parents);
$allids = array_merge($parents, $resids);

//now we have all required res ids for Wayfinder Call inside $allids
$props = array();

$props['includeDocs'] = implode(',', $allids);
$props['outerTpl'] = $outerTpl;
$props['innerRowTpl'] = $innerRowTpl;
$props['innerTpl'] = $innerTpl;
$props['parentRowTpl'] = $parentRowTpl;
$props['rowTpl'] = $rowTpl;
$props['startId'] = $startId;

// building menu structure
$output = $modx->runSnippet(
				$wayfindersnippet, 
				$props);

//return print_r($props, true);

return $output;