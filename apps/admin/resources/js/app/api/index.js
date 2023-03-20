import axios from 'axios'

axios.defaults.withCredentials = true

const axiosIns = axios.create({
    // You can add your headers here
    // ================================
    baseURL: `${location.protocol}//${location.host}/api/v1/`,
    // timeout: 1000,
    // headers: { X-header: authData!==null ? `${authData.token_type} ${authData.accessToken}`:'' },
})


export default axiosIns
