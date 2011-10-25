<?php

########################################################################
# Extension Manager/Repository config file for ext "fluid".
#
# Auto generated 25-10-2011 11:46
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Fluid Templating Engine',
	'description' => 'Fluid is a next-generation templating engine which makes the life of extension authors a lot easier!',
	'category' => 'fe',
	'author' => 'Sebastian Kurfürst, Bastian Waidelich',
	'author_email' => 'sebastian@typo3.org, bastian@typo3.org',
	'shy' => '',
	'dependencies' => 'extbase',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '1.4.0',
	'constraints' => array(
		'depends' => array(
			'php' => '5.3.0-0.0.0',
			'extbase' => '1.4.0-',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:246:{s:13:"ChangeLog.txt";s:4:"546f";s:16:"ext_autoload.php";s:4:"84d4";s:12:"ext_icon.gif";s:4:"e922";s:17:"ext_localconf.php";s:4:"d809";s:14:"ext_tables.php";s:4:"e8e2";s:24:"ext_typoscript_setup.txt";s:4:"b410";s:21:"Classes/Exception.php";s:4:"7373";s:17:"Classes/Fluid.php";s:4:"ac13";s:49:"Classes/Compatibility/DocbookGeneratorService.php";s:4:"84b6";s:47:"Classes/Compatibility/TemplateParserBuilder.php";s:4:"617b";s:26:"Classes/Core/Exception.php";s:4:"79b3";s:50:"Classes/Core/Compiler/AbstractCompiledTemplate.php";s:4:"2ee9";s:42:"Classes/Core/Compiler/TemplateCompiler.php";s:4:"55db";s:37:"Classes/Core/Parser/Configuration.php";s:4:"ab36";s:33:"Classes/Core/Parser/Exception.php";s:4:"1cbd";s:44:"Classes/Core/Parser/InterceptorInterface.php";s:4:"59dc";s:47:"Classes/Core/Parser/ParsedTemplateInterface.php";s:4:"ecdc";s:36:"Classes/Core/Parser/ParsingState.php";s:4:"1f34";s:38:"Classes/Core/Parser/TemplateParser.php";s:4:"ebaa";s:42:"Classes/Core/Parser/Interceptor/Escape.php";s:4:"fad1";s:47:"Classes/Core/Parser/SyntaxTree/AbstractNode.php";s:4:"e4b7";s:44:"Classes/Core/Parser/SyntaxTree/ArrayNode.php";s:4:"2cc1";s:46:"Classes/Core/Parser/SyntaxTree/BooleanNode.php";s:4:"d9c3";s:48:"Classes/Core/Parser/SyntaxTree/NodeInterface.php";s:4:"1a31";s:53:"Classes/Core/Parser/SyntaxTree/ObjectAccessorNode.php";s:4:"b0a9";s:65:"Classes/Core/Parser/SyntaxTree/RenderingContextAwareInterface.php";s:4:"68ab";s:43:"Classes/Core/Parser/SyntaxTree/RootNode.php";s:4:"6b59";s:43:"Classes/Core/Parser/SyntaxTree/TextNode.php";s:4:"fb66";s:49:"Classes/Core/Parser/SyntaxTree/ViewHelperNode.php";s:4:"395b";s:43:"Classes/Core/Rendering/RenderingContext.php";s:4:"58a7";s:52:"Classes/Core/Rendering/RenderingContextInterface.php";s:4:"a8ad";s:55:"Classes/Core/ViewHelper/AbstractConditionViewHelper.php";s:4:"b200";s:54:"Classes/Core/ViewHelper/AbstractTagBasedViewHelper.php";s:4:"759b";s:46:"Classes/Core/ViewHelper/AbstractViewHelper.php";s:4:"edd4";s:46:"Classes/Core/ViewHelper/ArgumentDefinition.php";s:4:"c4d5";s:37:"Classes/Core/ViewHelper/Arguments.php";s:4:"8553";s:37:"Classes/Core/ViewHelper/Exception.php";s:4:"f1d0";s:46:"Classes/Core/ViewHelper/TagBasedViewHelper.php";s:4:"b234";s:38:"Classes/Core/ViewHelper/TagBuilder.php";s:4:"1e21";s:53:"Classes/Core/ViewHelper/TemplateVariableContainer.php";s:4:"33b5";s:47:"Classes/Core/ViewHelper/ViewHelperInterface.php";s:4:"8274";s:55:"Classes/Core/ViewHelper/ViewHelperVariableContainer.php";s:4:"f6b8";s:62:"Classes/Core/ViewHelper/Exception/InvalidVariableException.php";s:4:"6dd4";s:76:"Classes/Core/ViewHelper/Exception/RenderingContextNotAccessibleException.php";s:4:"bbb7";s:59:"Classes/Core/ViewHelper/Facets/ChildNodeAccessInterface.php";s:4:"c50b";s:54:"Classes/Core/ViewHelper/Facets/CompilableInterface.php";s:4:"c4cd";s:53:"Classes/Core/ViewHelper/Facets/PostParseInterface.php";s:4:"35d5";s:48:"Classes/Core/Widget/AbstractWidgetController.php";s:4:"198b";s:48:"Classes/Core/Widget/AbstractWidgetViewHelper.php";s:4:"0ab7";s:47:"Classes/Core/Widget/AjaxWidgetContextHolder.php";s:4:"4658";s:33:"Classes/Core/Widget/Bootstrap.php";s:4:"3ea8";s:33:"Classes/Core/Widget/Exception.php";s:4:"a6e3";s:37:"Classes/Core/Widget/WidgetContext.php";s:4:"305f";s:37:"Classes/Core/Widget/WidgetRequest.php";s:4:"0ff7";s:44:"Classes/Core/Widget/WidgetRequestBuilder.php";s:4:"cf2b";s:44:"Classes/Core/Widget/WidgetRequestHandler.php";s:4:"c616";s:60:"Classes/Core/Widget/Exception/MissingControllerException.php";s:4:"5b77";s:67:"Classes/Core/Widget/Exception/RenderingContextNotFoundException.php";s:4:"a31b";s:64:"Classes/Core/Widget/Exception/WidgetContextNotFoundException.php";s:4:"ffc2";s:64:"Classes/Core/Widget/Exception/WidgetRequestNotFoundException.php";s:4:"7ce2";s:36:"Classes/Service/DocbookGenerator.php";s:4:"c6a5";s:37:"Classes/View/AbstractTemplateView.php";s:4:"f68b";s:26:"Classes/View/Exception.php";s:4:"4168";s:31:"Classes/View/StandaloneView.php";s:4:"3639";s:29:"Classes/View/TemplateView.php";s:4:"b65b";s:50:"Classes/View/Exception/InvalidSectionException.php";s:4:"6cb2";s:59:"Classes/View/Exception/InvalidTemplateResourceException.php";s:4:"d589";s:39:"Classes/ViewHelpers/AliasViewHelper.php";s:4:"06f9";s:38:"Classes/ViewHelpers/BaseViewHelper.php";s:4:"a23f";s:41:"Classes/ViewHelpers/CObjectViewHelper.php";s:4:"57b2";s:41:"Classes/ViewHelpers/CommentViewHelper.php";s:4:"5318";s:39:"Classes/ViewHelpers/CountViewHelper.php";s:4:"6b17";s:39:"Classes/ViewHelpers/CycleViewHelper.php";s:4:"d3bf";s:39:"Classes/ViewHelpers/DebugViewHelper.php";s:4:"2000";s:38:"Classes/ViewHelpers/ElseViewHelper.php";s:4:"cdde";s:40:"Classes/ViewHelpers/EscapeViewHelper.php";s:4:"69ef";s:47:"Classes/ViewHelpers/FlashMessagesViewHelper.php";s:4:"b9db";s:37:"Classes/ViewHelpers/ForViewHelper.php";s:4:"7068";s:38:"Classes/ViewHelpers/FormViewHelper.php";s:4:"2e41";s:44:"Classes/ViewHelpers/GroupedForViewHelper.php";s:4:"e0a9";s:36:"Classes/ViewHelpers/IfViewHelper.php";s:4:"aa56";s:39:"Classes/ViewHelpers/ImageViewHelper.php";s:4:"d12c";s:40:"Classes/ViewHelpers/LayoutViewHelper.php";s:4:"330b";s:48:"Classes/ViewHelpers/RenderChildrenViewHelper.php";s:4:"218b";s:53:"Classes/ViewHelpers/RenderFlashMessagesViewHelper.php";s:4:"3e52";s:40:"Classes/ViewHelpers/RenderViewHelper.php";s:4:"a8d7";s:41:"Classes/ViewHelpers/SectionViewHelper.php";s:4:"99f9";s:38:"Classes/ViewHelpers/ThenViewHelper.php";s:4:"de0c";s:43:"Classes/ViewHelpers/TranslateViewHelper.php";s:4:"e9b6";s:52:"Classes/ViewHelpers/Be/AbstractBackendViewHelper.php";s:4:"0b21";s:46:"Classes/ViewHelpers/Be/ContainerViewHelper.php";s:4:"1a81";s:45:"Classes/ViewHelpers/Be/PageInfoViewHelper.php";s:4:"2a33";s:45:"Classes/ViewHelpers/Be/PagePathViewHelper.php";s:4:"7bd7";s:46:"Classes/ViewHelpers/Be/TableListViewHelper.php";s:4:"13c5";s:48:"Classes/ViewHelpers/Be/Buttons/CshViewHelper.php";s:4:"1189";s:49:"Classes/ViewHelpers/Be/Buttons/IconViewHelper.php";s:4:"b906";s:53:"Classes/ViewHelpers/Be/Buttons/ShortcutViewHelper.php";s:4:"8b35";s:57:"Classes/ViewHelpers/Be/Menus/ActionMenuItemViewHelper.php";s:4:"3d58";s:53:"Classes/ViewHelpers/Be/Menus/ActionMenuViewHelper.php";s:4:"cb0d";s:61:"Classes/ViewHelpers/Be/Security/IfAuthenticatedViewHelper.php";s:4:"8bc3";s:55:"Classes/ViewHelpers/Be/Security/IfHasRoleViewHelper.php";s:4:"70a0";s:56:"Classes/ViewHelpers/Form/AbstractFormFieldViewHelper.php";s:4:"eb30";s:51:"Classes/ViewHelpers/Form/AbstractFormViewHelper.php";s:4:"d023";s:47:"Classes/ViewHelpers/Form/CheckboxViewHelper.php";s:4:"57b8";s:45:"Classes/ViewHelpers/Form/ErrorsViewHelper.php";s:4:"5f6b";s:45:"Classes/ViewHelpers/Form/HiddenViewHelper.php";s:4:"fd4f";s:47:"Classes/ViewHelpers/Form/PasswordViewHelper.php";s:4:"a364";s:44:"Classes/ViewHelpers/Form/RadioViewHelper.php";s:4:"d39f";s:45:"Classes/ViewHelpers/Form/SelectViewHelper.php";s:4:"c54f";s:45:"Classes/ViewHelpers/Form/SubmitViewHelper.php";s:4:"ee63";s:47:"Classes/ViewHelpers/Form/TextareaViewHelper.php";s:4:"dd77";s:46:"Classes/ViewHelpers/Form/TextboxViewHelper.php";s:4:"7278";s:48:"Classes/ViewHelpers/Form/TextfieldViewHelper.php";s:4:"c0ae";s:45:"Classes/ViewHelpers/Form/UploadViewHelper.php";s:4:"9b9e";s:56:"Classes/ViewHelpers/Form/ValidationResultsViewHelper.php";s:4:"20a0";s:57:"Classes/ViewHelpers/Format/AbstractEncodingViewHelper.php";s:4:"ad2e";s:45:"Classes/ViewHelpers/Format/CropViewHelper.php";s:4:"02f6";s:49:"Classes/ViewHelpers/Format/CurrencyViewHelper.php";s:4:"4911";s:45:"Classes/ViewHelpers/Format/DateViewHelper.php";s:4:"63c6";s:45:"Classes/ViewHelpers/Format/HtmlViewHelper.php";s:4:"5358";s:59:"Classes/ViewHelpers/Format/HtmlentitiesDecodeViewHelper.php";s:4:"7a3a";s:53:"Classes/ViewHelpers/Format/HtmlentitiesViewHelper.php";s:4:"b1d8";s:57:"Classes/ViewHelpers/Format/HtmlspecialcharsViewHelper.php";s:4:"d7bd";s:46:"Classes/ViewHelpers/Format/Nl2brViewHelper.php";s:4:"485e";s:47:"Classes/ViewHelpers/Format/NumberViewHelper.php";s:4:"4df4";s:48:"Classes/ViewHelpers/Format/PaddingViewHelper.php";s:4:"1c21";s:47:"Classes/ViewHelpers/Format/PrintfViewHelper.php";s:4:"7a95";s:44:"Classes/ViewHelpers/Format/RawViewHelper.php";s:4:"5980";s:50:"Classes/ViewHelpers/Format/StripTagsViewHelper.php";s:4:"f380";s:50:"Classes/ViewHelpers/Format/UrlencodeViewHelper.php";s:4:"c701";s:45:"Classes/ViewHelpers/Link/ActionViewHelper.php";s:4:"5e73";s:44:"Classes/ViewHelpers/Link/EmailViewHelper.php";s:4:"5b79";s:47:"Classes/ViewHelpers/Link/ExternalViewHelper.php";s:4:"3e52";s:43:"Classes/ViewHelpers/Link/PageViewHelper.php";s:4:"b6bc";s:58:"Classes/ViewHelpers/Security/IfAuthenticatedViewHelper.php";s:4:"b675";s:52:"Classes/ViewHelpers/Security/IfHasRoleViewHelper.php";s:4:"365b";s:44:"Classes/ViewHelpers/Uri/ActionViewHelper.php";s:4:"0bb9";s:43:"Classes/ViewHelpers/Uri/EmailViewHelper.php";s:4:"3ac1";s:46:"Classes/ViewHelpers/Uri/ExternalViewHelper.php";s:4:"3a74";s:43:"Classes/ViewHelpers/Uri/ImageViewHelper.php";s:4:"2e43";s:42:"Classes/ViewHelpers/Uri/PageViewHelper.php";s:4:"1a59";s:46:"Classes/ViewHelpers/Uri/ResourceViewHelper.php";s:4:"d2d7";s:53:"Classes/ViewHelpers/Widget/AutocompleteViewHelper.php";s:4:"8a67";s:45:"Classes/ViewHelpers/Widget/LinkViewHelper.php";s:4:"a181";s:49:"Classes/ViewHelpers/Widget/PaginateViewHelper.php";s:4:"7a5d";s:44:"Classes/ViewHelpers/Widget/UriViewHelper.php";s:4:"9100";s:64:"Classes/ViewHelpers/Widget/Controller/AutocompleteController.php";s:4:"2983";s:60:"Classes/ViewHelpers/Widget/Controller/PaginateController.php";s:4:"1573";s:34:"Configuration/TypoScript/setup.txt";s:4:"430a";s:70:"Resources/Private/Templates/ViewHelpers/Widget/Autocomplete/Index.html";s:4:"b955";s:66:"Resources/Private/Templates/ViewHelpers/Widget/Paginate/Index.html";s:4:"55a2";s:43:"Tests/Unit/Core/Fixtures/TestViewHelper.php";s:4:"253f";s:44:"Tests/Unit/Core/Fixtures/TestViewHelper2.php";s:4:"f08c";s:43:"Tests/Unit/Core/Parser/ParsingStateTest.php";s:4:"c6e2";s:52:"Tests/Unit/Core/Parser/TemplateParserPatternTest.php";s:4:"1ef3";s:45:"Tests/Unit/Core/Parser/TemplateParserTest.php";s:4:"a0c5";s:66:"Tests/Unit/Core/Parser/Fixtures/ChildNodeAccessFacetViewHelper.php";s:4:"01d1";s:60:"Tests/Unit/Core/Parser/Fixtures/PostParseFacetViewHelper.php";s:4:"4546";s:79:"Tests/Unit/Core/Parser/Fixtures/TemplateParserTestFixture01-shorthand-split.php";s:4:"ea3a";s:74:"Tests/Unit/Core/Parser/Fixtures/TemplateParserTestFixture01-shorthand.html";s:4:"e949";s:69:"Tests/Unit/Core/Parser/Fixtures/TemplateParserTestFixture06-split.php";s:4:"8d2c";s:64:"Tests/Unit/Core/Parser/Fixtures/TemplateParserTestFixture06.html";s:4:"4379";s:69:"Tests/Unit/Core/Parser/Fixtures/TemplateParserTestFixture14-split.php";s:4:"bd4b";s:64:"Tests/Unit/Core/Parser/Fixtures/TemplateParserTestFixture14.html";s:4:"1ec8";s:49:"Tests/Unit/Core/Parser/Interceptor/EscapeTest.php";s:4:"91cf";s:54:"Tests/Unit/Core/Parser/SyntaxTree/AbstractNodeTest.php";s:4:"48a5";s:53:"Tests/Unit/Core/Parser/SyntaxTree/BooleanNodeTest.php";s:4:"9e8b";s:50:"Tests/Unit/Core/Parser/SyntaxTree/TextNodeTest.php";s:4:"4fcd";s:56:"Tests/Unit/Core/Parser/SyntaxTree/ViewHelperNodeTest.php";s:4:"d6a8";s:50:"Tests/Unit/Core/Rendering/RenderingContextTest.php";s:4:"9bb6";s:62:"Tests/Unit/Core/ViewHelper/AbstractConditionViewHelperTest.php";s:4:"a579";s:61:"Tests/Unit/Core/ViewHelper/AbstractTagBasedViewHelperTest.php";s:4:"7311";s:53:"Tests/Unit/Core/ViewHelper/AbstractViewHelperTest.php";s:4:"3e84";s:53:"Tests/Unit/Core/ViewHelper/ArgumentDefinitionTest.php";s:4:"3b5c";s:45:"Tests/Unit/Core/ViewHelper/TagBuilderTest.php";s:4:"63af";s:60:"Tests/Unit/Core/ViewHelper/TemplateVariableContainerTest.php";s:4:"d251";s:62:"Tests/Unit/Core/ViewHelper/ViewHelperVariableContainerTest.php";s:4:"4373";s:55:"Tests/Unit/Core/Widget/AbstractWidgetControllerTest.php";s:4:"cfbf";s:55:"Tests/Unit/Core/Widget/AbstractWidgetViewHelperTest.php";s:4:"a400";s:54:"Tests/Unit/Core/Widget/AjaxWidgetContextHolderTest.php";s:4:"305b";s:44:"Tests/Unit/Core/Widget/WidgetContextTest.php";s:4:"0c7b";s:51:"Tests/Unit/Core/Widget/WidgetRequestBuilderTest.php";s:4:"440c";s:51:"Tests/Unit/Core/Widget/WidgetRequestHandlerTest.php";s:4:"81fe";s:44:"Tests/Unit/Core/Widget/WidgetRequestTest.php";s:4:"4a31";s:44:"Tests/Unit/View/AbstractTemplateViewTest.php";s:4:"3ecc";s:38:"Tests/Unit/View/StandaloneViewTest.php";s:4:"0ef5";s:36:"Tests/Unit/View/TemplateViewTest.php";s:4:"4ad8";s:38:"Tests/Unit/View/Fixtures/LayoutFixture";s:4:"85d8";s:43:"Tests/Unit/View/Fixtures/LayoutFixture.html";s:4:"5630";s:42:"Tests/Unit/View/Fixtures/LayoutFixture.xml";s:4:"85be";s:51:"Tests/Unit/View/Fixtures/StandaloneViewFixture.html";s:4:"8952";s:48:"Tests/Unit/View/Fixtures/TemplateViewFixture.php";s:4:"79e8";s:56:"Tests/Unit/View/Fixtures/TemplateViewSectionFixture.html";s:4:"5c65";s:54:"Tests/Unit/View/Fixtures/TransparentSyntaxTreeNode.php";s:4:"eb0d";s:53:"Tests/Unit/View/Fixtures/UnparsedTemplateFixture.html";s:4:"59dd";s:46:"Tests/Unit/ViewHelpers/AliasViewHelperTest.php";s:4:"bb8f";s:45:"Tests/Unit/ViewHelpers/BaseViewHelperTest.php";s:4:"ddfb";s:46:"Tests/Unit/ViewHelpers/CountViewHelperTest.php";s:4:"9aff";s:46:"Tests/Unit/ViewHelpers/CycleViewHelperTest.php";s:4:"c976";s:45:"Tests/Unit/ViewHelpers/ElseViewHelperTest.php";s:4:"0d90";s:44:"Tests/Unit/ViewHelpers/ForViewHelperTest.php";s:4:"a758";s:45:"Tests/Unit/ViewHelpers/FormViewHelperTest.php";s:4:"17c1";s:51:"Tests/Unit/ViewHelpers/GroupedForViewHelperTest.php";s:4:"8f37";s:43:"Tests/Unit/ViewHelpers/IfViewHelperTest.php";s:4:"25b8";s:55:"Tests/Unit/ViewHelpers/RenderChildrenViewHelperTest.php";s:4:"507d";s:47:"Tests/Unit/ViewHelpers/RenderViewHelperTest.php";s:4:"e191";s:45:"Tests/Unit/ViewHelpers/ThenViewHelperTest.php";s:4:"360c";s:49:"Tests/Unit/ViewHelpers/ViewHelperBaseTestcase.php";s:4:"b7d6";s:59:"Tests/Unit/ViewHelpers/Be/IfAuthenticatedViewHelperTest.php";s:4:"14d1";s:53:"Tests/Unit/ViewHelpers/Be/IfHasRoleViewHelperTest.php";s:4:"cec2";s:60:"Tests/Unit/ViewHelpers/Fixtures/ConstraintSyntaxTreeNode.php";s:4:"97f2";s:46:"Tests/Unit/ViewHelpers/Fixtures/IfFixture.html";s:4:"2e12";s:54:"Tests/Unit/ViewHelpers/Fixtures/IfThenElseFixture.html";s:4:"d826";s:63:"Tests/Unit/ViewHelpers/Form/AbstractFormFieldViewHelperTest.php";s:4:"1e96";s:58:"Tests/Unit/ViewHelpers/Form/AbstractFormViewHelperTest.php";s:4:"3ee8";s:54:"Tests/Unit/ViewHelpers/Form/CheckboxViewHelperTest.php";s:4:"3db9";s:63:"Tests/Unit/ViewHelpers/Form/FormFieldViewHelperBaseTestcase.php";s:4:"5121";s:52:"Tests/Unit/ViewHelpers/Form/HiddenViewHelperTest.php";s:4:"6699";s:54:"Tests/Unit/ViewHelpers/Form/PasswordViewHelperTest.php";s:4:"a81c";s:51:"Tests/Unit/ViewHelpers/Form/RadioViewHelperTest.php";s:4:"dc6d";s:52:"Tests/Unit/ViewHelpers/Form/SelectViewHelperTest.php";s:4:"d85d";s:52:"Tests/Unit/ViewHelpers/Form/SubmitViewHelperTest.php";s:4:"80c1";s:54:"Tests/Unit/ViewHelpers/Form/TextareaViewHelperTest.php";s:4:"bfb6";s:53:"Tests/Unit/ViewHelpers/Form/TextboxViewHelperTest.php";s:4:"0d38";s:52:"Tests/Unit/ViewHelpers/Form/UploadViewHelperTest.php";s:4:"350e";s:60:"Tests/Unit/ViewHelpers/Form/Fixtures/EmptySyntaxTreeNode.php";s:4:"9dca";s:64:"Tests/Unit/ViewHelpers/Form/Fixtures/Fixture_UserDomainClass.php";s:4:"494c";s:52:"Tests/Unit/ViewHelpers/Format/CropViewHelperTest.php";s:4:"15e4";s:56:"Tests/Unit/ViewHelpers/Format/CurrencyViewHelperTest.php";s:4:"511c";s:52:"Tests/Unit/ViewHelpers/Format/DateViewHelperTest.php";s:4:"3348";s:66:"Tests/Unit/ViewHelpers/Format/HtmlentitiesDecodeViewHelperTest.php";s:4:"cef0";s:60:"Tests/Unit/ViewHelpers/Format/HtmlentitiesViewHelperTest.php";s:4:"1fa3";s:64:"Tests/Unit/ViewHelpers/Format/HtmlspecialcharsViewHelperTest.php";s:4:"9548";s:53:"Tests/Unit/ViewHelpers/Format/Nl2brViewHelperTest.php";s:4:"7ceb";s:54:"Tests/Unit/ViewHelpers/Format/NumberViewHelperTest.php";s:4:"870f";s:55:"Tests/Unit/ViewHelpers/Format/PaddingViewHelperTest.php";s:4:"7e5a";s:54:"Tests/Unit/ViewHelpers/Format/PrintfViewHelperTest.php";s:4:"5f84";s:51:"Tests/Unit/ViewHelpers/Format/RawViewHelperTest.php";s:4:"9695";s:57:"Tests/Unit/ViewHelpers/Format/StripTagsViewHelperTest.php";s:4:"9d8c";s:57:"Tests/Unit/ViewHelpers/Format/UrlencodeViewHelperTest.php";s:4:"2cef";s:51:"Tests/Unit/ViewHelpers/Link/EmailViewHelperTest.php";s:4:"8d09";s:54:"Tests/Unit/ViewHelpers/Link/ExternalViewHelperTest.php";s:4:"9571";s:65:"Tests/Unit/ViewHelpers/Security/IfAuthenticatedViewHelperTest.php";s:4:"af2a";s:59:"Tests/Unit/ViewHelpers/Security/IfHasRoleViewHelperTest.php";s:4:"5722";s:50:"Tests/Unit/ViewHelpers/Uri/EmailViewHelperTest.php";s:4:"0b80";s:53:"Tests/Unit/ViewHelpers/Uri/ExternalViewHelperTest.php";s:4:"5405";}',
	'suggests' => array(
	),
);

?>