import type { RawEditorOptions } from 'tinymce'

type Params = {
  contentCSSPath: string
}
export const getDefaultOptions = ({ contentCSSPath }: Params): RawEditorOptions => ({
  skin: false,
  language: 'ru',
  promotion: false,
  branding: false,
  elementpath: false,
  statusbar: false,
  height: 600,
  resize: true, // enable vertical
  image_advtab: true,
  image_title: true,
  image_dimensions: false,
  automatic_uploads: true,
  file_picker_types: 'file image media',
  external_filemanager_url: '/filemanager',
  // remove_script_host: false,
  // forced_root_block: false,
  // color_map: [
  //     '000000', 'Black',
  //     'ffffff', 'White',
  //     'var(--main)', 'Main',
  //     'var(--text)', 'Text',
  //     'var(--link)', 'Link',
  //     'var(--background)', 'Background',
  //     'var(--error)', 'Error',
  //     'var(--cancel)', 'Cancel',
  //     'var(--success)', 'Success',
  //     'var(--alert)', 'Alert'
  // ],
  // extended_valid_elements: 'i[class],span[class]',
  relative_urls: false,
  remove_script_host: false,
  convert_urls: false,
  document_base_url: '/',
  fontsize_formats: '.5rem .75rem 1rem 1.5rem 2rem',
  plugins: [
    'advlist',
    'autolink',
    'lists',
    'link',
    'image',
    'charmap',
    'preview',
    'anchor',
    'searchreplace',
    'visualblocks',
    'code',
    'fullscreen',
    'insertdatetime',
    'media',
    'table',
    'filemanager',
  ],
  toolbar: [// insertfile
    'styleselect fontsizeselect | bold italic removeformat | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | insertfile image link | appvariables mastergallery',
  ],
  body_class: 'page-content',
  content_css: contentCSSPath,
})
