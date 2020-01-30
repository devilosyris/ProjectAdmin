import React, { useState } from 'react';
import Axios from 'axios';

const LoginPage = ({ history }) => {
    const [credentials, setCredentials] = useState({
        username:"" ,
        password:""
    })

    const [error, setError] = useState("");

    const handleChange = event => {
        const value = event.currentTarget.value;
        const name = event.currentTarget.name;

        setCredentials({ ...credentials, [name]: value});
    }
    const handleSubmit = async event => {
        event.preventDefault();
        try {
            const token = await Axios
            .post("http://localhost:8000/api/login_check", credentials)
            .then(response => response.data.token)

            setError("");
            window.localStorage.setItem("authToken", token);

            Axios.defaults.headers["Authorization"]= "Bearer " + token;

        } catch (error) {
            setError("Les identifiants sont incorrects");
        }

    }

    console.log(credentials);
    return <>
        <h1>Connexion à l'application</h1>
        <form onSubmit={handleSubmit}>
            <div className="form-group">
                <label htmlFor="username">Adresse email</label>
                <input type="email" name="username" id="api-username" className="form-control"
                value={credentials.username} onChange={handleChange} />
            </div>
            <div className="form-group">
                <label htmlFor="password"></label>
                <input type="password" name="password" id="api-password" className="form-control"
                value={credentials.password} onChange={handleChange} />
            </div>
            <button type="submit" className="btn success">Envoyer</button>
        </form>
    </>;
}
 
export default LoginPage;