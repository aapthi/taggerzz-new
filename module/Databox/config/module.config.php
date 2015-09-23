<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Databox\Controller\Databox' => 'Databox\Controller\DataboxController',
        ),
    ),
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'databox' => array(
                'type' => 'Literal',
                'priority' => 1000,
                'options' => array(
                    'route' => '/databox',
                    'defaults' => array(
                        'controller' => 'Databox\Controller\Databox',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'category-choice' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/category-choice',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'categoryChoice',
                            ),
                        ),
                    ),
                    'predefined-both' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/predefined-both',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'predefinedBoth',
                            ),
                        ),
                    ),
                    'userdefined-both' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/userdefined-both',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'userdefinedBoth',
                            ),
                        ),
                    ),
                    'userdefined-bookmarks' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/userdefined-bookmarks',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'userdefinedBookmarks',
                            ),
                        ),
                    ),
                    'check-databoxes-count' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/check-databoxes-count',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'checkDataboxesCount',
                            ),
                        ),
                    ),
                    'highlights-both' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/highlights-both',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'highlightsBoth',
                            ),
                        ),
                    ),
                    'mail-access-details' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/mail-access-details',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'mailAccessingDetails',
                            ),
                        ),
                    ),
                    'enlist-highlights' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route' => '/enlist-highlights[/:id]',
							'constraints' => array(
							   'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_~-]*',
							),
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'enlistHighlights',
                            ),
                        ),
                    ),
                    'highlights-search-ajax' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/highlights-search-ajax',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'highlightsSearchAjax',
                            ),
                        ),
                    ),
                    'vote-on-highlight' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/vote-on-highlight',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'voteOnHighlight',
                            ),
                        ),
                    ),
                    'delete-highlight' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/delete-highlight',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'deleteHighlight',
                            ),
                        ),
                    ),
                    'edit-highlight' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/edit-highlight',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'editHighlight',
                            ),
                        ),
                    ),
                    'edit-highlight-ascending' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/edit-highlight-ascending',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'editHighlightAscending',
                            ),
                        ),
                    ),
                    'vote-on-relevance' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/vote-on-relevance',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'voteOnRelevance',
                            ),
                        ),
                    ),
                    'vote-on-worth' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/vote-on-worth',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'voteOnWorth',
                            ),
                        ),
                    ),
                    'check-attributes' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/check-attributes',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'checkUrlAttributes',
                            ),
                        ),
                    ),
					'file-xlsx-uploaded' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/file-xlsx-uploaded',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'fileUploaded',
                            ),
                        ),
                    ),
                    'fetch-uploadedurls' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/fetch-uploadedurls',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'fetchUploadedUrls',
                            ),
                        ),
                    ),
                    'fetch-catimage' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/fetch-catimage',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'fetchCatImage',
                            ),
                        ),
                    ),
                    'delete-url' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/delete-url',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'deleteRemovedUrl',
                            ),
                        ),
                    ),
                    'unique-hashtag' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/unique-hashtag',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'uniquePublicHashtag',
                            ),
                        ),
                    ),
					'unique-pvtcode' => array(
						'type' => 'Literal',
						'options' => array(
							'route' => '/unique-pvtcode',
							'defaults' => array(
								'controller' => 'Databox\Controller\Databox',
								'action'     => 'uniquePrivateCode',
							),
						),
					),
                    'add-category' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/add-category',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'addCategory',
                            ),
                        ),
                    ),
                    'add-databox' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/add-databox',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'addDatabox',
                            ),
                        ),
                    ),
                    'display-grid' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/display-grid',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'displayGrid',
                            ),
                        ),
                    ),
                    'publish-grid' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/publish-grid',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'publishGrid',
                            ),
                        ),
                    ),
                    'view-grid' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/view-grid',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'viewGrid',
                            ),
                        ),
                    ),
                    'display-ascending' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/display-ascending',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'displayAscending',
                            ),
                        ),
                    ),
                    'post-vertical' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route' => '/post-vertical[/:id]',
							'constraints' => array(
							   'id' => '[%&;a-zA-Z0-9][%&+;a-zA-Z0-9_~-]*',
							),
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'viewVertical',
                            ),
                        ),
                    ),
					'pre-vertical' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route' => '/pre-vertical[/:id]',
							'constraints' => array(
							   'id' => '[%&;a-zA-Z0-9][%&+;a-zA-Z0-9_~-]*',
							),
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'viewVertical',
                            ),
                        ),
                    ),
					 'post-horizontal' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route' => '/post-horizontal[/:id]',
							'constraints' => array(
							   'id' => '[%&;a-zA-Z0-9][%&+;a-zA-Z0-9_~-]*',
							),
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'viewHorizontal',
                            ),
                        ),
                    ),
					'pre-horizontal' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route' => '/pre-horizontal[/:id]',
							'constraints' => array(
							   'id' => '[%&;a-zA-Z0-9][%&+;a-zA-Z0-9_~-]*',
							),
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'viewHorizontal',
                            ),
                        ),
                    ),
					 'view-vertical' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route' => '/view-vertical[/:id]',
							'constraints' => array(
							   'id' => '[%&;a-zA-Z0-9][%&+;a-zA-Z0-9_~-]*',
							),
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'viewVertical',
                            ),
                        ),
                    ),
					'view-horizontal' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route' => '/view-horizontal[/:id]',
							'constraints' => array(
							   'id' => '[%&;a-zA-Z0-9][%&+;a-zA-Z0-9_~-]*',
							),
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'viewHorizontal',
                            ),
                        ),
                    ),
                    'highlight-grid' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/highlight-grid',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'highlightGrid',
                            ),
                        ),
                    ),
                    'highlight-ascending' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/highlight-ascending',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'highlightAscending',
                            ),
                        ),
                    ),
                    'public-boxes-ajax' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/public-boxes-ajax',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'publicBoxesAjax',
                            ),
                        ),
                    ),
                    'highlight-boxes-ajax' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/highlight-boxes-ajax',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'highlightBoxesAjax',
                            ),
                        ),
                    ),
                    'public-search-ajax' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/public-search-ajax',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'publicSearchAjax',
                            ),
                        ),
                    ),
                    'private-search-ajax' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/private-search-ajax',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'privateSearchAjax',
                            ),
                        ),
                    ),
					'image-crop' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/image-crop',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'imageCrop',
                            ),
                        ),
                    ),
					'search-high-hash-names' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/search-high-hash-names',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'searchHighHashNames',
                            ),
                        ),
                    ),
					'update-cat-desc' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/update-cat-desc',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'updateCatDesc',
                            ),
                        ),
                    ),
                    'createhashtag' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route' => '/createhashtag[/:id/:boxid]',
							'constraints' => array(
							   'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_~-]*',
							),
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'createhashtag',
                            ),
                        ),
                    ),
                    'bookmarks' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route' => '/bookmarks[/:id/:boxid]',
							'constraints' => array(
							   'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_~-]*',
							),
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'bookmarks',
                            ),
                        ),
                    ),
                    'createhighlights' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route' => '/createhighlights[/:boxid]',
							'constraints' => array(
							   'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_~-]*',
							),
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'createhighlights',
                            ),
                        ),
                    ),
					'selectdatabox' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/selectdatabox',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'selectdatabox',
                            ),
                        ),
                    ),
                    'privatedatabox' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route' => '/privatedatabox[/:boxid]',
							'constraints' => array(
							   'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_~-]*',
							),
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'privatedatabox',
                            ),
                        ),
                    ),
					 'update-views-count' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/update-views-count',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'updateViewsCount',
                            ),
                        ),
                    ),
					 'my-collected-links' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/my-collected-links',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'myCollectedLinks',
                            ),
                        ),
                    ),
					 'set-tz-box-id' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/set-tz-box-id',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'setTzBoxId',
                            ),
                        ),
                    ),
					 'insert-comment' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/insert-comment',
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'insertComment',
                            ),
                        ),
                    ),
                    'publicdatabox' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route' => '/publicdatabox[/:boxid]',
							'constraints' => array(
							   'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_~-]*',
							),
                            'defaults' => array(
                                'controller' => 'Databox\Controller\Databox',
                                'action'     => 'publicdatabox',
                            ),
                        ),
                    ),
                ),
            ),
        ),
	),     
    'view_manager' => array(
        'template_path_stack' => array(
            'databox' => __DIR__ . '/../view',
        ),
		'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
	
);
?>