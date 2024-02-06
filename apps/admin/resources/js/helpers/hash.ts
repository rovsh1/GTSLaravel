import * as CryptoJS from 'crypto-js'

export const generateHashFromObject = (obj: any): string => {
  const jsonString = JSON.stringify(obj)
  return CryptoJS.SHA256(jsonString).toString()
}
