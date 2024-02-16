export const downloadFile = async (url: string, filename: string): Promise<void> => {
  const response = await fetch(url)
  const blob = await response.blob()
  const href = URL.createObjectURL(blob)
  const anchorElement = document.createElement('a')
  anchorElement.href = href
  anchorElement.download = filename
  document.body.appendChild(anchorElement)
  anchorElement.click()
  document.body.removeChild(anchorElement)
  URL.revokeObjectURL(href)
}
