// index.js
import App from './app.jsx';
import './index.scss';

const { render } = wp.element;

render( <App />, document.getElementById( 'customizer-root' ) );