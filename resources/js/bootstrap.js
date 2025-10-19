// Lazy load axios only when needed
let axiosInstance = null;

const getAxios = async () => {
    if (!axiosInstance) {
        const { default: axios } = await import('axios');
        axiosInstance = axios;
        axiosInstance.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        window.axios = axiosInstance;
    }
    return axiosInstance;
};

// Make axios available globally but load it lazily
window.axios = {
    get: (...args) => getAxios().then(axios => axios.get(...args)),
    post: (...args) => getAxios().then(axios => axios.post(...args)),
    put: (...args) => getAxios().then(axios => axios.put(...args)),
    delete: (...args) => getAxios().then(axios => axios.delete(...args)),
    patch: (...args) => getAxios().then(axios => axios.patch(...args)),
    request: (...args) => getAxios().then(axios => axios.request(...args))
};
