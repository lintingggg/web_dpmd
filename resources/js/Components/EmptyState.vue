<template>
  <div class="empty-state">
    <img
      v-if="image"
      :src="image"
      :alt="title"
      class="empty-image"
    />

    <div class="empty-content">

      <h2 class="empty-title">
        {{ title }}
      </h2>

      <p class="empty-description">
        {{ description }}
      </p>

      <button
        v-if="buttonText"
        class="empty-button"
        @click="$emit('action')"
      >
        {{ buttonText }}
      </button>

    </div>
  </div>
</template>

<script setup>

defineProps({

  image: {
    type: String,
    default: ""
  },

  title: {
    type: String,
    default: "Belum Ada Data"
  },

  description: {
    type: String,
    default: "Data akan ditampilkan di sini."
  },

  buttonText: {
    type: String,
    default: ""
  }

})

defineEmits([
  "action"
])

</script>

<style scoped>

.empty-state{

    display:flex;

    flex-direction:column;

    align-items:center;

    justify-content:center;

    gap:24px;

    padding:40px 20px;

    text-align:center;

}

.empty-image{

    width:260px;

    max-width:100%;

}

.empty-content{

    display:flex;

    flex-direction:column;

    gap:12px;

    align-items:center;

}

.empty-title{

    font-size:24px;

    font-weight:600;

    color:#1f2937;

}

.empty-description{

    color:#6b7280;

    line-height:1.6;

    max-width:420px;

}

.empty-button{

    margin-top:10px;

    padding:12px 24px;

    border:none;

    border-radius:8px;

    background:#111827;

    color:white;

    cursor:pointer;

    font-size:15px;

    font-weight:600;

    transition:.2s;

}

.empty-button:hover{

    background:#000;

}

</style>