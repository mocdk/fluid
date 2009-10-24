<?php

/*                                                                        *
 * This script belongs to the FLOW3 package "Fluid".                      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * Template parser building up an object syntax tree
 *
 * @version $Id: TemplateParser.php 3333 2009-10-21 09:52:46Z sebastian $
 * @package Fluid
 * @subpackage Core\Parser
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class Tx_Fluid_Core_Parser_TemplateParser {

	public static $SCAN_PATTERN_NAMESPACEDECLARATION = '/(?<!\\\\){namespace\s*([a-zA-Z]+[a-zA-Z0-9]*)\s*=\s*((?:F3|Tx)(?:FLUID_NAMESPACE_SEPARATOR\w+)+)\s*}/m';

	/**
	 * This regular expression splits the input string at all dynamic tags, AND on all <![CDATA[...]]> sections.
	 *
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public static $SPLIT_PATTERN_TEMPLATE_DYNAMICTAGS = '/
		(
			(?: <\/?                                      # Start dynamic tags
					(?:(?:NAMESPACE):[a-zA-Z0-9\\.]+)     # A tag consists of the namespace prefix and word characters
					(?:                                   # Begin tag arguments
						\s*[a-zA-Z0-9:]+                  # Argument Keys
						=                                 # =
						(?:                               # either...
							"(?:\\\"|[^"])*"              # a double-quoted string
							|\'(?:\\\\\'|[^\'])*\'        # or a single quoted string
						)\s*                              #
					)*                                    # Tag arguments can be replaced many times.
				\s*
				\/?>                                      # Closing tag
			)
			|(?:                                          # Start match CDATA section
				<!\[CDATA\[.*?\]\]>
			)
		)/xs';

	/**
	 * This regular expression scans if the input string is a ViewHelper tag
	 *
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public static $SCAN_PATTERN_TEMPLATE_VIEWHELPERTAG = '/^<(?P<NamespaceIdentifier>NAMESPACE):(?P<MethodIdentifier>[a-zA-Z0-9\\.]+)(?P<Attributes>(?:\s*[a-zA-Z0-9:]+=(?:"(?:\\\"|[^"])*"|\'(?:\\\\\'|[^\'])*\')\s*)*)\s*(?P<Selfclosing>\/?)>$/';

	/**
	 * This regular expression scans if the input string is a closing ViewHelper tag
	 *
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public static $SCAN_PATTERN_TEMPLATE_CLOSINGVIEWHELPERTAG = '/^<\/(?P<NamespaceIdentifier>NAMESPACE):(?P<MethodIdentifier>[a-zA-Z0-9\\.]+)\s*>$/';

	/**
	 * This regular expression splits the tag arguments into its parts
	 *
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public static $SPLIT_PATTERN_TAGARGUMENTS = '/(?:\s*(?P<Argument>[a-zA-Z0-9:]+)=(?:"(?P<ValueDoubleQuoted>(?:\\\"|[^"])*)"|\'(?P<ValueSingleQuoted>(?:\\\\\'|[^\'])*)\')\s*)/';

	/**
	 * This pattern detects CDATA sections and outputs the text between opening and closing CDATA.
	 *
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public static $SCAN_PATTERN_CDATA = '/^<!\[CDATA\[(.*?)\]\]>$/s';

	/**
	 * Pattern which splits the shorthand syntax into different tokens. The "shorthand syntax" is everything like {...}
	 *
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public static $SPLIT_PATTERN_SHORTHANDSYNTAX = '/
		(
			{                                # Start of shorthand syntax
				(?:                          # Shorthand syntax is either composed of...
					[a-zA-Z0-9\->_:,.()]     # Various characters
					|"(?:\\\"|[^"])*"        # Double-quoted strings
					|\'(?:\\\\\'|[^\'])*\'   # Single-quoted strings
					|(?R)                    # Other shorthand syntaxes inside, albeit not in a quoted string
					|\s+                     # Spaces
				)+
			}                                # End of shorthand syntax
		)/x';

	/**
	 * Pattern which detects the object accessor syntax:
	 * {object.some.value}, additionally it detects ViewHelpers like {f:for(param1:bla)} and chaining like
	 * {object.some.value->f:bla.blubb()->f:bla.blubb2()}
	 *
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	// THIS IS ALMOST THE SAME AS IN $SCAN_PATTERN_SHORTHANDSYNTAX_ARRAYS
	public static $SCAN_PATTERN_SHORTHANDSYNTAX_OBJECTACCESSORS = '/
		^{                                                      # Start of shorthand syntax
			                                                 # A shorthand syntax is either...
			(?P<Object>[a-zA-Z0-9\-_.]*)                                     # ... an object accessor (definition is below because of some strange behavior of PCRE...
			(?:->)?

			(?P<ViewHelper>                                 # ... a ViewHelper
				[a-zA-Z0-9]+                                # Namespace prefix of ViewHelper (as in $SCAN_PATTERN_TEMPLATE_VIEWHELPERTAG)
				:
				[a-zA-Z0-9\\.]+                             # Method Identifier (as in $SCAN_PATTERN_TEMPLATE_VIEWHELPERTAG)
				\(                                          # Opening parameter brackets of ViewHelper
					(?P<ViewHelperArguments>                # Start submatch for ViewHelper arguments. This is taken from $SCAN_PATTERN_SHORTHANDSYNTAX_ARRAYS
						(?:
							\s*[a-zA-Z0-9\-_]+                  # The keys of the array
							\s*:\s*                             # Key|Value delimiter :
							(?:                                 # Possible value options:
								"(?:\\\"|[^"])*"                # Double qouoted string
								|\'(?:\\\\\'|[^\'])*\'          # Single quoted string
								|[a-zA-Z0-9\-_.]+               # variable identifiers
								|{(?P>ViewHelperArguments)}     # Another sub-array
							)                                   # END possible value options
							\s*,?                               # There might be a , to seperate different parts of the array
						)*                                  # The above cycle is repeated for all array elements
					)                                       # End ViewHelper Arguments submatch
				\)                                          # Closing parameter brackets of ViewHelper
			)?
			(?P<AdditionalViewHelpers>                      # There can be more than one ViewHelper chained, by adding more -> and the ViewHelper (recursively)
				(?:
					->
					(?P>ViewHelper)
				)*
			)
		}$/x';
	// THIS IS ALMOST THE SAME AS $SCAN_PATTERN_SHORTHANDSYNTAX_OBJECTACCESSORS
	public static $SPLIT_PATTERN_SHORTHANDSYNTAX_VIEWHELPER = '/

		(?P<NamespaceIdentifier>[a-zA-Z0-9]+)                               # Namespace prefix of ViewHelper (as in $SCAN_PATTERN_TEMPLATE_VIEWHELPERTAG)
		:
		(?P<MethodIdentifier>[a-zA-Z0-9\\.]+)
		\(                                          # Opening parameter brackets of ViewHelper
			(?P<ViewHelperArguments>                # Start submatch for ViewHelper arguments. This is taken from $SCAN_PATTERN_SHORTHANDSYNTAX_ARRAYS
				(?:
					\s*[a-zA-Z0-9\-_]+                  # The keys of the array
					\s*:\s*                             # Key|Value delimiter :
					(?:                                 # Possible value options:
						"(?:\\\"|[^"])*"                # Double qouoted string
						|\'(?:\\\\\'|[^\'])*\'          # Single quoted string
						|[a-zA-Z0-9\-_.]+               # variable identifiers
						|{(?P>ViewHelperArguments)}     # Another sub-array
					)                                   # END possible value options
					\s*,?                               # There might be a , to seperate different parts of the array
				)*                                  # The above cycle is repeated for all array elements
			)                                       # End ViewHelper Arguments submatch
		\)                                          # Closing parameter brackets of ViewHelper
		/x';
	/**
	 * Pattern which detects the array/object syntax like in JavaScript, so it detects strings like:
	 * {object: value, object2: {nested: array}, object3: "Some string"}
	 *
	 *
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	// THIS IS ALMOST THE SAME AS IN SCAN_PATTERN_SHORTHANDSYNTAX_OBJECTACCESSORS
	public static $SCAN_PATTERN_SHORTHANDSYNTAX_ARRAYS = '/^
		(?P<Recursion>                                  # Start the recursive part of the regular expression - describing the array syntax
			{                                           # Each array needs to start with {
				(?P<Array>                              # Start submatch
					(?:
						\s*[a-zA-Z0-9\-_]+              # The keys of the array
						\s*:\s*                         # Key|Value delimiter :
						(?:                             # Possible value options:
							"(?:\\\"|[^"])*"            # Double qouoted string
							|\'(?:\\\\\'|[^\'])*\'      # Single quoted string
							|[a-zA-Z0-9\-_.]+           # variable identifiers
							|(?P>Recursion)             # Another sub-array
						)                               # END possible value options
						\s*,?                           # There might be a , to seperate different parts of the array
					)*                                  # The above cycle is repeated for all array elements
				)                                       # End array submatch
			}                                           # Each array ends with }
		)$/x';

	/**
	 * This pattern splits an array into its parts. It is quite similar to the pattern above.
	 *
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public static $SPLIT_PATTERN_SHORTHANDSYNTAX_ARRAY_PARTS = '/
		(?P<ArrayPart>                                             # Start submatch
			(?P<Key>[a-zA-Z0-9\-_]+)                               # The keys of the array
			\s*:\s*                                                   # Key|Value delimiter :
			(?:                                                       # Possible value options:
				"(?P<DoubleQuotedString>(?:\\\"|[^"])*)"              # Double qouoted string
				|\'(?P<SingleQuotedString>(?:\\\\\'|[^\'])*)\'        # Single quoted string
				|(?P<VariableIdentifier>[a-zA-Z][a-zA-Z0-9\-_.]*)    # variable identifiers have to start with a letter
				|(?P<Number>[0-9.]+)                                  # Number
				|{\s*(?P<Subarray>(?:(?P>ArrayPart)\s*,?\s*)+)\s*}              # Another sub-array
			)                                                         # END possible value options
		)                                                          # End array part submatch
	/x';

	/**
	 * Namespace identifiers and their component name prefix (Associative array).
	 * @var array
	 */
	protected $namespaces = array(
		'f' => 'Tx_Fluid_ViewHelpers'
	);

	/**
	 * @var \Tx_Fluid_Compatibility_ObjectFactory
	 */
	protected $objectFactory;

	/**
	 * Constructor. Preprocesses the $SCAN_PATTERN_NAMESPACEDECLARATION by inserting the correct namespace separator.
	 *
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function __construct() {
		self::$SCAN_PATTERN_NAMESPACEDECLARATION = str_replace('FLUID_NAMESPACE_SEPARATOR', preg_quote(Tx_Fluid_Fluid::NAMESPACE_SEPARATOR), self::$SCAN_PATTERN_NAMESPACEDECLARATION);
	}

	/**
	 * Inject object factory
	 *
	 * @param Tx_Fluid_Compatibility_ObjectFactory $objectFactory
	 * @return void
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function injectObjectFactory(Tx_Fluid_Compatibility_ObjectFactory $objectFactory) {
		$this->objectFactory = $objectFactory;
	}

	/**
	 * Parses a given template and returns a parsed template object.
	 *
	 * @param string $templateString The template to parse as a string
	 * @return Tx_Fluid_Core_Parser_ParsedTemplateInterface Parsed template
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @todo Refine doc comment
	 */
	public function parse($templateString) {
		if (!is_string($templateString)) throw new Tx_Fluid_Core_Parser_Exception('Parse requires a template string as argument, ' . gettype($templateString) . ' given.', 1224237899);

		$this->initialize();

		$templateString = $this->extractNamespaceDefinitions($templateString);
		$splittedTemplate = $this->splitTemplateAtDynamicTags($templateString);
		$parsingState = $this->buildMainObjectTree($splittedTemplate);

		return $parsingState;
	}

	/**
	 * Gets the namespace definitions found.
	 *
	 * @return array Namespace identifiers and their component name prefix
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function getNamespaces() {
		return $this->namespaces;
	}

	/**
	 * Resets the parser to its default values.
	 *
	 * @return void
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	protected function initialize() {
		$this->namespaces = array(
			'f' => 'Tx_Fluid_ViewHelpers'
		);
	}

	/**
	 * Extracts namespace definitions out of the given template string and sets $this->namespaces.
	 *
	 * @param string $templateString Template string to extract the namespaces from
	 * @return string The updated template string without namespace declarations inside
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	protected function extractNamespaceDefinitions($templateString) {
		$matchedVariables = array();
		if (preg_match_all(self::$SCAN_PATTERN_NAMESPACEDECLARATION, $templateString, $matchedVariables) > 0) {
			foreach (array_keys($matchedVariables[0]) as $index) {
				$namespaceIdentifier = $matchedVariables[1][$index];
				$fullyQualifiedNamespace = $matchedVariables[2][$index];
				if (key_exists($namespaceIdentifier, $this->namespaces)) {
					throw new Tx_Fluid_Core_Parser_Exception('Namespace identifier "' . $namespaceIdentifier . '" is already registered. Do not redeclare namespaces!', 1224241246);
				}
				$this->namespaces[$namespaceIdentifier] = $fullyQualifiedNamespace;
			}

			$templateString = preg_replace(self::$SCAN_PATTERN_NAMESPACEDECLARATION, '', $templateString);
		}
		return $templateString;
	}

	/**
	 * Splits the template string on all dynamic tags found.
	 *
	 * @param string $templateString Template string to split.
	 * @return array Splitted template
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	protected function splitTemplateAtDynamicTags($templateString) {
		$regularExpression = $this->prepareTemplateRegularExpression(self::$SPLIT_PATTERN_TEMPLATE_DYNAMICTAGS);
		return preg_split($regularExpression, $templateString, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
	}

	/**
	 * Build object tree from the splitted template
	 *
	 * @param array $splittedTemplate The splitted template, so that every tag with a namespace declaration is already a seperate array element.
	 * @return Tx_Fluid_Core_Parser_ParsingState
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	protected function buildMainObjectTree($splittedTemplate) {
		$regularExpression_viewHelperTag = $this->prepareTemplateRegularExpression(self::$SCAN_PATTERN_TEMPLATE_VIEWHELPERTAG);
		$regularExpression_closingViewHelperTag = $this->prepareTemplateRegularExpression(self::$SCAN_PATTERN_TEMPLATE_CLOSINGVIEWHELPERTAG);

		$state = $this->objectFactory->create('Tx_Fluid_Core_Parser_ParsingState');
		$rootNode = $this->objectFactory->create('Tx_Fluid_Core_Parser_SyntaxTree_RootNode');
		$state->setRootNode($rootNode);
		$state->pushNodeToStack($rootNode);

		foreach ($splittedTemplate as $templateElement) {
			$matchedVariables = array();
			if (preg_match(self::$SCAN_PATTERN_CDATA, $templateElement, $matchedVariables) > 0) {
				$this->handler_text($state, $matchedVariables[1]);
			} elseif (preg_match($regularExpression_viewHelperTag, $templateElement, $matchedVariables) > 0) {
				$namespaceIdentifier = $matchedVariables['NamespaceIdentifier'];
				$methodIdentifier = $matchedVariables['MethodIdentifier'];
				$selfclosing = $matchedVariables['Selfclosing'] === '' ? FALSE : TRUE;
				$arguments = $matchedVariables['Attributes'];

				$this->handler_openingViewHelperTag($state, $namespaceIdentifier, $methodIdentifier, $arguments, $selfclosing);
			} elseif (preg_match($regularExpression_closingViewHelperTag, $templateElement, $matchedVariables) > 0) {
				$namespaceIdentifier = $matchedVariables['NamespaceIdentifier'];
				$methodIdentifier = $matchedVariables['MethodIdentifier'];

				$this->handler_closingViewHelperTag($state, $namespaceIdentifier, $methodIdentifier);
			} else {
				$this->handler_textAndShorthandSyntax($state, $templateElement);
			}
		}

		if ($state->countNodeStack() !== 1) {
			throw new Tx_Fluid_Core_Parser_Exception('Not all tags were closed!', 1238169398);
		}
		return $state;
	}

	/**
	 * Handles an opening or self-closing view helper tag.
	 *
	 * @param Tx_Fluid_Core_Parser_ParsingState $state Current parsing state
	 * @param string $namespaceIdentifier Namespace identifier - being looked up in $this->namespaces
	 * @param string $methodIdentifier Method identifier
	 * @param string $arguments Arguments string, not yet parsed
	 * @param boolean $selfclosing true, if the tag is a self-closing tag.
	 * @return void
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	protected function handler_openingViewHelperTag(Tx_Fluid_Core_Parser_ParsingState $state, $namespaceIdentifier, $methodIdentifier, $arguments, $selfclosing) {
		$argumentsObjectTree = $this->parseArguments($arguments);
		$this->initializeViewHelperAndAddItToStack($state, $namespaceIdentifier, $methodIdentifier, $argumentsObjectTree);

		if ($selfclosing) {
			$state->popNodeFromStack();
		}
	}

	/**
	 * Initialize the given ViewHelper and adds it to the current node and to the stack.
	 *
	 * @param Tx_Fluid_Core_Parser_ParsingState $state Current parsing state
	 * @param string $namespaceIdentifier Namespace identifier - being looked up in $this->namespaces
	 * @param string $methodIdentifier Method identifier
	 * @param array $argumentsObjectTree Arguments object tree
	 * @return void
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	protected function initializeViewHelperAndAddItToStack(Tx_Fluid_Core_Parser_ParsingState $state, $namespaceIdentifier, $methodIdentifier, $argumentsObjectTree) {
		if (!array_key_exists($namespaceIdentifier, $this->namespaces)) {
			throw new Tx_Fluid_Core_Parser_Exception('Namespace could not be resolved. This exception should never be thrown!', 1224254792);
		}

		$viewHelperName = $this->resolveViewHelperName($namespaceIdentifier, $methodIdentifier);

		$viewHelper = $this->objectFactory->create($viewHelperName);
		$expectedViewHelperArguments = $viewHelper->prepareArguments();
		$this->abortIfUnregisteredArgumentsExist($expectedViewHelperArguments, $argumentsObjectTree);
		$this->abortIfRequiredArgumentsAreMissing($expectedViewHelperArguments, $argumentsObjectTree);

		$currentDynamicNode = $this->objectFactory->create('Tx_Fluid_Core_Parser_SyntaxTree_ViewHelperNode', $viewHelperName, $argumentsObjectTree);

		$state->getNodeFromStack()->addChildNode($currentDynamicNode);

			// PostParse Facet
		if (in_array('Tx_Fluid_Core_ViewHelper_Facets_PostParseInterface',class_implements($viewHelperName))) {
			call_user_func(array($viewHelperName, 'postParseEvent'), $currentDynamicNode, $argumentsObjectTree, $state->getVariableContainer());
		}

		$state->pushNodeToStack($currentDynamicNode);
	}
	/**
	 * Throw a ParsingException if there are arguments which were not registered before.
	 *
	 * @param array $expectedArguments Array of Tx_Fluid_Core_ViewHelper_ArgumentDefinition of all expected arguments
	 * @param array $actualArguments Actual arguments
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	protected function abortIfUnregisteredArgumentsExist($expectedArguments, $actualArguments) {
		$expectedArgumentNames = array();
		foreach ($expectedArguments as $expectedArgument) {
			$expectedArgumentNames[] = $expectedArgument->getName();
		}

		foreach (array_keys($actualArguments) as $argumentName) {
			if (!in_array($argumentName, $expectedArgumentNames)) {
				throw new Tx_Fluid_Core_Parser_Exception('Argument "' . $argumentName . '" was not registered.', 1237823695);
			}
		}
	}

	/**
	 * Throw a ParsingException if required arguments are missing
	 *
	 * @param array $expectedArguments Array of Tx_Fluid_Core_ViewHelper_ArgumentDefinition of all expected arguments
	 * @param array $actualArguments Actual arguments
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	protected function abortIfRequiredArgumentsAreMissing($expectedArguments, $actualArguments) {
		$actualArgumentNames = array_keys($actualArguments);
		foreach ($expectedArguments as $expectedArgument) {
			if ($expectedArgument->isRequired() && !in_array($expectedArgument->getName(), $actualArgumentNames)) {
				throw new Tx_Fluid_Core_Parser_Exception('Required argument "' . $expectedArgument->getName() . '" was not supplied.', 1237823699);
			}
		}
	}

	/**
	 * Resolve a view helper.
	 *
	 * @param string $namespaceIdentifier Namespace identifier for the view helper.
	 * @param string $methodIdentifier Method identifier, might be hierarchical like "link.url"
	 * @return array An Array where the first argument is the object to call the method on, and the second argument is the method name
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	protected function resolveViewHelperName($namespaceIdentifier, $methodIdentifier) {
		$explodedViewHelperName = explode('.', $methodIdentifier);
		$className = '';
		if (count($explodedViewHelperName) > 1) {
			$className = implode(Tx_Fluid_Fluid::NAMESPACE_SEPARATOR, array_map('ucfirst', $explodedViewHelperName));
		} else {
			$className = ucfirst($explodedViewHelperName[0]);
		}
		$className .= 'ViewHelper';

		$name = $this->namespaces[$namespaceIdentifier] . Tx_Fluid_Fluid::NAMESPACE_SEPARATOR . $className;

		return $name;
	}

	/**
	 * Handles a closing view helper tag
	 *
	 * @param Tx_Fluid_Core_Parser_ParsingState $state The current parsing state
	 * @param string $namespaceIdentifier Namespace identifier for the closing tag.
	 * @param string $methodIdentifier Method identifier.
	 * @return void
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	protected function handler_closingViewHelperTag(Tx_Fluid_Core_Parser_ParsingState $state, $namespaceIdentifier, $methodIdentifier) {
		if (!array_key_exists($namespaceIdentifier, $this->namespaces)) {
			throw new Tx_Fluid_Core_Parser_Exception('Namespace could not be resolved. This exception should never be thrown!', 1224256186);
		}
		$lastStackElement = $state->popNodeFromStack();
		if (!($lastStackElement instanceof Tx_Fluid_Core_Parser_SyntaxTree_ViewHelperNode)) {
			throw new Tx_Fluid_Core_Parser_Exception('You closed a templating tag which you never opened!', 1224485838);
		}
		if ($lastStackElement->getViewHelperClassName() != $this->resolveViewHelperName($namespaceIdentifier, $methodIdentifier)) {
			throw new Tx_Fluid_Core_Parser_Exception('Templating tags not properly nested. Expected: ' . $lastStackElement->getViewHelperClassName() . '; Actual: ' . $this->resolveViewHelperName($namespaceIdentifier, $methodIdentifier), 1224485398);
		}
	}

	/**
	 * Handles the appearance of an object accessor (like {posts.author.email}).
	 * Creates a new instance of Tx_Fluid_ObjectAccessorNode.
	 *
	 * Handles ViewHelpers as well which are in the shorthand syntax.
	 *
	 * @param Tx_Fluid_Core_Parser_ParsingState $state The current parsing state
	 * @param string $objectAccessorString String which identifies which objects to fetch
	 * @return void
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	protected function handler_objectAccessor(Tx_Fluid_Core_Parser_ParsingState $state, $objectAccessorString, $viewHelperString, $additionalViewHelpersString) {
		$viewHelperString .= $additionalViewHelpersString;
		$numberOfViewHelpers = 0;

		// ViewHelpers
		if (strlen($viewHelperString) > 0 && preg_match_all(self::$SPLIT_PATTERN_SHORTHANDSYNTAX_VIEWHELPER, $viewHelperString, $matches, PREG_SET_ORDER) > 0) {
			$matches = array_reverse($matches); // The last ViewHelper has to be added first for correct chaining.
			foreach ($matches as $singleMatch) {
				$namespaceIdentifier = $singleMatch['NamespaceIdentifier'];
				$methodIdentifier = $singleMatch['MethodIdentifier'];
				if (strlen($singleMatch['ViewHelperArguments']) > 0) {
					$arguments = $this->handler_array_recursively($singleMatch['ViewHelperArguments']);
					$arguments = $this->postProcessArgumentsForObjectAccessor($arguments);
				} else {
					$arguments = array();
				}
				$this->initializeViewHelperAndAddItToStack($state, $namespaceIdentifier, $methodIdentifier, $arguments);
				$numberOfViewHelpers++;
			}
		}

		// Object Accessor
		if (strlen($objectAccessorString) > 0) {
			$node = $this->objectFactory->create('Tx_Fluid_Core_Parser_SyntaxTree_ObjectAccessorNode', $objectAccessorString);
			$state->getNodeFromStack()->addChildNode($node);
		}

		// Close ViewHelper Tags if needed.
		for ($i=0; $i<$numberOfViewHelpers; $i++) {
			$state->popNodeFromStack();
		}
	}

	/**
	 * Post process the arguments for the ViewHelpers in the object accessor syntax. We need to convert an array into an array of ViewHelper Nodes
	 *
	 * @param array $arguments The arguments to be processed
	 * @return array the processed array
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @todo This method should become superflous once the rest has been refactored, so that this code is not needed.
	 */
	protected function postProcessArgumentsForObjectAccessor(array $arguments) {
		$output = array();
		foreach ($arguments as $argumentName => $argumentValue) {
			$output[$argumentName] = $this->objectFactory->create('Tx_Fluid_Core_Parser_SyntaxTree_RootNode');
			if ($argumentValue instanceof Tx_Fluid_Core_Parser_SyntaxTree_AbstractNode) {
				$output[$argumentName]->addChildNode($argumentValue);
			} else {
				$output[$argumentName]->addChildNode($node = $this->objectFactory->create('Tx_Fluid_Core_Parser_SyntaxTree_TextNode', (string)$argumentValue));
			}
		}
		return $output;
	}

	/**
	 * Parse arguments of a given tag, and build up the Arguments Object Tree for each argument.
	 * Returns an associative array, where the key is the name of the argument,
	 * and the value is a single Argument Object Tree.
	 *
	 * @param string $argumentsString All arguments as string
	 * @return array An associative array of objects, where the key is the argument name.
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	protected function parseArguments($argumentsString) {
		$argumentsObjectTree = array();
		$matches = array();
		if (preg_match_all(self::$SPLIT_PATTERN_TAGARGUMENTS, $argumentsString, $matches, PREG_SET_ORDER) > 0) {
			foreach ($matches as $singleMatch) {
				$argument = $singleMatch['Argument'];
				if (!array_key_exists('ValueSingleQuoted', $singleMatch)) $singleMatch['ValueSingleQuoted'] = '';
				if (!array_key_exists('ValueDoubleQuoted', $singleMatch)) $singleMatch['ValueDoubleQuoted'] = '';

				$value = $this->unquoteArgumentString($singleMatch['ValueSingleQuoted'], $singleMatch['ValueDoubleQuoted']);
				$argumentsObjectTree[$argument] = $this->buildArgumentObjectTree($value);
			}
		}
		return $argumentsObjectTree;
	}

	/**
	 * Build up an argument object tree for the string in $argumentString.
	 * This builds up the tree for a single argument value.
	 *
	 * @param string $argumentString
	 * @return ArgumentObject the corresponding argument object tree.
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	protected function buildArgumentObjectTree($argumentString) {
		$splittedArgument = $this->splitTemplateAtDynamicTags($argumentString);
		$rootNode = $this->buildMainObjectTree($splittedArgument)->getRootNode();
		return $rootNode;
	}

	/**
	 * Removes escapings from a given argument string. Expects two string parameters, with one of them being empty.
	 * The first parameter should be non-empty if the argument was quoted by single quotes,
	 * and the second parameter should be non-empty if the argument was quoted by double quotes.
	 *
	 * This method is meant as a helper for regular expression results.
	 *
	 * @param string $singleQuotedValue Value, if quoted by single quotes
	 * @param string $doubleQuotedValue Value, if quoted by double quotes
	 * @return string Unquoted value
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	protected function unquoteArgumentString($singleQuotedValue, $doubleQuotedValue) {
		if ($singleQuotedValue != '') {
			$value = str_replace("\\'", "'", $singleQuotedValue);
		} else {
			$value = str_replace('\"', '"', $doubleQuotedValue);
		}
		return str_replace('\\\\', '\\', $value);
	}

	/**
	 * Takes a regular expression template and replaces "NAMESPACE" with the currently registered namespace identifiers. Returns a regular expression which is ready to use.
	 *
	 * @param string $regularExpression Regular expression template
	 * @return string Regular expression ready to be used
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	protected function prepareTemplateRegularExpression($regularExpression) {
		return str_replace('NAMESPACE', implode('|', array_keys($this->namespaces)), $regularExpression);
	}

	/**
	 * Handler for everything which is not a ViewHelperNode.
	 *
	 * This includes Text, array syntax, and object accessor syntax.
	 *
	 * @param Tx_Fluid_Core_Parser_ParsingState $state Current parsing state
	 * @param string $text Text to process
	 * @return void
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	protected function handler_textAndShorthandSyntax(Tx_Fluid_Core_Parser_ParsingState $state, $text) {
		$sections = preg_split($this->prepareTemplateRegularExpression(self::$SPLIT_PATTERN_SHORTHANDSYNTAX), $text, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

		foreach ($sections as $section) {
			$matchedVariables = array();
			if (preg_match(self::$SCAN_PATTERN_SHORTHANDSYNTAX_OBJECTACCESSORS, $section, $matchedVariables) > 0) {
				$this->handler_objectAccessor($state, $matchedVariables['Object'], (isset($matchedVariables['ViewHelper'])?$matchedVariables['ViewHelper']:''), (isset($matchedVariables['AdditionalViewHelpers'])?$matchedVariables['AdditionalViewHelpers']:''));
			} elseif (preg_match(self::$SCAN_PATTERN_SHORTHANDSYNTAX_ARRAYS, $section, $matchedVariables) > 0) {
				$this->handler_array($state, $matchedVariables['Array']);
			} else {
				$this->handler_text($state, $section);
			}
		}
	}

	/**
	 * Handler for array syntax. This creates the array object recursively and adds it to the current node.
	 *
	 * @param Tx_Fluid_Core_Parser_ParsingState $state The current parsing state
	 * @param string $arrayText The array as string.
	 * @return void
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	protected function handler_array(Tx_Fluid_Core_Parser_ParsingState $state, $arrayText) {
		$node = $this->objectFactory->create('Tx_Fluid_Core_Parser_SyntaxTree_ArrayNode', $this->handler_array_recursively($arrayText));
		$state->getNodeFromStack()->addChildNode($node);
	}

	/**
	 * Recursive function which takes the string representation of an array and builds an object tree from it.
	 *
	 * Deals with the following value types:
	 * - Numbers (Integers and Floats)
	 * - Strings
	 * - Variables
	 * - sub-arrays
	 *
	 * @param string $arrayText Array text
	 * @return Tx_Fluid_ArrayNode the array node built up
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	protected function handler_array_recursively($arrayText) {
		$matches = array();
		if (preg_match_all(self::$SPLIT_PATTERN_SHORTHANDSYNTAX_ARRAY_PARTS, $arrayText, $matches, PREG_SET_ORDER) > 0) {
			$arrayToBuild = array();
			foreach ($matches as $singleMatch) {
				$arrayKey = $singleMatch['Key'];
				if (!empty($singleMatch['VariableIdentifier'])) {
					$arrayToBuild[$arrayKey] = $this->objectFactory->create('Tx_Fluid_Core_Parser_SyntaxTree_ObjectAccessorNode', $singleMatch['VariableIdentifier']);
				} elseif (array_key_exists('Number', $singleMatch) && ( !empty($singleMatch['Number']) || $singleMatch['Number'] === '0' ) ) {
					$arrayToBuild[$arrayKey] = floatval($singleMatch['Number']);
				} elseif ( ( array_key_exists('DoubleQuotedString', $singleMatch) && !empty($singleMatch['DoubleQuotedString']) )
							|| ( array_key_exists('SingleQuotedString', $singleMatch) && !empty($singleMatch['SingleQuotedString']) ) ) {
					if (!array_key_exists('SingleQuotedString', $singleMatch)) $singleMatch['SingleQuotedString'] = '';
					if (!array_key_exists('DoubleQuotedString', $singleMatch)) $singleMatch['DoubleQuotedString'] = '';

					$arrayToBuild[$arrayKey] = $this->unquoteArgumentString($singleMatch['SingleQuotedString'], $singleMatch['DoubleQuotedString']);
				} elseif ( array_key_exists('Subarray', $singleMatch) && !empty($singleMatch['Subarray'])) {
					$arrayToBuild[$arrayKey] = $this->objectFactory->create('Tx_Fluid_Core_Parser_SyntaxTree_ArrayNode', $this->handler_array_recursively($singleMatch['Subarray']));
				} else {
					throw new Tx_Fluid_Core_Parser_Exception('This exception should never be thrown, as the array value has to be of some type (Value given: "' . var_export($singleMatch, TRUE) . '"). Please post your template to the bugtracker at forge.typo3.org.', 1225136013);
				}
			}
			return $arrayToBuild;
		} else {
			throw new Tx_Fluid_Core_Parser_Exception('This exception should never be thrown, there is most likely some error in the regular expressions. Please post your template to the bugtracker at forge.typo3.org.', 1225136013);
		}
	}

	/**
	 * Text node handler
	 *
	 * @param Tx_Fluid_Core_Parser_ParsingState $state
	 * @param string $text
	 * @return void
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	protected function handler_text(Tx_Fluid_Core_Parser_ParsingState $state, $text) {
		$node = $this->objectFactory->create('Tx_Fluid_Core_Parser_SyntaxTree_TextNode', $text);
		$state->getNodeFromStack()->addChildNode($node);
	}
}
?>