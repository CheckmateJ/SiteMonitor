import './styles/app.css';
import './bootstrap';
import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import JsonConfiguration from "./components/jsonConfiguration";
import './components/index';
if(document.getElementById('site_test_configuration')) {
    ReactDOM.render(<JsonConfiguration init-value={JSON.parse(document.getElementById('site_test_configuration').value)}/>, document.getElementById('type-configuration'));
}



