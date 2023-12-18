import { TinyMCE } from 'tinymce'

import { getFileFromManifest } from '~resources/js/app/build-manifest'

import 'tinymce/skins/ui/oxide/skin.min.css'

let loadedFlag = false

export const loadEditor = async (): Promise<TinyMCE> => {
  if (window.tinymce) {
    return window.tinymce
  }
  if (!loadedFlag) {
    loadedFlag = true
    return new Promise((resolve) => {
      const head = document.getElementsByTagName('head')[0]
      const script = document.createElement('script')
      script.type = 'text/javascript'
      getFileFromManifest('resources/assets/tinymce.js').then((file) => {
        script.src = file
        script.onload = function () {
          resolve(window.tinymce)
        }
        head.appendChild(script)
      })
    })
  }
  return new Promise((resolve) => {
    // eslint-disable-next-line unused-imports/no-unused-vars
    const timer = () => {
      setTimeout(() => {
        if (window.tinymce) {
          resolve(window.tinymce)
        } else {
          timer()
        }
      }, 500)
    }
  })
}
