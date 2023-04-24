<script setup lang="ts">

</script>

<template>
  <div class="card card-form">
    <div class="card-header">
      <ul class="nav nav-tabs card-header-tabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button
            id="main-tab"
            class="nav-link"
            data-bs-target="#main"
            aria-controls="main"
            data-bs-toggle="tab"
            type="button"
            role="tab"
            tabindex="-1"
          >
            Порядок проживания
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button
            id="cancellation-tab"
            class="nav-link"
            data-bs-target="#cancellation"
            aria-controls="cancellation"
            data-bs-toggle="tab"
            type="button"
            role="tab"
          >
            Условия отмены
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button
            id="extra-price-tab"
            class="nav-link"
            data-bs-target="#extra-price"
            aria-controls="extra-price"
            data-bs-toggle="tab"
            type="button"
            role="tab"
          >
            Условия наценки
          </button>
        </li>
      </ul>
    </div>
    <div class="card-body">
      <form
        class="tab-content"
      >
        <div
          id="main"
          role="tabpanel"
          class="tab-pane fade show active"
          aria-labelledby="main-tab"
        >
          Порядок проживания
        </div>

        <div
          id="cancellation"
          role="tabpanel"
          class="tab-pane fade"
          aria-labelledby="rules-tab"
        >
          Условия отмены
        </div>

        <div
          id="extra-price"
          role="tabpanel"
          class="tab-pane fade"
          aria-labelledby="rules-tab"
        >
          Наценка
        </div>
      </form>
    </div>
  </div>
</template>
