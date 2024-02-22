<script setup lang="ts">

export type Action = {
  icon?: string
  title: string
  callback: () => void
}

withDefaults(defineProps<{
  dropdownButtonIcon?: string
  dropdownButtonText?: string
  actions?: Action[]
  canEdit?: boolean
}>(), {
  dropdownButtonIcon: 'more_vert',
  dropdownButtonText: '',
  actions: undefined,
  canEdit: true,
})

</script>

<template>
  <div v-if="canEdit" class="dropdown menu-actions-wrapper">
    <a href="#" class="d-inline" role="button" data-bs-toggle="dropdown" aria-expanded="false">
      <i class="icon">{{ dropdownButtonIcon }}</i> {{ dropdownButtonText }}
    </a>
    <ul
      v-if="actions?.length"
      class="dropdown-menu"
      aria-labelledby="menu-actions"
      data-popper-placement="bottom-start"
    >
      <li v-for="(action, idx) in actions" :key="idx">
        <a
          class="dropdown-item"
          href="#"
          @click.prevent="action.callback()"
        >
          <i v-if="action.icon" class="icon">{{ action.icon }}</i>
          {{ action.title }}
        </a>
      </li>
    </ul>
  </div>
</template>

<style scoped lang="scss">
a {
  line-height: 1;

  i {
    vertical-align: top;
    margin-left: 0.313rem;
    font-weight: bold;
    font-size: 1.125rem;
  }
}

ul {
  position: absolute;
  inset: 0 auto auto 0;
  margin: 0;
  transform: translate3d(16px, 34px, 0);
}
</style>
