const detectMacOS = () => navigator.platform === 'MacIntel'

export const usePlatformDetect = () => ({
  isMacOS: detectMacOS(),
})
