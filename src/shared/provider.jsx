import { HttpLink } from 'apollo-link-http';
import { BrowserRouter as Router } from 'react-router-dom';
import { WPProvider } from 'wp-graphql-composer';

const provider = ( { endpoint, basename = '/', children } ) => {
    const httpLink = new HttpLink({
        uri: endpoint,
        credentials: 'same-origin',
    });

    return (
        <WPProvider link={ httpLink }>
            <Router basename={ basename }>
                { children }
            </Router>
        </WPProvider>
    )
}

export default provider;