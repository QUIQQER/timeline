<?php

/**
 * Site list
 */

$Timeline = new QUI\Timeline\Controls\Timeline(array(
    'Site'           => $Site,
    'showLinks'       => $Site->getAttribute('quiqqer.timeline.showLinks'),
    'itemtype'       => 'http://schema.org/ItemList',
    'child-itemtype' => 'http://schema.org/ListItem',
    'display'        => $Site->getAttribute('quiqqer.timeline.display')
));

$Engine->assign(array(
    'Timeline' => $Timeline
));
