<?php

/**
 * Static class for hooks handled by the Maps extension.
 *
 * @since 0.7
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
final class MapsHooks {

	/**
	 * Adds a link to Admin Links page.
	 *
	 * @since 0.7
	 *
	 * @param ALTree $admin_links_tree
	 *
	 * @return boolean
	 */
	public static function addToAdminLinks( ALTree &$admin_links_tree ) {
		$displaying_data_section = $admin_links_tree->getSection( wfMsg( 'smw_adminlinks_displayingdata' ) );

		// Escape if SMW hasn't added links.
		if ( is_null( $displaying_data_section ) ) {
			return true;
		}

		$smw_docu_row = $displaying_data_section->getRow( 'smw' );

		$maps_docu_label = wfMsg( 'adminlinks_documentation', 'Maps' );
		$smw_docu_row->addItem( AlItem::newFromExternalLink( 'http://mapping.referata.com/wiki/Maps', $maps_docu_label ) );

		return true;
	}

	/**
	 * Hook to add PHPUnit test cases.
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/UnitTestsList
	 *
	 * @since 0.7
	 *
	 * @param array &$files
	 *
	 * @return boolean
	 */
	public static function registerUnitTests( array &$files ) {
		// @codeCoverageIgnoreStart
		$testFiles = array(
			'elements/Circle',
			'elements/ImageOverlay',
			'elements/Line',
			'elements/Location',
			'elements/Polygon',
			'elements/Rectangle',

			'parserhooks/Coordinates',
			'parserhooks/DisplayMap',
			'parserhooks/Distance',
			'parserhooks/Finddestination',
			'parserhooks/Geocode',
			'parserhooks/Geodistance',
			'parserhooks/MapsDoc',

			'parsers/DistanceParser',
			'parsers/LineParser',
			'parsers/LocationParser',
			'parsers/WmsOverlayParser',

			'Element',
			'MapsDistanceParser',
		);

		foreach ( $testFiles as $file ) {
			$files[] = __DIR__ . '/tests/phpunit/' . $file . 'Test.php';
		}

		return true;
		// @codeCoverageIgnoreEnd
	}

	/**
	 * Intercept pages in the Layer namespace to handle them correctly.
	 *
	 * @param $title: Title
	 * @param $article: Article or null
	 *
	 * @return boolean
	 */
	public static function onArticleFromTitle( Title &$title, /* Article */ &$article ) {
		if ( $title->getNamespace() == Maps_NS_LAYER ) {
			$article = new MapsLayerPage( $title );
		}

		return true;
	}

	/**
	 * Adds global JavaScript variables.
	 *
	 * @since 1.0
         * @see http://www.mediawiki.org/wiki/Manual:Hooks/MakeGlobalVariablesScript
         * @param array &$vars Variables to be added into the output
         * @param OutputPage $outputPage OutputPage instance calling the hook
         * @return boolean true in all cases
	 */
	public static function onMakeGlobalVariablesScript( array &$vars, OutputPage $outputPage ) {
		global $egMapsGlobalJSVars;

		$vars['egMapsDebugJS'] = $GLOBALS['egMapsDebugJS'];
                $vars[ 'egMapsAvailableServices' ] = $GLOBALS['egMapsAvailableServices'];

		$vars += $egMapsGlobalJSVars;

		return true;
	}

	/**
	 * @since 0.7
	 *
	 * @param array $list
	 *
	 * @return boolean
	 */
	public static function onCanonicalNamespaces( array &$list ) {
		$list[Maps_NS_LAYER] = 'Layer';
		$list[Maps_NS_LAYER_TALK] = 'Layer_talk';
		return true;
	}
}
