import axios from 'axios'

axios.defaults.withCredentials = true

const axiosIns = axios.create({
    baseURL: `${location.protocol}//${location.host}/api/v1/`,
    // You can add your headers here
    // ================================
    // timeout: 1000,
    // headers: { X-header: authData!==null ? `${authData.token_type} ${authData.accessToken}`:'' },
})


export default axiosIns
