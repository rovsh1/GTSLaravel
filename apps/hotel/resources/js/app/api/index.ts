import axios from 'axios'

import { ADMIN_API_URL } from '~lib/env'

// @todo сейчас вообще не используется авторизация (в т.ч. непонятно какой юзер делает запрос)
// axios.defaults.withCredentials = true

const axiosIns = axios.create({
  baseURL: ADMIN_API_URL,
  // You can add your headers here
  // ================================
  // timeout: 1000,
  headers: {
    // X-header: authData!==null ? `${authData.token_type} ${authData.accessToken}`:''
    'Content-Type': 'application/json; charset=utf-8',
    'Accept': 'application/json',
  },
})

export default axiosIns
