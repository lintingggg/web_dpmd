<template>
  <div style="
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 24px;
    padding: 48px 24px;
    text-align: center;
    font-family: 'Plus Jakarta Sans', sans-serif;
  ">
    <img
      v-if="image"
      :src="image"
      :alt="title"
      style="width: 200px; max-width: 100%; opacity: 0.7;"
    />

    <div style="display: flex; flex-direction: column; gap: 10px; align-items: center; max-width: 400px;">

      <h2 style="font-size: 20px; font-weight: 700; color: #0f172a; letter-spacing: -0.3px;">
        {{ title }}
      </h2>

      <p style="font-size: 14px; font-weight: 500; color: #646a79; line-height: 1.7;">
        {{ description }}
      </p>

      <button
        v-if="buttonText"
        style="
          margin-top: 8px;
          padding: 12px 24px;
          background: #0f172a;
          color: #ffffff;
          border: none;
          border-radius: 9999px;
          font-size: 14px;
          font-weight: 700;
          cursor: pointer;
          letter-spacing: -0.2px;
          transition: background 0.2s ease;
          box-shadow: 0 4px 16px rgba(15,23,42,0.18);
          font-family: 'Plus Jakarta Sans', sans-serif;
        "
        @click="$emit('action')"
        @mouseenter="$event.target.style.background = '#222a3d'"
        @mouseleave="$event.target.style.background = '#0f172a'"
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