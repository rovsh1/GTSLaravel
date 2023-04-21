import { createApp, h } from 'vue'

import HotelSettings from '~resources/views/hotel/settings/HotelSettings.vue'

createApp({
  render: () => h(HotelSettings),
}).mount('#hotel-settings')
