import AddressBar from './addressbar'
import Container from './container'
import StatusBar from './statusbar'
import EventsTrait from './support/events-trait'
import Toolbar from './toolbar'
import Reader from '../uploader/reader'

// import {getMimeImage, isImage} from "../uploader/functions";

const homePath = ''

class Manager {
  #params

  #el

  constructor(el, params) {
    this.#params = params
    this.#el = $('<div class="filemanager loading"></div>').appendTo(el)

    this.toolbar = new Toolbar(this)
    this.addressbar = new AddressBar(this)
    this.statusbar = new StatusBar(this)
    this.container = new Container(this)

    this.#el
      .append(this.toolbar.el)
      .append(this.addressbar.el)
      .append(this.container.el)
      .append(this.statusbar.el)

    this.loadPath(homePath)
    this.setView('grid')

    this.bind('selection-changed', function () {
      this.statusbar.setSelectedCount(this.getSelected().length)
    })
  }

  setLoading(flag) {
    this.#el[flag ? 'addClass' : 'removeClass']('loading')
  }

  isHome() { return this.path === homePath }

  goHome() { this.loadPath(homePath) }

  goUp() {
    if (this.isHome()) return

    const a = this.path.split('/')
    a.pop()
    a.pop()

    this.loadPath(a.join('/'))
  }

  setView(view) {
    this.container.setView(view)
    this.statusbar.setView(view)
  }

  createFolder(name) {
    const self = this
    this.setLoading(true)

    $.post('/filemanager/folder', {
      name,
      path: this.path,
    }, (r) => {
      if (r.error) {
        self.setLoading(false)
        alert(r.error)
      } else self.refresh()
    })
  }

  deleteSelected() {
    const self = this
    this.setLoading(true)

    $.post('/filemanager/delete', {
      files: this.getSelected().map((f) => f.name),
      path: this.path,
    }, (r) => {
      self.refresh()
    })
  }

  upload(files) {
    const self = this
    const setProgress = function (html) {
      self.statusbar.setProgress(html)
    }

    const reader = new Reader()

    reader
      .bind('start', () => { setProgress('Подготовка файлов ') })
      .bind('progress', (p) => { setProgress(`Загрузка файлов ${p}%`) })
      .bind('complete', (data) => {
        setProgress('')
        self.refresh()
        self.toolbar.resetUpload()
      })
      .bind('error', () => {
        setProgress('Ошибка')
        self.toolbar.resetUpload()
      })
      .upload(files, {
        url: '/filemanager/upload',
        data: { path: this.path },
      })
  }

  rename(file, name) {
    $.post('/filemanager/rename', {
      name,
      file: file.name,
      path: this.path,
    }, (r) => {
      if (r.error) alert(r.error)
      else file.rename(r.name)
    })
  }

  moveSelectedTo(path) {
    this.setLoading(true)
    $.post('/filemanager/move', {
      files: this.getSelected().map((file) => file.name),
      folder: path,
      path: this.path,
    }, (r) => { this.refresh() })
  }

  refresh() {
    this.deselectAll()
    this.setLoading(true)

    $.getJSON('/filemanager/files', {
      path: this.path,
      page: this.statusbar.page,
      step: this.statusbar.step,
      r: Math.random(),
    }, (r) => {
      this.setLoading(false)
      this.container.setRelativePath(r.urlPath)
      this.container.setFiles(r.files)
      // this.container.setCount(r.count);
      this.statusbar.setCount(r.count)
      this.statusbar.setSelectedCount(0)
      this.container.search(this.addressbar.searchValue)
    })
  }

  loadPath(path) {
    this.path = path
    this.addressbar.setPath(this.path)
    this.statusbar.setPage(1)

    this.refresh()
  }

  openPath(path) { return this.loadPath(this.isHome() ? path : `${this.path}/${path}`) }

  loadPage(page) {
    this.statusbar.setPage(page)
    this.refresh()
  }

  deselectAll() {
    this.statusbar.setSelectedCount(0)
    this.container.deselectAll()
    this.trigger('selection-changed')
  }

  getSelected() { return this.container.getSelected() }

  filterByType(type) {
    this.deselectAll()
    this.addressbar.setFilter(type)
    this.container.filterByType(type)
  }

  search(term) { this.container.search(term) }
}

Object.assign(Manager.prototype, EventsTrait)

export default Manager
