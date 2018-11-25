/**
 * BLOCK: Example Block
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

//  Import CSS.
import './style.scss';
import './editor.scss';

const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

registerBlockType( 'oil-based/example-block', {
	title: __( 'Example Block' ),
	icon: 'shield',
	category: 'common',
	keywords: [
		__( 'example-block' ),
		__( 'oil-based' ),
	],
	edit: function( props ) {
		return (
			<div className={ props.className }>
				<p>— Hello from the backend.</p>
				<p>
					CGB BLOCK: <code>example-block</code> is a new Gutenberg block
				</p>
			</div>
		);
	},
	save: function( props ) {
		return (
			<div>
				<p>— Hello from the frontend.</p>
				<p>
					CGB BLOCK: <code>example-block</code> is a new Gutenberg block.
				</p>
			</div>
		);
	},
} );