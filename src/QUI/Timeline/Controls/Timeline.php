<?php

/**
 * This file contains QUI\Timeline\Controls\Timeline
 */

namespace QUI\Timeline\Controls;

use QUI;
use QUI\Projects\Site\Utils;

/**
 * Class Timeline
 *
 * @package quiqqer/timeline
 */
class Timeline extends QUI\Control
{
    /**
     * constructor
     *
     * @param array $attributes
     */
    public function __construct($attributes = array())
    {
        // default options
        $this->setAttributes(array(
            'class'           => 'timeline',
            'order'           => 'c_date ASC',
            'parentInputList' => false, //todo später für brick
            'showLinks'       => true,
            'display'         => 'VerticalBothSides',
            // Custom children template (path to html file); overwrites "display"
            'displayTemplate' => false,
            // Custom children template css (path to css file); overwrites "display"
            'displayCss'      => false,
            'nodeName'        => 'section',
        ));

        parent::__construct($attributes);
    }

    /**
     * Return the inner body of the element
     * Can be overwritten
     *
     * @return String
     */
    public function getBody()
    {
        $Engine  = QUI::getTemplateManager()->getEngine();
        $Site    = $this->getSite();
        $Project = $this->getProject();

        if (!$Site && !$this->getAttribute('parentInputList')) {
            return '';
        }

        $parents = $this->getAttribute('parentInputList');

        if (!$parents) {
            $parents = $Site->getId();
        }

        // only active sites
        $where['active'] = 1;

        if ($this->getAttribute('parentInputList')) {
            // for bricks
            $children = Utils::getSitesByInputList($Project, $parents, array(
                'where' => $where,
                'order' => $this->getAttribute('order')
            ));
        } else {
            // for site types
            $children = $Site->getChildren(array(
                'where' => $where,
            ));
        }

        $Engine->assign(array(
            'this'      => $this,
            'Site'      => $Site,
            'Project'   => $this->getProject(),
            'children'  => $children,
            'showLinks' => $this->getAttribute('showLinks')
        ));

        // load custom template (if set)
        if ($this->getAttribute('displayTemplate')
            && file_exists($this->getAttribute('displayTemplate'))
        ) {
            if ($this->getAttribute('displayCss')
                && file_exists($this->getAttribute('displayCss'))
            ) {
                $this->addCSSFile($this->getAttribute('displayCss'));
            }

            return $Engine->fetch($this->getAttribute('displayTemplate'));
        }


        // template
        $css      = dirname(__FILE__) . $this->getAttribute('display') . '.css';
        $template = dirname(__FILE__) . $this->getAttribute('display') . '.template';

        $this->addCSSFile($css);

        return $Engine->fetch($template);
    }


    /**
     * @return mixed|QUI\Projects\Site
     */
    protected function getSite()
    {
        if ($this->getAttribute('Site')) {
            return $this->getAttribute('Site');
        }

        $Site = QUI::getRewrite()->getSite();

        $this->setAttribute('Site', $Site);

        return $Site;
    }
}
