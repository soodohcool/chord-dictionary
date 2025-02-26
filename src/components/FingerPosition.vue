<template>
  <div
    class="finger"
    :style="{
      left: ((6 - fingerPosition.string) * 25) + 'px',
      top: ((fingerPosition.fret - 0.5) * 30) + 'px'
    }"
  >
    {{ fingerPosition.finger }}
  </div>
</template>

<script setup>
import { defineProps } from 'vue';

// Props with validator
const props = defineProps({
  fingerPosition: {
    type: Object,
    required: true,
    validator: (prop) => {
      return 'string' in prop &&
             'fret' in prop &&
             'finger' in prop;
    }
  }
});
</script>

<style scoped>
.finger {
  position: absolute;
  width: 20px;
  height: 20px;
  border-radius: 50%;
  background-color: #4CAF50;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 14px;
  transform: translate(-50%, -50%);
}
</style>
