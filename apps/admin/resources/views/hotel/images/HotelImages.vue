<script setup lang="ts">
import { ref } from 'vue'

import AddButton from '~resources/components/AddButton.vue'
import BaseLayout from '~resources/components/BaseLayout.vue'
import axios from '~resources/js/app/api'

const value = ref<string>('Hello world! Images page.')

const showUploadModal = async () => {
  console.log('clicked')
  const resp = await axios.get('/admin/v1/hotel/363/images')
  console.log(resp)
}

const images = ref([
  {
    id: 1,
    name: 'Test',
    url: 'http://bspems.site.gotostans.com/file/bfa612832df2c174a8ab96ab633a0563/',
  },
  {
    id: 2,
    name: 'Test',
    url: 'http://bspems.site.gotostans.com/file/bfa612832df2c174a8ab96ab633a0563/',
  },
  {
    id: 3,
    name: 'Test',
    url: 'http://bspems.site.gotostans.com/file/bfa612832df2c174a8ab96ab633a0563/',
  },
  {
    id: 4,
    name: 'Test',
    url: 'http://bspems.site.gotostans.com/file/bfa612832df2c174a8ab96ab633a0563/',
  },
  {
    id: 5,
    name: 'Test',
    url: 'http://bspems.site.gotostans.com/file/bfa612832df2c174a8ab96ab633a0563/',
  },
])
</script>

<template>
  <BaseLayout title="Afrosiyob">
    <template #header-controls>
      <AddButton
        text="Добавить фотографию"
        @click="showUploadModal"
      />
    </template>

    <template #body>
      <div class="cards">
        <div v-for="image in images" :key="image.id" class="card">
          <div class="edit-info" />
          <div class="image">
            <img :src="image.url" :alt="image.name" class="w-100 h-100">
            <div class="name">
              {{ image.name }}
            </div>
          </div>
          <div class="body">
            <div class="type">
              на <b>1</b> человека
              (<b>x10</b>)
            </div>
            <div class="buttons">
              <a href="http://admin.gts.local/hotels/363/rooms/1624/edit" class="btn btn-light">Редакировать</a>
              <a href="#" class="btn btn-light">Фотографии</a>
            </div>
          </div>
          <!--          <div class="sort-handle ui-sortable-handle">-->
          <!--            <i class="icon">drag_pan</i>-->
          <!--          </div>-->
        </div>
      </div>
    </template>
  </BaseLayout>
</template>

<style>
.cards {
    display: flex;
    flex-wrap: wrap;
    gap: 32px;
}

.cards .card {
    flex-basis: 30%;
    flex-grow: 1;
    overflow: hidden;
}

.card .image {
    height: 200px;
    position: relative;
    background: #F3F3F3 url(http://[::1]:5173/resources/images/nophoto.webp) 50% 50% no-repeat;
}

.card .image .name {
    position: absolute;
    left: 0;
    bottom: 0;
    width: 100%;
    padding: 0.5rem 1rem;
    background: rgba(0, 0, 0, 0.46);
    color: #fff;
}

.card .body {
    padding: 0.5rem 1rem;
}
</style>
