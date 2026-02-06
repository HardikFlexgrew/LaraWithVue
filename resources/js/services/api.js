import axios from "axios";


async function csrf() {
    await axios('/sanctum/csrf-cookie', { credentials: 'include' });
}

export async function apiRequest(url,options) {
    await csrf();
    const response = await axios.post(url,options);
    return response;
}