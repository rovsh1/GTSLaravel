// TODO figure out how to do the same in `vite dev`
import buildManifest from '../../../public/build/manifest.json'

type BuildManifest = Record<string, {
  src: string
  file: string
  isEntry?: boolean
}>

const buildDirectory = '/build'

const manifestPath = `${buildDirectory}/manifest.json`

const fetchManifest = () => new Promise<BuildManifest>((resolve) => {
  fetch(manifestPath)
    .then<BuildManifest>((response) => response.json())
    .then((manifest) => resolve(manifest))
})

export const getFileFromManifest = async (src: keyof typeof buildManifest) => {
  const manifest = await fetchManifest()
  const item = manifest[src]
  if (item === undefined) {
    throw new Error(`File ${src} not found in '${manifestPath}'`)
  }
  const { file } = item
  if (file === undefined) {
    throw new Error(`File ${src} is found in '${manifestPath}', but there is no 'file' property`)
  }
  return `${buildDirectory}/${file}`
}
