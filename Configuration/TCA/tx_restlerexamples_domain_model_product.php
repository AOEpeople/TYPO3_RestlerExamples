<?php

return array(
    'ctrl' => array(
        'title' => 'LLL:EXT:restler_examples/Resources/Private/Language/locallang_db.xml:tx_restlerexamples_domain_model_product',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'enablecolumns' => array(
            'disabled' => 'hidden'
        ),
        'dividers2tabs' => true,
        'searchFields' => 'name,',
        'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('restler_examples') . 'Resources/Public/Icons/TCA/tx_restlerexamples_domain_model_product.gif'
    ),
    'interface' => array(
        'showRecordFieldList' => 'name,description'
    ),
    'types' => array(
        '1' => array(
            'showitem' => 'hidden;;1,name,description,details_page',
        ),
    ),
    'palettes' => array(
        '1' => array('showitem' => ''),
    ),
    'columns' => array(
        'hidden' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
            'config' => array(
                'type' => 'check',
                'default' => '0'
            )
        ),
        'name' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:restler_examples/Resources/Private/Language/locallang_db.xml:tx_restlerexamples_domain_model_product.name',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ),
        ),
        'description' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:restler_examples/Resources/Private/Language/locallang_db.xml:tx_restlerexamples_domain_model_product.description',
            'config' => array (
                'type' => 'text',
                'cols' => '48',
                'rows' => '5',
                'wizards' => array(
                    '_PADDING' => 2,
                    'RTE' => array(
                        'notNewRecords' => 1,
                        'RTEonly' => 1,
                        'type' => 'script',
                        'title' => 'Full screen Rich Text Editing|Formatteret redigering i hele vinduet',
                        'icon' => 'wizard_rte2.gif',
                        'script' => 'wizard_rte.php',
                    ),
                )
            ),
            'defaultExtras' => 'richtext[blockstylelabel|blockstyle|textstylelabel|textstyle|formatblock|bold|italic|underline|subscript|superscript|orderedlist|unorderedlist|link|image|findreplace|chMode|removeformat|undo|redo]:rte_transform[mode=ts_css]'
        ),
        'details_page' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:restler_examples/Resources/Private/Language/locallang_db.xml:tx_restlerexamples_domain_model_product.details_page',
            'config' => array(
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'pages',
                'size' => 1,
                'minitems' => 1,
                'maxitems' => 1,
            ),
        ),
    ),
);
