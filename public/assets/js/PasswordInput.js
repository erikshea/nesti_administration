
const calculatePasswordStrength = (password) => {
    let possibleChars = 1; // set of potentially different characters in password
    ["09", "az", "AZ"].forEach( range => {
        let re = new RegExp(`^.*[${range[0]}-${range[1]}].*$`);
        if ( re.test(password) ) {
            possibleChars += range.codePointAt(1) - range.codePointAt(0);
        }
    })

    // Equation source: https://www.ssi.gouv.fr/administration/precautions-elementaires/calculer-la-force-dun-mot-de-passe/
    return password.length * Math.log(possibleChars)/Math.log(2);
}


const IndicatedPasswordField = (props) => {
    const [password, setPassword] = React.useState(props.value || "");
    const requiredStrength = 60;

    let strength = calculatePasswordStrength(password);
    let percent = 100*strength/(requiredStrength * 1.5);
    if ( percent > 100 ){
        percent = 100;
    } 

    let colorClass;

    if ( percent > 66 ){
        colorClass = "success";
    } else if (percent > 33){
        colorClass = "warning";
    } else {
        colorClass  = "danger";
    }

    let failedValidators = [];
    if ( strength < requiredStrength ){
        failedValidators.push("Le mot de passe doit être fort.");
    }
    if ( !/[a-zA-ZÀ-ÿ]/.test(password) ){
        failedValidators.push("Le mot de passe doit contenir une lettre.");
    }
    if ( !/[0-9]/.test(password) ){
        failedValidators.push("Le mot de passe doit contenir un chiffre.");
    }

    const errors = failedValidators.map((errorMessage,i) =>
        <div key={i} className='failed-validator'>
            {errorMessage}
        </div>
    );

    return (
        <div className="col form-group">
            <div className="form-group__content ">
                <label htmlFor={props.name}>{props.label}</label>
                <div className='d-flex flex-column'>
                    <div className="input-group">
                        <div className="input-group-prepend">
                            <span className="input-group-text">
                                <i className="fas fa-lock"></i>
                            </span>
                        </div>
                        <input
                            id={props.name}
                            type='password'
                            name={props.name}
                            className={'password-input form-control ' + ( failedValidators.length == 0 ? "is-valid":"is-invalid") } 
                            onChange={(e)=>setPassword(e.target.value)}
                        />
                    </div>
                    <div className={"bg-"+colorClass} style={{height: "1rem", width: percent + "%"}}></div>
                </div>
                {errors}
            </div>
        </div>
    );
}

$(() => {
    let passwordInputContainerDiv =  document.getElementById('indicated-form-field');
    if ( passwordInputContainerDiv ){
        ReactDOM.render(<IndicatedPasswordField {...passwordProps}/>, passwordInputContainerDiv);
    }
})
