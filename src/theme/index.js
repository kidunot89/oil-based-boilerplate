// index.js
import './style.scss';

const { render } = wp.element;
const { Site, Provider } = window.oilBasedShared;

const infoDOM = document.querySelector( '#site-info' );
let endpoint;
if ( infoDOM ) {
  endpoint = infoDOM.getAttribute( 'data-endpoint' );
  infoDOM.parentElement.removeChild( infoDOM );
}

render( 
  (
    <Provider endpoint={ endpoint }>
        <Site />
    </Provider>
  ),
  document.getElementById( 'page' ) );
