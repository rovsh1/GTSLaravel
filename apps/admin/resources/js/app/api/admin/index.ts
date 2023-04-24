import axios from 'axios'

import { ADMIN_DOMAIN } from '~resources/js/app/constants'

// @todo сейчас вообще не используется авторизация (в т.ч. непонятно какой юзер делает запрос)
// axios.defaults.withCredentials = true

const axiosIns = axios.create({
  baseURL: `${location.protocol}//${ADMIN_DOMAIN}/`,
  // You can add your headers here
  // ================================
  // timeout: 1000,
  headers: {
    // X-header: authData!==null ? `${authData.token_type} ${authData.accessToken}`:''
    'Content-Type': 'application/json; charset=utf-8',
    Accept: 'application/json',
  },
})

export default axiosIns
