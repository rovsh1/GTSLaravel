import { downloadFile } from '~lib/download-file'

export default function bootFilesDownloader() {
  $('a.download-file').removeAttr('target')
  $('a.download-file').removeAttr('download')
  $('a.download-file').click(async (e) => {
    e.preventDefault()
    const fileUrl = $(e.target).attr('href')
    const fileName = $(e.target).text()
    if (!fileUrl) return
    await downloadFile(fileUrl, fileName)
  })
}
