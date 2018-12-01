// index.js
import App from './app';
import './index.scss';

const { render } = wp.element;

render( <App />, document.getElementById( 'customizer-root' ) );