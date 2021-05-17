import React, {Component} from 'react';

class ValueToJson extends Component {
    constructor(props) {
        super(props);
        this.state = {editConfig: []}
        this.handleConfig = this.handleConfig.bind(this)
        this.state = {values: [], keys: []}
        // parse props.initValue
        this.parseInitData();
        // send configuration back to the parent
    }

    componentDidUpdate() {
        this.handleConfig();
    }

    parseInitData() {

        let stateValue = [];
        let stateKey = [];
        let ssl = ''
        let initValue = this.props.initValue;
        let keys = [];
        let requiredType
        const type = this.props.type
        if (Object.keys(initValue).length > 0) {
            if (type === "Keyword") {
                stateKey = Object.keys(initValue[type])
                for (let i = 0; i < stateKey.length; i++) {
                    stateValue.push({text: initValue[type][i]})
                }
            }
            if (type === 'Ssl Expiration Test') {
                requiredType = type === 'Ssl Expiration Test' ? 'notifyBeforeExpiration' : ''
                stateKey = 0
                ssl = initValue[requiredType]
            }if(type === 'Schema Test'){
                document.getElementById('site_test_configuration').style.display = 'block'
                stateValue = initValue
            } else if (type === "Header" || type === 'Required Texts') {
                requiredType = (type === 'Header' ? 'requiredHeaders' : 'requiredTexts')
                keys = Object.keys(initValue[requiredType])
                console.log(keys)

                for (const key in keys) {
                    stateKey.push({key: keys[key]})
                }

                for (const key in initValue[requiredType]) {
                    stateValue.push({text: initValue[requiredType][key]})
                }
            }
            this.props.parent.setInitState({value: stateValue, keyArray: stateKey, text: ssl})
        }
    }

    handleConfig() {
        var result = {}
        const type = this.props.type

        if (type === "Keyword" || type === 'Ssl Expiration Test') {
            let requiredType = type === 'Keyword' ? 'Keyword' : 'notifyBeforeExpiration'
            result[requiredType] = this.props.value.map(f => {
                return f.text
            });
            if (type === 'Ssl Expiration Test') {
                result[requiredType] = this.props.text
            }
        } else if (type === "Header" || type === 'Required Texts') {
            var value = []
            var keys = []
            let requiredType = type === 'Header' ? 'requiredHeaders' : 'requiredTexts'
            result[requiredType] = {}

            value = this.props.value.map(f => {
                if (f.text) {
                    return f.text
                }
                return f
            })
            keys = this.props.keyArray.map(f => {
                if (f.key) {
                    return f.key
                }
                return f
            })

            for (var i = 0; i < value.length; i++) {
                result[requiredType][keys[i]] = value[i]
            }
        }
        document.getElementById('site_test_configuration').innerText = JSON.stringify(result);
    }

    render() {
        return (
            <div>

            </div>
        );
    }
}

export default ValueToJson;
