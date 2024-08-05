import { API_URL } from './config';

async function fetchWithCredentials(endpoint, method = 'GET', body = null) {
    const url = `${API_URL}${endpoint}`;

    const options = {
        method: method,
        headers: {
            'Content-Type': 'application/json',
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
