import { createVueInstance } from '~resources/lib/vue'

import HotelImages from './HotelImages.vue'

import '~resources/views/main'

createVueInstance({
  rootComponent: HotelImages,
  rootContainer: '#hotel-images',
})
