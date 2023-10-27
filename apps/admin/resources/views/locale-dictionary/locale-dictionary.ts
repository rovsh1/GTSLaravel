import { createVueInstance } from '~lib/vue'

import LocaleDictionary from './LocaleDictionary.vue'

import '~resources/views/main'

createVueInstance({
  rootComponent: LocaleDictionary,
  rootContainer: '#locale-dictionary',
})
