import classNames from 'classnames';
import PropTypes from 'prop-types';
import { Header, Main, Menu, Icon } from 'wp-graphql-composer';
import './site.scss';

const { Component } = wp.element;

class Site extends Component {
    constructor() {
        super( ...arguments );
        this.state = { toggled: false };
        this.toggle = this.toggle.bind( this );
    }

    toggle() {
        this.setState( { toggled: !this.state.toggled } );
    }

    render() {
        const { as: Container } = this.props;
        const className = classNames( "site", { toggled: this.state.toggled } );
        const toggleStyle = document.getElementById( "wpadminbar" ) ?
            { marginTop: '36px' } : {};

        return (
            <Container className={ className }>
                <div className="sidebar-wrapper">
                    <Header style={ { margin: '1em auto', maxWidth: '386px' } }>
                        <Menu location="PRIMARY" />
                    </Header>
                </div>
                <div className="page-content-wrapper">
                    <button className="sidebar-toggle" onClick={ this.toggle } style={ toggleStyle }>
                        <Icon name={ this.state.toggled ? 'menu' : 'exit_to_app' } />
                    </button>
                    <Main/>
                </div>
            </Container>
        );
    }
}

Site.propTypes = {
    as: PropTypes.oneOfType( [
        PropTypes.string,
        PropTypes.shape( {} ),
    ] ),
};

Site.defaultProps = {
    as: 'div',
};

export default Site;
