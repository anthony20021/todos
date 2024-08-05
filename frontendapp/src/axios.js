import { API_URL } from './config';
import Cookies from 'js-cookie'; // Importez js-cookie

async function fetchWithCredentials(endpoint, method = 'GET', body = null) {
    const url = `${API_URL}${endpoint}`;

    // Récupérez le token CSRF du cookie
    const csrfToken = Cookies.get('XSRF-TOKEN'); // Assurez-vous que le nom du cookie correspond à celui utilisé par votre application

    const options = {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken, // Ajoutez le token CSRF aux en-têtes
        },
        credentials: 'include',
    };

    if (body) {
        options.body = JSON.stringify(body);
    }

    try {
        const response = await fetch(url, options);

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        return data;
    } catch (error) {
        // console.error('Fetch error:', error);
        throw error;
    }
}

export default fetchWithCredentials;
