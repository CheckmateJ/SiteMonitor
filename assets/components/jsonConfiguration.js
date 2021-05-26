import React, {Component} from 'react';
import ValueToJson from "./valueToJson";


class JsonConfiguration extends Component {
    constructor(props) {
        super(props);
        this.state = {value: [], keyArray: [], text: "", key: "", type: document.querySelector('#site_test_type').value}
        this.valueChange = this.valueChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
        this.handleType = this.handleType.bind(this);
        this.keyChange = this.keyChange.bind(this);
        this.handleDelete = this.handleDelete.bind(this);
    }

    componentDidMount() {
        this.handleType()
    }

    valueChange(e) {
        this.setState({text: e.target.value});
    }

    keyChange(e) {
        this.setState({key: e.target.value})
    }

    handleDelete(i) {
        const removingValue = [...this.state.value]
        const removingKeys = [...this.state.keyArray]
        removingKeys.splice(i, 1)
        removingValue.splice(i, 1)
        this.setState({value: removingValue})
        this.setState({keyArray: removingKeys})
    }

    handleType() {
        document.querySelector('#site_test_type').addEventListener('change', (e) => {
            this.state.value = []
            this.state.keyArray = []
            this.setState(state => ({
                type: e.target.value,
            }));
            const configInput = document.getElementById('site_test_configuration')
            this.state.type === 'Schema Test' ? configInput.style.display = 'block' : configInput.style.display = 'none'

        })
        this.setState(state => ({
            type: document.querySelector('#site_test_type').value
        }));
    }

    handleSubmit(e) {
        e.preventDefault();
        const visible = document.getElementById('new-key').offsetLeft
        if (this.state.text.length === 0 || this.state.key.length === 0 && visible > 0) {
            return;
        }
        const newValue = {
            text: this.state.text
        };
        const newKey = {
            key: this.state.key
        }

        this.setState(state => ({
            value: this.state.value.concat(newValue),
            keyArray: this.state.keyArray.concat(newKey),
            text: '',
            key: ''
        }));
    }

    setInitState(config) {
        this.setState(config);
        console.log(config)
    }

    render() {
        return (
            <div>
                <ValueToJson value={this.state.value} type={this.state.type} keyArray={this.state.keyArray}
                             text={this.state.text} parent={this} initValue={this.props['init-value']}/>
                <table className={this.state.value.length > 0 ? 'table-show table' : 'table-hidden'}>
                    <thead>
                    <tr>
                        {this.state.type !== 'Keyword' ? <th scope="col">Key</th> : <th></th>}
                        <th scope="col">Value</th>
                        <th scope="col">Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    {this.state.value.map((value, el) => {
                        return <tr key={el}>
                            <td>{this.state.keyArray[el].key ? this.state.keyArray[el].key : ''}</td>
                            <td>{this.state.value[el].text ? this.state.value[el].text : this.state.value[el]}</td>
                            <td onClick={() => this.handleDelete(el)}><i
                                className="bi bi-trash"></i></td>
                        </tr>
                    })}
                    </tbody>
                </table>
                <label id="label-config">Configuration</label>
                <form onSubmit={this.handleSubmit}>
                    <input type="text" placeholder="key" id="new-key"
                           className={this.state.type === 'Keyword' || this.state.type === 'Ssl Expiration Test' || this.state.type === 'Schema Test' || this.state.type === 'Domain Expiration Test' ? 'hidden-input' : 'show-input form-control'}
                           onChange={this.keyChange} value={this.state.key}
                    />
                    <input id="new-value"
                           className={this.state.type === 'Schema Test' ? 'hidden-input' : 'show-input form-control'}
                           placeholder="value"
                           onChange={this.valueChange}
                           value={this.state.text}/>
                    <button type='submit'
                            className={this.state.type === 'Schema Test' || this.state.type === 'Ssl Expiration Test' || this.state.type === 'Domain Expiration Test' ? 'hidden-button' : 'show-button btn btn-primary'}
                            id="btn-add">Add
                    </button>
                </form>
            </div>
        );
    }
}

export default JsonConfiguration;